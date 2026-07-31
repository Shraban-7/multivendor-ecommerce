@php
    $counts = $counts ?? ['total' => 0, 'enabled' => 0, 'disabled' => 0];
@endphp
@extends('admin.layouts.app')
@section('title', 'Payment Gateways')

@section('content')

{{-- ═══ HERO ═══ --}}
<section class="bg-white rounded-sm shadow-sm overflow-hidden mb-3 relative">
    <div class="absolute top-0 left-0 right-0 h-1" style="background: linear-gradient(90deg, #10b981, #34d399, #6ee7b7);"></div>
    <div class="p-5 lg:p-6 pt-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="min-w-0">
                <nav class="flex items-center gap-1 mb-2 text-xs text-ink-tertiary">
                    <i data-lucide="banknote" class="text-feedback-success" style="width:12px;height:12px;"></i>
                    <span>Infrastructure</span>
                    <i data-lucide="chevron-right" style="width:12px;height:12px;"></i>
                    <span class="text-ink-soft font-semibold">Payment Gateways</span>
                </nav>
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <h1 class="text-xl font-bold text-ink-emphasis mb-0">Payment Gateways</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-feedback-success/15 text-feedback-success">
                        <i data-lucide="shield-check" style="width:11px;height:11px;" class="me-1"></i> Checkouts
                    </span>
                </div>
                <p class="text-sm text-ink-secondary mb-0">Manage the payment processors your marketplace connects to.</p>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('admin.paymentGateways.create') }}" class="btn btn-primary">
                    <i data-lucide="plus" style="width:15px;height:15px;"></i> Add Gateway
                </a>
            </div>
        </div>
    </div>
</section>

{{-- Flash --}}
@if (session('success'))
    <div class="px-4 py-2 rounded-sm bg-feedback-success/10 text-feedback-success text-sm mb-3 alert-dismissible fade show">{{ session('success') }}</div>
@endif

{{-- ═══ KPI TILES ═══ --}}
@php
    $tiles = [
        ['key' => 'total',    'label' => 'Total Gateways', 'top' => '#10b981', 'text' => 'text-feedback-success',  'icon' => 'banknote'],
        ['key' => 'enabled',  'label' => 'Enabled',        'top' => '#0ea5e9', 'text' => 'text-feedback-info',     'icon' => 'check-circle-2'],
        ['key' => 'disabled', 'label' => 'Disabled',       'top' => '#6b7280', 'text' => 'text-ink-secondary',     'icon' => 'pause-circle'],
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

{{-- ═══ TABLE CARD ═══ --}}
<section class="bg-white rounded-sm shadow-sm overflow-hidden">
    <div class="px-5 py-3 bg-surface-muted flex items-center gap-2">
        <i data-lucide="banknote" style="width:14px;height:14px;" class="text-ink-tertiary"></i>
        <h3 class="text-sm font-bold text-ink-emphasis mb-0">All Gateways</h3>
    </div>

    <div class="overflow-x-auto px-4 pb-4">
        <table class="w-full text-left text-sm border-collapse">
            <thead class="bg-surface-muted">
                <tr>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary w-12">#</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Name</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Image</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Payment URL</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Credentials</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary text-center">Status</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($paymentGateways as $gateway)
                    <tr class="border-t border-border hover:bg-surface-muted/40 transition-colors">
                        <td class="px-4 py-3 text-ink-tertiary">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3 font-semibold text-ink-emphasis">{{ $gateway->name }}</td>
                        <td class="px-4 py-3">
                            @if ($gateway->image)
                                <img src="{{ storage_url($gateway->image) }}" alt="{{ $gateway->name }}"
                                     style="max-height:36px;border-radius:6px;">
                            @else
                                <span class="text-ink-tertiary text-xs">No image</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-xs">
                            @if ($gateway->payment_url)
                                <a href="{{ $gateway->payment_url }}" target="_blank" class="text-brand-deep hover:underline">
                                    {{ Str::limit($gateway->payment_url, 40) }}
                                    <i data-lucide="external-link" style="width:11px;height:11px;" class="ms-1 align-text-bottom"></i>
                                </a>
                            @else
                                <span class="text-ink-tertiary">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-xs text-ink-soft">
                            @if (!empty($gateway->credentials) && is_array($gateway->credentials))
                                @foreach ($gateway->credentials as $key => $value)
                                    <div><span class="font-semibold text-ink-emphasis">{{ ucwords(str_replace('_', ' ', $key)) }}:</span> {{ $value }}</div>
                                @endforeach
                            @else
                                <span class="text-ink-tertiary">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if ($gateway->is_enabled)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider bg-feedback-success/15 text-feedback-success">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current opacity-70 me-1.5"></span>
                                    Enabled
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider bg-surface-muted text-ink-tertiary">
                                    Disabled
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="inline-flex gap-1.5">
                                <a href="{{ route('admin.paymentGateways.edit', $gateway->id) }}" class="btn btn-light btn-sm">
                                    <i data-lucide="pencil" style="width:13px;height:13px;"></i>
                                </a>
                                <button type="button" class="btn btn-light btn-sm text-feedback-danger" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $gateway->id }}">
                                    <i data-lucide="trash-2" style="width:13px;height:13px;"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="py-10 text-center">
                                <i data-lucide="banknote" class="text-ink-tertiary mx-auto mb-2" style="width:36px;height:36px;"></i>
                                <p class="text-ink-soft font-semibold mb-1">No payment gateways</p>
                                <p class="text-ink-tertiary text-xs">Add your first gateway to start accepting payments.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

@foreach ($paymentGateways as $gateway)
    <div class="modal fade" id="deleteModal-{{ $gateway->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.paymentGateways.destroy', $gateway->id) }}">
                    @csrf @method('DELETE')
                    <div class="modal-header">
                        <div>
                            <h5 class="modal-title font-bold text-feedback-danger">Confirm Deletion</h5>
                            <small class="text-ink-tertiary">{{ $gateway->name }}</small>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="bg-feedback-danger/10 rounded-xs p-4 flex items-start gap-3">
                            <i data-lucide="triangle-alert" class="text-feedback-danger shrink-0 mt-0.5" style="width:18px;height:18px;"></i>
                            <div class="text-sm text-ink-soft">
                                Are you sure you want to delete <strong>{{ $gateway->name }}</strong>? Any active checkout flows using this gateway will fail.
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">
                            <i data-lucide="trash-2" style="width:14px;height:14px;"></i> Delete
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

@endsection
