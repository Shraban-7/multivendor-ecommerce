<?php

namespace App\Domain\Support\Services;

use App\Domain\Auth\Models\Admin;
use App\Domain\Support\Enums\TicketPriority;
use App\Domain\Support\Enums\TicketStatus;
use App\Domain\Support\Models\SupportTicket;
use App\Domain\Support\Models\SupportTicketAttachment;
use App\Domain\Support\Models\SupportTicketEvent;
use App\Domain\Support\Models\SupportTicketMessage;
use App\Domain\Vendor\Models\Seller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use RuntimeException;

/**
 * Centralises the support ticket lifecycle. Controllers should call this service
 * rather than touching models directly so state transitions + audit + notifications
 * stay consistent.
 */
class SupportTicketService
{
    public function createTicket(
        Seller $seller,
        string $subject,
        string $description,
        string $category = 'other',
        TicketPriority $priority = TicketPriority::NORMAL,
        ?int $orderId = null,
    ): SupportTicket {
        return DB::transaction(function () use ($seller, $subject, $description, $category, $priority, $orderId) {
            $slaHours = (int) (config("marketplace.support.sla_hours.{$priority->value}") ?? 48);

            $ticket = SupportTicket::create([
                'ticket_number' => $this->generateTicketNumber(),
                'subject' => $subject,
                'description' => $description,
                'seller_id' => $seller->id,
                'category' => $category,
                'priority' => $priority->value,
                'status' => TicketStatus::OPEN->value,
                'order_id' => $orderId,
                'sla_due_at' => now()->addHours($slaHours),
            ]);

            // Internal-context message = the opening description.
            SupportTicketMessage::create([
                'support_ticket_id' => $ticket->id,
                'sender_type' => SupportTicketMessage::SENDER_SELLER,
                'sender_id' => $seller->id,
                'body' => $description,
            ]);

            SupportTicketEvent::log(
                $ticket, 'created', 'seller', $seller->id,
                null, TicketStatus::OPEN->value, 'Ticket opened by seller'
            );

            $this->notifyAdminsNewTicket($ticket);

            $ticket->update(['last_message_at' => now()]);

            return $ticket->fresh();
        });
    }

    public function reply(
        SupportTicket $ticket,
        string $senderType,
        ?int $senderId,
        string $body,
        bool $isInternal = false,
        array $attachmentPaths = [],
        ?string $statusAfter = null,
        ?string $note = null,
    ): SupportTicketMessage {
        return DB::transaction(function () use ($ticket, $senderType, $senderId, $body, $isInternal, $attachmentPaths, $statusAfter, $note) {
            $message = SupportTicketMessage::create([
                'support_ticket_id' => $ticket->id,
                'sender_type' => $senderType,
                'sender_id' => $senderId,
                'body' => $body,
                'is_internal_note' => $isInternal,
            ]);

            foreach ($attachmentPaths as $att) {
                SupportTicketAttachment::create([
                    'support_ticket_message_id' => $message->id,
                    'disk' => $att['disk'] ?? 'public',
                    'path' => $att['path'],
                    'original_name' => $att['original_name'] ?? basename($att['path']),
                    'mime' => $att['mime'] ?? null,
                    'size' => $att['size'] ?? null,
                ]);
            }

            $from = $ticket->status instanceof TicketStatus ? $ticket->status->value : (string) $ticket->status;

            // First admin reply timestamps the SLA.
            if ($senderType === SupportTicketMessage::SENDER_ADMIN && $ticket->first_admin_reply_at === null) {
                $ticket->update(['first_admin_reply_at' => now()]);
            }
            if ($senderType === SupportTicketMessage::SENDER_SELLER) {
                $ticket->update(['seller_last_reply_at' => now()]);
            }
            if ($senderType === SupportTicketMessage::SENDER_ADMIN) {
                $ticket->update(['admin_last_reply_at' => now()]);
            }

            // Status auto-promotion / demotion based on who replied.
            if ($statusAfter) {
                $target = TicketStatus::tryFrom($statusAfter);
                if (! $target) {
                    throw new InvalidArgumentException("Unknown status: {$statusAfter}");
                }
                if ($this->canTransition($from, $target->value)) {
                    $ticket->update(['status' => $target->value]);
                    SupportTicketEvent::log(
                        $ticket, 'status_change', $senderType, $senderId,
                        $from, $target->value, $note
                    );
                }
            } else {
                $target = match ($senderType) {
                    SupportTicketMessage::SENDER_SELLER => TicketStatus::AWAITING_ADMIN,
                    SupportTicketMessage::SENDER_ADMIN => TicketStatus::AWAITING_SELLER,
                    default => null,
                };

                if ($target && $target->value !== $from && $this->canTransition($from, $target->value)) {
                    $ticket->update(['status' => $target->value]);
                    SupportTicketEvent::log(
                        $ticket, 'status_change', $senderType, $senderId,
                        $from, $target->value, 'Auto-transition on reply'
                    );
                }
            }

            $ticket->update([
                'last_message_at' => now(),
                'reply_count' => $ticket->reply_count + 1,
            ]);

            // Notifications
            if (! $isInternal) {
                $this->notifyCounterparty($ticket, $message, $senderType);
            }

            return $message->fresh(['attachments']);
        });
    }

    public function changeStatus(
        SupportTicket $ticket,
        TicketStatus $newStatus,
        string $actorType,
        ?int $actorId = null,
        ?string $note = null,
    ): SupportTicket {
        $from = $ticket->status instanceof TicketStatus ? $ticket->status->value : (string) $ticket->status;

        if (! $this->canTransition($from, $newStatus->value)) {
            throw new RuntimeException("Cannot transition ticket from {$from} to {$newStatus->value}.");
        }

        DB::transaction(function () use ($ticket, $newStatus, $from, $actorType, $actorId, $note) {
            $updates = ['status' => $newStatus->value];

            if ($newStatus->value === TicketStatus::RESOLVED->value) {
                $updates['resolved_at'] = now();
            }
            if ($newStatus->value === TicketStatus::CLOSED->value || $newStatus->value === TicketStatus::REJECTED->value) {
                $updates['closed_at'] = now();
            }

            $ticket->update($updates);
            SupportTicketEvent::log(
                $ticket, 'status_change', $actorType, $actorId, $from, $newStatus->value, $note
            );

            // System message in the thread for visibility
            SupportTicketMessage::create([
                'support_ticket_id' => $ticket->id,
                'sender_type' => SupportTicketMessage::SENDER_SYSTEM,
                'body' => 'Status changed: '.TicketStatus::tryFrom($from)?->label().' → '.$newStatus->label().
                    ($note ? "\n\n{$note}" : ''),
                'is_status_change' => true,
                'meta' => ['from' => $from, 'to' => $newStatus->value],
            ]);

            $this->notifyCounterpartyOnStatusChange($ticket, $from, $newStatus->value);
        });

        return $ticket->fresh();
    }

    public function changePriority(
        SupportTicket $ticket,
        TicketPriority $priority,
        string $actorType,
        ?int $actorId = null,
    ): SupportTicket {
        $from = $ticket->priority instanceof TicketPriority
            ? $ticket->priority->value
            : (string) $ticket->priority;

        $ticket->update([
            'priority' => $priority->value,
            'sla_due_at' => now()->addHours((int) (config("marketplace.support.sla_hours.{$priority->value}") ?? 48)),
        ]);

        SupportTicketEvent::log(
            $ticket, 'priority_change', $actorType, $actorId, $from, $priority->value
        );

        return $ticket->fresh();
    }

    public function assignTo(SupportTicket $ticket, int $adminId, string $actorType, ?int $actorId = null): SupportTicket
    {
        $from = $ticket->assigned_admin_id;
        $ticket->update(['assigned_admin_id' => $adminId]);

        SupportTicketEvent::log(
            $ticket, 'assignment', $actorType, $actorId,
            $from !== null ? (string) $from : null,
            (string) $adminId
        );

        if (config('marketplace.support.allow_internal_notes', true)) {
            SupportTicketMessage::create([
                'support_ticket_id' => $ticket->id,
                'sender_type' => SupportTicketMessage::SENDER_SYSTEM,
                'body' => 'Assigned to admin #'.$adminId,
                'is_status_change' => true,
                'is_internal_note' => true,
            ]);
        }

        return $ticket->fresh();
    }

    public function resolve(
        SupportTicket $ticket,
        string $actorType,
        ?int $actorId = null,
        ?string $resolutionNote = null,
    ): SupportTicket {
        return $this->changeStatus($ticket, TicketStatus::RESOLVED, $actorType, $actorId, $resolutionNote);
    }

    public function close(SupportTicket $ticket, string $actorType, ?int $actorId = null, ?string $note = null): SupportTicket
    {
        return $this->changeStatus($ticket, TicketStatus::CLOSED, $actorType, $actorId, $note);
    }

    public function reopen(
        SupportTicket $ticket,
        string $actorType,
        ?int $actorId = null,
        ?string $reason = null,
    ): SupportTicket {
        $from = $ticket->status instanceof TicketStatus ? $ticket->status->value : (string) $ticket->status;

        if (! $this->canTransition($from, TicketStatus::OPEN->value)) {
            throw new RuntimeException("Cannot reopen ticket from {$from}.");
        }

        $ticket->update([
            'status' => TicketStatus::OPEN->value,
            'resolved_at' => null,
            'closed_at' => null,
        ]);

        SupportTicketEvent::log(
            $ticket, 'reopened', $actorType, $actorId, $from, TicketStatus::OPEN->value, $reason
        );

        return $ticket->fresh();
    }

    public function canTransition(string $from, string $to): bool
    {
        $allowed = [
            TicketStatus::OPEN->value => [
                TicketStatus::IN_PROGRESS->value,
                TicketStatus::AWAITING_SELLER->value,
                TicketStatus::AWAITING_ADMIN->value,
                TicketStatus::RESOLVED->value,
                TicketStatus::CLOSED->value,
                TicketStatus::REJECTED->value,
            ],
            TicketStatus::IN_PROGRESS->value => [
                TicketStatus::AWAITING_SELLER->value,
                TicketStatus::AWAITING_ADMIN->value,
                TicketStatus::RESOLVED->value,
                TicketStatus::CLOSED->value,
                TicketStatus::REJECTED->value,
            ],
            TicketStatus::AWAITING_SELLER->value => [
                TicketStatus::IN_PROGRESS->value,
                TicketStatus::AWAITING_ADMIN->value,
                TicketStatus::RESOLVED->value,
                TicketStatus::CLOSED->value,
            ],
            TicketStatus::AWAITING_ADMIN->value => [
                TicketStatus::IN_PROGRESS->value,
                TicketStatus::AWAITING_SELLER->value,
                TicketStatus::RESOLVED->value,
                TicketStatus::CLOSED->value,
            ],
            TicketStatus::RESOLVED->value => [
                TicketStatus::CLOSED->value,
                TicketStatus::OPEN->value, // reopen
            ],
            TicketStatus::CLOSED->value => [
                TicketStatus::OPEN->value, // reopen
            ],
            TicketStatus::REJECTED->value => [
                TicketStatus::OPEN->value, // reopen
            ],
        ];

        return in_array($to, $allowed[$from] ?? [], true);
    }

    public function generateTicketNumber(): string
    {
        $prefix = (string) config('marketplace.support.ticket_prefix', 'SUP');
        $date = now()->format('ymd');
        $sequence = (int) (DB::table('support_tickets')->whereDate('created_at', today())->count()) + 1;

        return sprintf('%s-%s-%04d', $prefix, $date, $sequence);
    }

    protected function notifyAdminsNewTicket(SupportTicket $ticket): void
    {
        try {
            $admins = Admin::query()->pluck('id')->all();
            foreach ($admins as $adminId) {
                notify_admin(
                    $adminId,
                    'New Support Ticket',
                    "Seller raised ticket {$ticket->ticket_number}: {$ticket->subject}",
                    'support',
                    $ticket->id,
                );
            }
        } catch (\Throwable $e) {
            Log::warning('admin notify failed (new support ticket)', [
                'ticket_id' => $ticket->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    protected function notifyCounterparty(SupportTicket $ticket, SupportTicketMessage $message, string $senderType): void
    {
        try {
            if ($senderType === SupportTicketMessage::SENDER_SELLER && $ticket->seller_id) {
                if (config('marketplace.performance.auto_recompute', true) === false) {
                    // Quietly degrade — don't notify if system disabled
                }
                notify_seller(
                    $ticket->seller_id,
                    'Support Update',
                    "Ticket {$ticket->ticket_number}: seller replied.",
                    'support',
                    $ticket->id,
                );
            } elseif ($senderType === SupportTicketMessage::SENDER_ADMIN) {
                if ($ticket->assigned_admin_id) {
                    notify_admin(
                        $ticket->assigned_admin_id,
                        'Ticket Reply Recorded',
                        "Reply added to {$ticket->ticket_number}",
                        'support',
                        $ticket->id,
                    );
                }
                // Notify seller of admin reply
                if ($ticket->seller_id) {
                    notify_seller(
                        $ticket->seller_id,
                        'Support Update',
                        "Ticket {$ticket->ticket_number}: admin replied. {$ticket->subject}",
                        'support',
                        $ticket->id,
                    );
                }
            }
        } catch (\Throwable $e) {
            Log::warning('support ticket notify failed', [
                'ticket_id' => $ticket->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    protected function notifyCounterpartyOnStatusChange(SupportTicket $ticket, string $from, string $to): void
    {
        try {
            if ($ticket->seller_id) {
                notify_seller(
                    $ticket->seller_id,
                    'Support Status Changed',
                    "Ticket {$ticket->ticket_number} moved to {$to}",
                    'support',
                    $ticket->id,
                );
            }
            if ($ticket->assigned_admin_id) {
                notify_admin(
                    $ticket->assigned_admin_id,
                    'Support Status Changed',
                    "Ticket {$ticket->ticket_number} moved to {$to}",
                    'support',
                    $ticket->id,
                );
            }
        } catch (\Throwable $e) {
            Log::warning('support status-change notify failed', [
                'ticket_id' => $ticket->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
