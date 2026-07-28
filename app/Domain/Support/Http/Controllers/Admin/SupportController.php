<?php

namespace App\Domain\Support\Http\Controllers\Admin;

use App\Domain\Support\Enums\TicketPriority;
use App\Domain\Support\Enums\TicketStatus;
use App\Domain\Support\Models\SupportTicket;
use App\Domain\Support\Models\SupportTicketMessage;
use App\Domain\Support\Services\SupportTicketService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    public function __construct(
        private readonly SupportTicketService $service,
    ) {}

    public function index(Request $request)
    {
        $query = SupportTicket::query()
            ->with(['seller', 'user', 'admin'])
            ->latest('last_message_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('search')) {
            $term = '%'.$request->search.'%';
            $query->where(function ($q) use ($term) {
                $q->where('ticket_number', 'like', $term)
                    ->orWhere('subject', 'like', $term)
                    ->orWhereHas('seller', fn ($s) => $s->where('business_name', 'like', $term));
            });
        }
        if ($request->boolean('overdue')) {
            $query->open()->where('sla_due_at', '<', now());
        }

        $tickets = $query->paginate(20)->withQueryString();

        $stats = SupportTicket::query()
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $overdueCount = SupportTicket::open()->where('sla_due_at', '<', now())->count();

        return view('admin.support.index', compact('tickets', 'stats', 'overdueCount'));
    }

    public function show(SupportTicket $ticket)
    {
        $ticket->load([
            'seller',
            'user',
            'admin',
            'order',
            'messages.attachments',
            'events.actor',
        ]);

        return view('admin.support.show', compact('ticket'));
    }

    public function reply(Request $request, SupportTicket $ticket)
    {
        $validated = $request->validate([
            'body' => 'required|string|max:10000',
            'is_internal' => 'nullable|boolean',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:10240',
        ]);

        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('support/'.$ticket->id, 'public');
                $attachments[] = [
                    'disk' => 'public',
                    'path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'mime' => $file->getMimeType(),
                    'size' => $file->getSize(),
                ];
            }
        }

        try {
            $this->service->reply(
                $ticket,
                SupportTicketMessage::SENDER_ADMIN,
                (int) auth()->id(),
                $validated['body'],
                (bool) ($validated['is_internal'] ?? false),
                $attachments,
            );
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }

        return back()->with('success', 'Reply sent.');
    }

    public function changeStatus(Request $request, SupportTicket $ticket)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:'.implode(',', array_map(fn ($s) => $s->value, TicketStatus::cases())),
            'note' => 'nullable|string|max:2000',
        ]);

        try {
            $this->service->changeStatus(
                $ticket,
                TicketStatus::from($validated['status']),
                SupportTicketMessage::SENDER_ADMIN,
                (int) auth()->id(),
                $validated['note'] ?? null,
            );
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Status updated.');
    }

    public function changePriority(Request $request, SupportTicket $ticket)
    {
        $validated = $request->validate([
            'priority' => 'required|string|in:'.implode(',', array_map(fn ($p) => $p->value, TicketPriority::cases())),
        ]);

        $this->service->changePriority(
            $ticket,
            TicketPriority::from($validated['priority']),
            SupportTicketMessage::SENDER_ADMIN,
            (int) auth()->id(),
        );

        return back()->with('success', 'Priority updated.');
    }

    public function assign(Request $request, SupportTicket $ticket)
    {
        $validated = $request->validate([
            'assigned_admin_id' => 'required|exists:admins,id',
        ]);

        $this->service->assignTo(
            $ticket,
            (int) $validated['assigned_admin_id'],
            SupportTicketMessage::SENDER_ADMIN,
            (int) auth()->id(),
        );

        return back()->with('success', 'Assigned.');
    }

    public function selfAssign(SupportTicket $ticket)
    {
        $this->service->assignTo(
            $ticket,
            (int) auth()->id(),
            SupportTicketMessage::SENDER_ADMIN,
            (int) auth()->id(),
        );

        return back()->with('success', 'Assigned to you.');
    }

    public function resolve(Request $request, SupportTicket $ticket)
    {
        $validated = $request->validate([
            'resolution' => 'nullable|string|max:2000',
        ]);

        $this->service->resolve(
            $ticket,
            SupportTicketMessage::SENDER_ADMIN,
            (int) auth()->id(),
            $validated['resolution'] ?? null,
        );

        return back()->with('success', 'Ticket resolved.');
    }

    public function reopen(Request $request, SupportTicket $ticket)
    {
        $request->validate([
            'reason' => 'nullable|string|max:2000',
        ]);

        $this->service->reopen(
            $ticket,
            SupportTicketMessage::SENDER_ADMIN,
            (int) auth()->id(),
            $request->reason,
        );

        return back()->with('success', 'Ticket reopened.');
    }
}
