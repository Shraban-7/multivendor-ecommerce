<?php

use App\Domain\Auth\Models\Admin;
use App\Domain\Support\Enums\TicketPriority;
use App\Domain\Support\Enums\TicketStatus;
use App\Domain\Support\Models\SupportTicketEvent;
use App\Domain\Support\Models\SupportTicketMessage;
use App\Domain\Support\Services\SupportTicketService;
use App\Domain\Vendor\Models\Seller;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

function suSeller(array $attrs = []): Seller
{
    static $i = 0;
    $i++;

    return Seller::create(array_merge([
        'name' => 'Owner '.Str::random(4),
        'username' => 'seller_su_'.Str::random(6).$i,
        'email' => 'selleru'.$i.'@example.test',
        'phone' => '017'.str_pad((string) random_int(10000000, 99999999), 8, '0', STR_PAD_LEFT),
        'password' => Hash::make('password'),
        'business_name' => 'Biz SU '.$i,
        'business_email' => 'bizsu'.$i.'@example.test',
        'business_address' => '123 Test',
        'nid_no' => str_pad((string) random_int(1, 9999999999), 10, '0', STR_PAD_LEFT),
        'is_active' => 1,
        'status' => Seller::ACTIVE,
        'code' => strtoupper(Str::random(4)),
        'balance' => 0,
    ], $attrs));
}

function suAdmin(array $attrs = []): Admin
{
    static $i = 0;
    $i++;

    return Admin::create(array_merge([
        'name' => 'Admin '.Str::random(4),
        'username' => 'adminsu_'.Str::random(6).$i,
        'email' => 'admin'.$i.'@example.test',
        'password' => Hash::make('password'),
        'role_id' => 1,
    ], $attrs));
}

test('createTicket generates sequential ticket number with prefix', function () {
    $seller = suSeller();
    $service = app(SupportTicketService::class);

    $ticket1 = $service->createTicket($seller, 'T1', 'Body 1', 'order', TicketPriority::NORMAL);
    $ticket2 = $service->createTicket($seller, 'T2', 'Body 2', 'order', TicketPriority::HIGH);

    expect($ticket1->ticket_number)->toStartWith('SUP-'.now()->format('ymd').'-')
        ->and($ticket2->ticket_number)->not->toBe($ticket1->ticket_number)
        ->and((int) $ticket1->reply_count)->toBe(0)
        ->and($ticket1->sla_due_at->isFuture())->toBeTrue()
        ->and($ticket1->sla_due_at->diffInHours(now(), true))->toBeGreaterThan(40);
});

test('ticket moves to awaiting_admin when seller replies, awaiting_seller when admin replies', function () {
    $seller = suSeller();
    $admin = suAdmin();
    $svc = app(SupportTicketService::class);

    $ticket = $svc->createTicket($seller, 'Payout issue', 'Body', 'payment', TicketPriority::NORMAL);
    expect($ticket->status)->toBe(TicketStatus::OPEN);

    $svc->reply($ticket, SupportTicketMessage::SENDER_ADMIN, $admin->id, 'Hi');
    $ticket->refresh();
    expect($ticket->status)->toBe(TicketStatus::AWAITING_SELLER);
    expect((float) $ticket->first_admin_reply_at->diffInSeconds(now()))->toBeLessThan(5.0);

    $svc->reply($ticket, SupportTicketMessage::SENDER_SELLER, $seller->id, 'More details');
    $ticket->refresh();
    expect($ticket->status)->toBe(TicketStatus::AWAITING_ADMIN);
});

test('admin reply creates message + sender, increments reply_count', function () {
    $seller = suSeller();
    $admin = suAdmin();
    $svc = app(SupportTicketService::class);

    $ticket = $svc->createTicket($seller, 'Q', 'B', 'technical', TicketPriority::URGENT);
    $message = $svc->reply($ticket, SupportTicketMessage::SENDER_ADMIN, $admin->id, 'Looking into it', false, []);

    expect($message->exists)->toBeTrue()
        ->and($message->isFromAdmin())->toBeTrue()
        ->and($message->body)->toBe('Looking into it');

    expect($ticket->refresh()->reply_count)->toBe(1);
});

test('priority change updates sla_due_at based on config', function () {
    $seller = suSeller();
    $admin = suAdmin();
    $svc = app(SupportTicketService::class);

    $ticket = $svc->createTicket($seller, 'Q', 'B', 'other', TicketPriority::LOW);
    $oldDue = $ticket->sla_due_at;

    $svc->changePriority($ticket, TicketPriority::URGENT, 'admin', $admin->id);
    $ticket->refresh();

    expect($ticket->priority)->toBe(TicketPriority::URGENT)
        ->and($ticket->sla_due_at->lessThan($oldDue))->toBeTrue();

    $event = SupportTicketEvent::where('support_ticket_id', $ticket->id)
        ->where('type', 'priority_change')->first();
    expect($event)->not->toBeNull()
        ->and($event->from_value)->toBe('low')
        ->and($event->to_value)->toBe('urgent');
});

test('resolve / close / reopen transitions work and are recorded in events', function () {
    $seller = suSeller();
    $admin = suAdmin();
    $svc = app(SupportTicketService::class);

    $ticket = $svc->createTicket($seller, 'Q', 'B', 'other', TicketPriority::NORMAL);

    $ticket = $svc->resolve($ticket, 'admin', $admin->id, 'All done');
    expect($ticket->status)->toBe(TicketStatus::RESOLVED)
        ->and($ticket->resolved_at)->not->toBeNull();

    $ticket = $svc->close($ticket, 'admin', $admin->id);
    expect($ticket->status)->toBe(TicketStatus::CLOSED)
        ->and($ticket->closed_at)->not->toBeNull();

    $ticket = $svc->reopen($ticket, 'admin', $admin->id, 'Customer came back');
    expect($ticket->status)->toBe(TicketStatus::OPEN)
        ->and($ticket->resolved_at)->toBeNull()
        ->and($ticket->closed_at)->toBeNull();
});

test('state machine: OPEN → RESOLVED is allowed, RESOLVED → IN_PROGRESS is rejected', function () {
    $svc = app(SupportTicketService::class);
    expect($svc->canTransition('open', 'resolved'))->toBeTrue()
        ->and($svc->canTransition('resolved', 'in_progress'))->toBeFalse()
        ->and($svc->canTransition('awaiting_seller', 'resolved'))->toBeTrue()
        ->and($svc->canTransition('awaiting_admin', 'closed'))->toBeTrue()
        ->and($svc->canTransition('closed', 'open'))->toBeTrue()
        ->and($svc->canTransition('open', 'in_progress'))->toBeTrue();
});

test('attachments can be added with a reply and are visible on the message', function () {
    $seller = suSeller();
    $admin = suAdmin();
    $svc = app(SupportTicketService::class);

    $ticket = $svc->createTicket($seller, 'Q', 'B', 'technical', TicketPriority::NORMAL);

    $message = $svc->reply($ticket, SupportTicketMessage::SENDER_ADMIN, $admin->id, 'See attached', false, [
        ['disk' => 'public', 'path' => 'support/'.$ticket->id.'/a.txt', 'original_name' => 'a.txt', 'mime' => 'text/plain', 'size' => 100],
        ['disk' => 'public', 'path' => 'support/'.$ticket->id.'/b.txt', 'original_name' => 'b.txt', 'mime' => 'text/plain', 'size' => 200],
    ]);

    expect($message->attachments)->toHaveCount(2)
        ->and($message->attachments->first()->original_name)->toBe('a.txt');
});

test('assignTo records event and stamps system message', function () {
    $seller = suSeller();
    $admin = suAdmin();
    $svc = app(SupportTicketService::class);

    $ticket = $svc->createTicket($seller, 'Q', 'B', 'other', TicketPriority::NORMAL);
    $ticket = $svc->assignTo($ticket, $admin->id, 'admin', $admin->id);

    expect($ticket->assigned_admin_id)->toBe($admin->id);

    $event = SupportTicketEvent::where('support_ticket_id', $ticket->id)
        ->where('type', 'assignment')->first();
    expect($event)->not->toBeNull()
        ->and((int) $event->to_value)->toBe($admin->id);
});

test('internal note is not visible to seller when replying as seller', function () {
    $seller = suSeller();
    $admin = suAdmin();
    $svc = app(SupportTicketService::class);

    $ticket = $svc->createTicket($seller, 'Q', 'B', 'other', TicketPriority::NORMAL);

    // Admin internal note
    $svc->reply($ticket, SupportTicketMessage::SENDER_ADMIN, $admin->id, 'Will need extra context', true);

    // Seller visible message
    $svc->reply($ticket, SupportTicketMessage::SENDER_SELLER, $seller->id, 'Here are more details');

    $ticket->refresh();
    expect($ticket->messages()->count())->toBe(3); // opening + admin internal + seller reply
    expect($ticket->messages()->where('is_internal_note', true)->count())->toBe(1);
    expect($ticket->messages()->where('is_internal_note', false)->count())->toBe(2);
});
