@php
    $counts = $counts ?? ['total' => 0, 'unread' => 0, 'read' => 0];
@endphp
@extends('seller.layouts.app')
@section('title', 'Notifications')

@section('content')

<section class="bg-white rounded-sm shadow-sm overflow-hidden mb-3 relative">
    <div class="absolute top-0 left-0 right-0 h-1" style="background: linear-gradient(90deg, #ef4444, #f87171, #fca5a5);"></div>
    <div class="p-5 lg:p-6 pt-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="min-w-0">
                <nav class="flex items-center gap-1 mb-2 text-xs text-ink-tertiary">
                    <i data-lucide="bell" class="text-feedback-info" style="width:12px;height:12px;"></i>
                    <span>Workspace</span>
                    <i data-lucide="chevron-right" style="width:12px;height:12px;"></i>
                    <span class="text-ink-soft font-semibold">Notifications</span>
                </nav>
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <h1 class="text-xl font-bold text-ink-emphasis mb-0">Notifications</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-feedback-info/15 text-feedback-info">
                        <i data-lucide="bell-ring" style="width:11px;height:11px;" class="me-1"></i> Inbox
                    </span>
                </div>
                <p class="text-sm text-ink-secondary mb-0">Stay on top of orders, payouts and account alerts.</p>
            </div>
        </div>
    </div>
</section>

@php
    $tiles = [
        ['key' => 'total',  'label' => 'Total',  'top' => '#0ea5e9', 'text' => 'text-feedback-info',         'icon' => 'bell'],
        ['key' => 'unread', 'label' => 'Unread',  'top' => '#ef4444', 'text' => 'text-feedback-danger',      'icon' => 'bell-ring'],
        ['key' => 'read',   'label' => 'Read',    'top' => '#10b981', 'text' => 'text-feedback-success',     'icon' => 'bell-off'],
    ];
@endphp
<section class="grid grid-cols-3 gap-3 mb-3">
    @foreach ($tiles as $tile)
        <article class="bg-white rounded-sm shadow-sm overflow-hidden relative">
            <div class="absolute top-0 left-0 right-0 h-1" style="background-color: {{ $tile['top'] }};"></div>
            <div class="p-4 pt-5">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider">{{ $tile['label'] }}</span>
                    <i data-lucide="{{ $tile['icon'] }}" class="text-ink-tertiary" style="width:14px;height:14px;"></i>
                </div>
                <h3 class="text-2xl font-bold {{ $tile['text'] }} mb-0">{{ number_format($counts[$tile['key']] ?? 0) }}</h3>
            </div>
        </article>
    @endforeach
</section>

<section class="bg-white rounded-sm shadow-sm overflow-hidden">
    <div class="px-5 py-3 bg-surface-muted flex items-center gap-2">
        <i data-lucide="bell" style="width:14px;height:14px;" class="text-ink-tertiary"></i>
        <h3 class="text-sm font-bold text-ink-emphasis mb-0">All Notifications</h3>
    </div>

    <div class="p-5 border-t border-border">
        @if ($notifications->count())
            <ul class="list-none mb-0 space-y-3">
                @foreach ($notifications as $notification)
                    <li class="rounded-sm p-4 transition-colors
                              {{ !$notification->is_read ? 'bg-brand-tint ring-1 ring-brand-deep/40' : 'bg-surface-muted' }}">
                        <div class="flex flex-wrap items-start justify-between gap-2 mb-1">
                            <h5 class="text-sm font-bold text-ink-emphasis mb-0">{{ $notification->title }}</h5>
                            @if (!$notification->is_read)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-brand-deep text-white">
                                    <i data-lucide="circle-dot" style="width:9px;height:9px;" class="me-1"></i> New
                                </span>
                            @endif
                        </div>
                        <p class="text-sm text-ink-soft mb-2 leading-relaxed">{{ $notification->message }}</p>
                        <div class="flex items-center gap-2 text-xs text-ink-tertiary">
                            <i data-lucide="clock" style="width:11px;height:11px;"></i>
                            {{ $notification->created_at->diffForHumans() }}
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="text-center text-ink-tertiary py-5">
                <i data-lucide="bell-off" class="text-ink-tertiary mx-auto mb-2" style="width:36px;height:36px;"></i>
                <p class="text-ink-soft font-semibold mb-1">You're all caught up</p>
                <p class="text-ink-tertiary text-xs">No notifications right now.</p>
            </div>
        @endif
    </div>
</section>

@endsection
