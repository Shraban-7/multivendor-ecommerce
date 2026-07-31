@uses('Illuminate\Support\Facades\Storage')

@php
    use App\Domain\Support\Enums\TicketStatus;

    $statusBadge = function () use ($ticket) {
        return [
            'bg'   => $ticket->statusColor(),
            'text' => $ticket->statusLabel(),
        ];
    };
    $statusInfo = $statusBadge();
@endphp
@extends('seller.layouts.app')
@section('title', 'Ticket '.$ticket->ticket_number)

@section('content')

{{-- ═══ HERO ═══ --}}
<section class="bg-white rounded-sm shadow-sm overflow-hidden mb-3 relative">
    <div class="absolute top-0 left-0 right-0 h-1" style="background: linear-gradient(90deg, #0ea5e9, #38bdf8, #7dd3fc);"></div>
    <div class="p-5 lg:p-6 pt-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="min-w-0">
                <nav class="flex items-center gap-1 mb-2 text-xs text-ink-tertiary">
                    <i data-lucide="message-circle" class="text-feedback-info" style="width:12px;height:12px;"></i>
                    <a href="{{ route('seller.support.index') }}" class="hover:text-ink-emphasis">Support</a>
                    <i data-lucide="chevron-right" style="width:12px;height:12px;"></i>
                    <span class="text-ink-soft font-semibold">{{ $ticket->ticket_number }}</span>
                </nav>
                <div class="flex flex-wrap items-center gap-2 mb-1">
                    <h1 class="text-xl font-bold text-ink-emphasis mb-0">{{ $ticket->subject }}</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider bg-{{ $statusInfo['bg'] }}">
                        <span class="w-1.5 h-1.5 rounded-full bg-current opacity-70 me-1.5"></span>
                        {{ $statusInfo['text'] }}
                    </span>
                </div>
                <p class="text-sm text-ink-secondary mb-0">
                    <i data-lucide="hash" style="width:11px;height:11px;" class="me-1 align-text-bottom text-ink-tertiary"></i> {{ $ticket->ticket_number }}
                    &nbsp;·&nbsp; <i data-lucide="calendar" style="width:11px;height:11px;" class="me-1 align-text-bottom text-ink-tertiary"></i> Open {{ $ticket->created_at->diffForHumans() }}
                </p>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('seller.support.index') }}" class="btn btn-light">
                    <i data-lucide="arrow-left" style="width:16px;height:16px;"></i> Back
                </a>
            </div>
        </div>
    </div>
</section>

{{-- Flash messages --}}
@if (session('success'))
    <div class="px-4 py-2 rounded-sm bg-feedback-success/10 border border-feedback-success/20 text-feedback-success text-sm mb-3 alert-dismissible fade show">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="px-4 py-2 rounded-sm bg-feedback-danger/10 border border-feedback-danger/20 text-feedback-danger text-sm mb-3 alert-dismissible fade show">{{ session('error') }}</div>
@endif

{{-- ═══ META TILES ═══ --}}
<section class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-3">
    {{-- Status --}}
    <article class="bg-white rounded-sm shadow-sm overflow-hidden relative">
        <div class="absolute top-0 left-0 right-0 h-1" style="background-color: #0ea5e9;"></div>
        <div class="p-4 pt-5">
            <div class="flex items-center justify-between mb-1">
                <span class="text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider">Status</span>
                <i data-lucide="shield" class="text-ink-tertiary" style="width:13px;height:13px;"></i>
            </div>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider bg-{{ $statusInfo['bg'] }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current opacity-70 me-1.5"></span>
                {{ $statusInfo['text'] }}
            </span>
        </div>
    </article>
    {{-- Priority --}}
    <article class="bg-white rounded-sm shadow-sm overflow-hidden relative">
        <div class="absolute top-0 left-0 right-0 h-1" style="background-color: #fb923c;"></div>
        <div class="p-4 pt-5">
            <div class="flex items-center justify-between mb-1">
                <span class="text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider">Priority</span>
                <i data-lucide="flag" class="text-ink-tertiary" style="width:13px;height:13px;"></i>
            </div>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider bg-{{ $ticket->priorityColor() }}">
                <i data-lucide="flag" style="width:10px;height:10px;" class="me-1"></i>
                {{ ucfirst($ticket->priority) }}
            </span>
        </div>
    </article>
    {{-- Category --}}
    <article class="bg-white rounded-sm shadow-sm overflow-hidden relative">
        <div class="absolute top-0 left-0 right-0 h-1" style="background-color: #a855f7;"></div>
        <div class="p-4 pt-5">
            <div class="flex items-center justify-between mb-1">
                <span class="text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider">Category</span>
                <i data-lucide="tag" class="text-ink-tertiary" style="width:13px;height:13px;"></i>
            </div>
            <div class="text-sm font-semibold text-ink-emphasis">{{ ucwords(str_replace('_', ' ', $ticket->category)) }}</div>
        </div>
    </article>
    {{-- SLA --}}
    <article class="bg-white rounded-sm shadow-sm overflow-hidden relative">
        <div class="absolute top-0 left-0 right-0 h-1" style="background-color: {{ $ticket->isOverdue() ? '#ef4444' : '#10b981' }};"></div>
        <div class="p-4 pt-5">
            <div class="flex items-center justify-between mb-1">
                <span class="text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider">SLA Due</span>
                <i data-lucide="{{ $ticket->isOverdue() ? 'alarm-clock' : 'timer' }}" class="text-ink-tertiary" style="width:13px;height:13px;"></i>
            </div>
            <div class="text-sm font-semibold {{ $ticket->isOverdue() ? 'text-feedback-danger' : 'text-ink-emphasis' }}">
                {{ optional($ticket->sla_due_at)->format('d M Y · H:i') ?? '—' }}
            </div>
            @if ($ticket->isOverdue())
                <small class="text-feedback-danger text-xs font-semibold flex items-center gap-1 mt-1">
                    <i data-lucide="circle-alert" style="width:11px;height:11px;"></i> Overdue
                </small>
            @endif
        </div>
    </article>
</section>

{{-- ═══ MAIN GRID (conversation + sidebar) ═══ --}}
<section class="grid grid-cols-1 lg:grid-cols-3 gap-3">
    {{-- Conversation thread + reply form --}}
    <div class="lg:col-span-2 space-y-3">
        {{-- Reply form --}}
        @if (! $ticket->isClosed())
            <section class="bg-white rounded-sm shadow-sm overflow-hidden">
                <div class="px-5 py-3 border-b border-border flex items-center gap-2">
                    <i data-lucide="reply" style="width:14px;height:14px;" class="text-ink-tertiary"></i>
                    <h3 class="text-sm font-bold text-ink-emphasis mb-0">Send Reply</h3>
                </div>
                <div class="p-5">
                    <form method="POST" action="{{ route('seller.support.reply', $ticket) }}" enctype="multipart/form-data">
                        @csrf
                        <x-textarea-input name="body" value="" required rows="5" maxlength="10000"
                                           placeholder="Type your reply…" />
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-3 items-center">
                            <div>
                                <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">
                                    Attachments <span class="text-ink-tertiary font-normal normal-case">(optional)</span>
                                </label>
                                <div class="relative">
                                    <i data-lucide="paperclip" class="absolute top-1/2 -translate-y-1/2 text-ink-tertiary" style="width:14px;height:14px; left: 10px;"></i>
                                    <input type="file" name="attachments[]" multiple
                                           class="w-full pl-8 pr-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                                </div>
                                <small class="text-ink-tertiary mt-1 block">JPG, PNG, PDF or any file. Max 10MB each.</small>
                            </div>
                            <div class="flex justify-end items-end">
                                <button type="submit" class="btn btn-primary">
                                    <i data-lucide="send" style="width:14px;height:14px;"></i> Send Reply
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        @endif

        {{-- Conversation thread --}}
        <section class="bg-white rounded-sm shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b border-border flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i data-lucide="messages-square" style="width:14px;height:14px;" class="text-ink-tertiary"></i>
                    <h3 class="text-sm font-bold text-ink-emphasis mb-0">Conversation</h3>
                </div>
                <span class="text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider">{{ $ticket->messages->count() }} {{ Str::plural('message', $ticket->messages->count()) }}</span>
            </div>
            <div class="p-5">
                <ul class="list-none pl-0 mb-0 space-y-4">
                    @forelse ($ticket->messages as $message)
                        @if ($message->is_internal_note)
                            @continue
                        @endif
                        @php
                            $fromSeller = $message->isFromSeller();
                            $fromAdmin  = $message->isFromAdmin();
                        @endphp
                        <li class="border-b border-border pb-4 last:border-0 last:pb-0">
                            <div class="flex items-start gap-3">
                                <div class="w-9 h-9 rounded-full {{ $fromSeller ? 'bg-brand-tint text-brand-deep' : ($fromAdmin ? 'bg-feedback-info/15 text-feedback-info' : 'bg-surface-muted text-ink-secondary') }} flex items-center justify-center font-bold text-xs shrink-0">
                                    @if ($fromSeller)
                                        <i data-lucide="user-round" style="width:14px;height:14px;"></i>
                                    @elseif ($fromAdmin)
                                        {{ mb_strtoupper(mb_substr($message->adminSender()?->name ?? 'A', 0, 1)) }}
                                    @else
                                        <i data-lucide="bot" style="width:14px;height:14px;"></i>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex flex-wrap items-center justify-between gap-2 mb-1">
                                        <span class="font-semibold text-ink-emphasis text-sm">
                                            @if ($fromSeller) You
                                            @elseif ($fromAdmin) {{ $message->adminSender()?->name ?? 'Admin' }}
                                            @else System
                                            @endif
                                        </span>
                                        <small class="text-ink-tertiary text-xs flex items-center gap-1">
                                            <i data-lucide="clock" style="width:11px;height:11px;"></i>
                                            {{ $message->created_at->format('d M Y · H:i') }}
                                        </small>
                                    </div>
                                    <p class="mb-0 text-sm text-ink-soft leading-relaxed" style="white-space: pre-line;">{{ $message->body }}</p>
                                    @if ($message->attachments->isNotEmpty())
                                        <div class="mt-2 flex flex-wrap gap-1.5">
                                            @foreach ($message->attachments as $att)
                                                <a href="{{ $att->url() }}" target="_blank"
                                                   class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xs text-xs font-semibold bg-surface-muted text-ink-soft">
                                                    <i data-lucide="paperclip" style="width:11px;height:11px;"></i> {{ $att->original_name }}
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="text-center py-6">
                            <i data-lucide="messages-square" class="text-ink-tertiary mx-auto mb-2" style="width:28px;height:28px;"></i>
                            <p class="text-ink-soft font-semibold mb-1">No messages yet</p>
                            <small class="text-ink-tertiary">Be the first to reply below.</small>
                        </li>
                    @endforelse
                </ul>
            </div>
        </section>
    </div>

    {{-- Sidebar info --}}
    <aside class="space-y-3">
        <section class="bg-white rounded-sm shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b border-border flex items-center gap-2">
                <i data-lucide="info" style="width:14px;height:14px;" class="text-ink-tertiary"></i>
                <h3 class="text-sm font-bold text-ink-emphasis mb-0">Ticket Information</h3>
            </div>
            <div class="p-5 space-y-3 text-sm">
                <div>
                    <div class="text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider mb-1">Assigned to</div>
                    @if ($ticket->admin)
                        <div class="flex items-center gap-2 mt-1">
                            <div class="w-7 h-7 rounded-full bg-feedback-info/15 flex items-center justify-center text-feedback-info text-[11px] font-bold">
                                {{ mb_strtoupper(mb_substr($ticket->admin->name ?? 'A', 0, 1)) }}
                            </div>
                            <span class="text-ink-emphasis">{{ $ticket->admin->name }}</span>
                        </div>
                    @else
                        <span class="text-ink-tertiary">— unassigned yet —</span>
                    @endif
                </div>
                <div>
                    <div class="text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider mb-1">Opened</div>
                    <div class="text-ink-emphasis">{{ $ticket->created_at->format('d M Y · H:i') }}</div>
                    <small class="text-ink-tertiary">{{ $ticket->created_at->diffForHumans() }}</small>
                </div>
                @if ($ticket->order_id)
                    <div>
                        <div class="text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider mb-1">Related Order</div>
                        <div class="text-ink-emphasis">#{{ $ticket->order_id }}</div>
                    </div>
                @endif
            </div>
        </section>

        <section class="bg-white rounded-sm shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b border-border flex items-center gap-2">
                <i data-lucide="settings-2" style="width:14px;height:14px;" class="text-ink-tertiary"></i>
                <h3 class="text-sm font-bold text-ink-emphasis mb-0">Actions</h3>
            </div>
            <div class="p-5 space-y-2">
                @if ($ticket->isOpen() && config('marketplace.support.allow_self_resolve', true))
                    <form method="POST" action="{{ route('seller.support.resolve', $ticket) }}">
                        @csrf
                        <button class="btn btn-success w-full">
                            <i data-lucide="check-circle-2" style="width:14px;height:14px;"></i> Mark as Resolved
                        </button>
                    </form>
                @endif

                @if ($ticket->isClosed())
                    <form method="POST" action="{{ route('seller.support.reopen', $ticket) }}">
                        @csrf
                        <button class="btn btn-warning w-full">
                            <i data-lucide="rotate-cw" style="width:14px;height:14px;"></i> Reopen Ticket
                        </button>
                    </form>
                @endif

                @if (! $ticket->isClosed())
                    <a href="{{ route('seller.support.create') }}" class="btn btn-light w-full">
                        <i data-lucide="plus" style="width:14px;height:14px;"></i> New Ticket
                    </a>
                @endif
            </div>
        </section>
    </aside>
</section>

@endsection
