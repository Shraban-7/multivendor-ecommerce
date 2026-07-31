@php
    use Illuminate\Support\Str;

    $initials = mb_strtoupper(mb_substr($customer->name ?? '?', 0, 1));
    $verified = ! empty($customer->email_verified_at);
    $address  = $customer->address ?? null;
    $country  = optional($customer->country)->name ?? null;
@endphp
@extends('admin.layouts.app')
@section('title', $customer->name.' · Client Profile')

@section('content')

{{-- ═══ HERO ═══ --}}
<section class="bg-white rounded-sm shadow-sm overflow-hidden mb-3 relative">
    <div class="absolute top-0 left-0 right-0 h-1" style="background: linear-gradient(90deg, #0ea5e9, #38bdf8, #7dd3fc);"></div>
    <div class="p-5 lg:p-6 pt-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="flex items-start gap-4 min-w-0">
                <div class="w-16 h-16 rounded-full bg-brand-tint flex items-center justify-center text-brand-deep text-xl font-bold shrink-0">
                    {{ $initials }}
                </div>
                <div class="min-w-0">
                    <nav class="flex items-center gap-1 mb-2 text-xs text-ink-tertiary">
                        <i data-lucide="users" class="text-feedback-info" style="width:12px;height:12px;"></i>
                        <a href="{{ route('admin.customers.index') }}" class="hover:text-ink-emphasis">Customers</a>
                        <i data-lucide="chevron-right" style="width:12px;height:12px;"></i>
                        <span class="text-ink-soft font-semibold truncate">{{ $customer->name }}</span>
                    </nav>
                    <div class="flex flex-wrap items-center gap-2 mb-1">
                        <h1 class="text-xl font-bold text-ink-emphasis mb-0 truncate max-w-[300px]">{{ $customer->name }}</h1>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider {{ $verified ? 'bg-feedback-success/15 text-feedback-success' : 'bg-surface-muted text-ink-tertiary' }}">
                            @if ($verified)
                                <i data-lucide="badge-check" style="width:11px;height:11px;" class="me-1"></i> Verified
                            @else
                                <i data-lucide="help-circle" style="width:11px;height:11px;" class="me-1"></i> Unverified
                            @endif
                        </span>
                    </div>
                    <p class="text-sm text-ink-secondary mb-0">
                        <span class="font-semibold text-ink-emphasis">@{{ $customer->username }}</span>
                        &nbsp;·&nbsp; <i data-lucide="calendar" style="width:11px;height:11px;" class="me-1 align-text-bottom text-ink-tertiary"></i>
                        Joined {{ optional($customer->created_at)->format('d M Y') }}
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('admin.customers.index') }}" class="btn btn-light">
                    <i data-lucide="arrow-left" style="width:15px;height:15px;"></i> Back
                </a>
                @if ($customer->email)
                    <a href="mailto:{{ $customer->email }}" class="btn btn-primary">
                        <i data-lucide="send" style="width:15px;height:15px;"></i> Email
                    </a>
                @endif
            </div>
        </div>
    </div>
</section>

{{-- ═══ KPI TILES ═══ --}}
@php
    $countCards = [
        ['key' => 'total_spent',      'label' => 'Lifetime Value', 'top' => '#10b981', 'text' => 'text-feedback-success', 'icon' => 'dollar-sign', 'display' => 'money'],
        ['key' => 'total_orders',     'label' => 'Total Orders',   'top' => '#F85606', 'text' => 'text-brand-deep',        'icon' => 'shopping-bag', 'display' => 'number'],
        ['key' => 'pending_orders',   'label' => 'Pending',        'top' => '#fb923c', 'text' => 'text-feedback-warning',  'icon' => 'clock', 'display' => 'number'],
        ['key' => 'shipped_orders',   'label' => 'Shipped',        'top' => '#0ea5e9', 'text' => 'text-feedback-info',     'icon' => 'truck', 'display' => 'number'],
        ['key' => 'delivered_orders', 'label' => 'Delivered',      'top' => '#10b981', 'text' => 'text-feedback-success',  'icon' => 'package-check', 'display' => 'number'],
        ['key' => 'cancelled_orders', 'label' => 'Cancelled',      'top' => '#ef4444', 'text' => 'text-feedback-danger',   'icon' => 'x-circle', 'display' => 'number'],
    ];
@endphp
<section class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 mb-3">
    @foreach ($countCards as $card)
        @php
            $value = match ($card['key']) {
                'total_spent'      => $total_spent,
                'total_orders'     => $total_orders,
                'pending_orders'   => $pending_orders,
                'shipped_orders'   => $shipped_orders,
                'delivered_orders' => $delivered_orders,
                'cancelled_orders' => $cancelled_orders,
            };
        @endphp
        <article class="bg-white rounded-sm shadow-sm overflow-hidden relative">
            <div class="absolute top-0 left-0 right-0 h-1" style="background-color: {{ $card['top'] }};"></div>
            <div class="p-4 pt-5">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider">{{ $card['label'] }}</span>
                    <i data-lucide="{{ $card['icon'] }}" class="text-ink-tertiary" style="width:14px;height:14px;"></i>
                </div>
                <h3 class="text-xl font-bold {{ $card['text'] }} mb-0">
                    @if ($card['display'] === 'money')
                        {{ money($value) }}
                    @else
                        {{ number_format($value) }}
                    @endif
                </h3>
            </div>
        </article>
    @endforeach
</section>

{{-- ═══ TWO-COLUMN MAIN: Contact + Recent Orders ═══ --}}
<section class="grid grid-cols-1 lg:grid-cols-3 gap-3">
    {{-- ── Contact info card ── --}}
    <aside class="bg-white rounded-sm shadow-sm overflow-hidden">
        <div class="px-5 py-3 flex items-center gap-2 bg-surface-muted">
            <i data-lucide="contact" style="width:14px;height:14px;" class="text-ink-tertiary"></i>
            <h3 class="text-sm font-bold text-ink-emphasis mb-0">Contact Information</h3>
        </div>
        <div class="p-5 space-y-4 text-sm">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-full bg-surface-muted flex items-center justify-center shrink-0">
                    <i data-lucide="mail" style="width:14px;height:14px;" class="text-ink-tertiary"></i>
                </div>
                <div class="min-w-0">
                    <div class="text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider mb-0.5">Email</div>
                    @if ($customer->email)
                        <a href="mailto:{{ $customer->email }}" class="font-semibold text-ink-emphasis hover:text-brand-deep break-all">
                            {{ $customer->email }}
                        </a>
                    @else
                        <span class="text-ink-tertiary">—</span>
                    @endif
                </div>
            </div>

            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-full bg-surface-muted flex items-center justify-center shrink-0">
                    <i data-lucide="phone" style="width:14px;height:14px;" class="text-ink-tertiary"></i>
                </div>
                <div class="min-w-0">
                    <div class="text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider mb-0.5">Phone</div>
                    @if ($customer->phone)
                        <span class="font-semibold text-ink-emphasis">{{ $customer->phone }}</span>
                    @else
                        <span class="text-ink-tertiary">—</span>
                    @endif
                </div>
            </div>

            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-full bg-surface-muted flex items-center justify-center shrink-0">
                    <i data-lucide="globe" style="width:14px;height:14px;" class="text-ink-tertiary"></i>
                </div>
                <div class="min-w-0">
                    <div class="text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider mb-0.5">Country</div>
                    <span class="font-semibold text-ink-emphasis">{{ $country ?: '—' }}</span>
                </div>
            </div>

            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-full bg-surface-muted flex items-center justify-center shrink-0">
                    <i data-lucide="map-pin" style="width:14px;height:14px;" class="text-ink-tertiary"></i>
                </div>
                <div class="min-w-0">
                    <div class="text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider mb-0.5">Address</div>
                    <span class="font-semibold text-ink-emphasis break-words">{{ $address ?: '—' }}</span>
                </div>
            </div>

            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-full bg-surface-muted flex items-center justify-center shrink-0">
                    <i data-lucide="calendar" style="width:14px;height:14px;" class="text-ink-tertiary"></i>
                </div>
                <div class="min-w-0">
                    <div class="text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider mb-0.5">Joined</div>
                    <span class="font-semibold text-ink-emphasis">
                        {{ optional($customer->created_at)->format('d M Y · H:i') }}
                    </span>
                </div>
            </div>
        </div>
    </aside>

    {{-- ── Recent orders card ── --}}
    <section class="lg:col-span-2 bg-white rounded-sm shadow-sm overflow-hidden">
        <div class="px-5 py-3 flex items-center justify-between bg-surface-muted">
            <div class="flex items-center gap-2">
                <i data-lucide="shopping-bag" style="width:14px;height:14px;" class="text-ink-tertiary"></i>
                <h3 class="text-sm font-bold text-ink-emphasis mb-0">Recent Orders</h3>
                <span class="text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider">Last 8</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm border-collapse">
                <thead>
                    <tr>
                        <th class="px-4 py-2.5 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Order #</th>
                        <th class="px-4 py-2.5 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Date</th>
                        <th class="px-4 py-2.5 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Status</th>
                        <th class="px-4 py-2.5 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse (($recent_orders ?? collect()) as $order)
                        <tr class="border-t border-border hover:bg-surface-muted/40">
                            <td class="px-4 py-3 font-semibold text-ink-emphasis">#{{ $order->order_number ?? $order->id }}</td>
                            <td class="px-4 py-3 text-xs text-ink-secondary">
                                <i data-lucide="calendar" style="width:11px;height:11px;" class="me-1 align-text-bottom text-ink-tertiary"></i>
                                {{ $order->created_at?->format('d M Y') }}
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $statusKey = strtolower($order->status ?? 'pending');
                                    $statusLabel = $order->status ?? 'Pending';
                                    $pillBg = match (true) {
                                        in_array($statusKey, ['delivered', 'completed']) => 'bg-feedback-success/15 text-feedback-success',
                                        in_array($statusKey, ['cancelled', 'canceled', 'failed'])  => 'bg-feedback-danger/15 text-feedback-danger',
                                        in_array($statusKey, ['shipped'])  => 'bg-feedback-info/15 text-feedback-info',
                                        in_array($statusKey, ['processing', 'accepted'])  => 'bg-feedback-warning/15 text-feedback-warning',
                                        default => 'bg-surface-muted text-ink-tertiary',
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider {{ $pillBg }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current opacity-70 me-1.5"></span>
                                    {{ Str::title($statusLabel) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right font-semibold text-ink-emphasis">{{ money($order->total ?? 0) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                <div class="py-8 text-center">
                                    <i data-lucide="package-x" class="text-ink-tertiary mx-auto mb-2" style="width:32px;height:32px;"></i>
                                    <p class="text-ink-soft font-semibold mb-1">No orders yet</p>
                                    <p class="text-ink-tertiary text-xs">This customer hasn't placed orders with the marketplace.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</section>

@endsection
