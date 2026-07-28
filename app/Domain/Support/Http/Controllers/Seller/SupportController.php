<?php

namespace App\Domain\Support\Http\Controllers\Seller;

use App\Domain\Support\Enums\TicketCategory;
use App\Domain\Support\Enums\TicketPriority;
use App\Domain\Support\Models\SupportTicket;
use App\Domain\Support\Models\SupportTicketMessage;
use App\Domain\Support\Services\SupportTicketService;
use App\Domain\Vendor\Models\Seller;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    public function __construct(
        private readonly SupportTicketService $service,
    ) {}

    public function index(Request $request)
    {
        $seller = Seller::find(get_seller_id());

        $query = SupportTicket::query()
            ->with(['admin', 'latestMessage' => function ($q) {
                $q->latest('created_at');
            }])
            ->where('seller_id', $seller->id)
            ->latest('last_message_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        if ($request->filled('search')) {
            $term = '%'.$request->search.'%';
            $query->where(function ($q) use ($term) {
                $q->where('ticket_number', 'like', $term)
                    ->orWhere('subject', 'like', $term);
            });
        }

        $tickets = $query->paginate(15)->withQueryString();

        $counts = [
            'total' => SupportTicket::where('seller_id', $seller->id)->count(),
            'open' => SupportTicket::where('seller_id', $seller->id)->open()->count(),
            'awaiting_admin' => SupportTicket::where('seller_id', $seller->id)
                ->where('status', 'awaiting_admin')->count(),
            'resolved' => SupportTicket::where('seller_id', $seller->id)
                ->where('status', 'resolved')->count(),
            'overdue' => SupportTicket::where('seller_id', $seller->id)
                ->open()
                ->where('sla_due_at', '<', now())
                ->count(),
        ];

        return view('seller.support.index', compact('tickets', 'counts', 'seller'));
    }

    public function create()
    {
        return view('seller.support.create', [
            'categories' => TicketCategory::labels(),
            'priorities' => array_map(fn (TicketPriority $p) => $p->label(), TicketPriority::cases()),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:200',
            'description' => 'required|string|max:10000',
            'category' => 'required|string|in:'.implode(',', array_map(fn ($c) => $c->value, TicketCategory::cases())),
            'priority' => 'required|string|in:'.implode(',', array_map(fn ($p) => $p->value, TicketPriority::cases())),
            'order_id' => 'nullable|exists:orders,id',
        ]);

        $seller = Seller::find(get_seller_id());
        $priority = TicketPriority::from($validated['priority']);

        $ticket = $this->service->createTicket(
            $seller,
            $validated['subject'],
            $validated['description'],
            $validated['category'],
            $priority,
            $validated['order_id'] ?? null,
        );

        return redirect()
            ->route('seller.support.show', $ticket)
            ->with('success', "Ticket {$ticket->ticket_number} created. We'll be in touch soon.");
    }

    public function show(SupportTicket $ticket)
    {
        if ($ticket->seller_id !== get_seller_id()) {
            abort(403);
        }

        $ticket->load(['messages.attachments', 'messages.sender', 'events.actor', 'admin']);

        return view('seller.support.show', compact('ticket'));
    }

    public function reply(Request $request, SupportTicket $ticket)
    {
        if ($ticket->seller_id !== get_seller_id()) {
            abort(403);
        }

        $validated = $request->validate([
            'body' => 'required|string|max:10000',
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
                SupportTicketMessage::SENDER_SELLER,
                (int) get_seller_id(),
                $validated['body'],
                false,
                $attachments,
            );
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }

        return back()->with('success', 'Reply sent.');
    }

    public function resolve(SupportTicket $ticket)
    {
        if ($ticket->seller_id !== get_seller_id()) {
            abort(403);
        }

        if (! config('marketplace.support.allow_self_resolve', true)) {
            return back()->with('error', 'Self-resolve is disabled.');
        }

        try {
            $this->service->resolve(
                $ticket,
                SupportTicketMessage::SENDER_SELLER,
                (int) get_seller_id(),
            );
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Ticket marked as resolved.');
    }

    public function reopen(SupportTicket $ticket)
    {
        if ($ticket->seller_id !== get_seller_id()) {
            abort(403);
        }

        $this->service->reopen(
            $ticket,
            SupportTicketMessage::SENDER_SELLER,
            (int) get_seller_id(),
            'Reopened by seller'
        );

        return back()->with('success', 'Ticket reopened.');
    }
}
