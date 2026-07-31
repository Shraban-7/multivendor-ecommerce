@php
    $counts = $counts ?? ['total' => 0, 'active' => 0, 'inactive' => 0];
@endphp
@extends('admin.layouts.app')
@section('title', 'Shipping Carriers')

@section('content')

{{-- ═══ HERO ═══ --}}
<section class="bg-white rounded-sm shadow-sm overflow-hidden mb-3 relative">
    <div class="absolute top-0 left-0 right-0 h-1" style="background: linear-gradient(90deg, #0ea5e9, #38bdf8, #7dd3fc);"></div>
    <div class="p-5 lg:p-6 pt-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="min-w-0">
                <nav class="flex items-center gap-1 mb-2 text-xs text-ink-tertiary">
                    <i data-lucide="truck" class="text-feedback-info" style="width:12px;height:12px;"></i>
                    <span>Infrastructure</span>
                    <i data-lucide="chevron-right" style="width:12px;height:12px;"></i>
                    <span class="text-ink-soft font-semibold">Shipping Carriers</span>
                </nav>
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <h1 class="text-xl font-bold text-ink-emphasis mb-0">Shipping Carriers</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-feedback-info/15 text-feedback-info">
                        <i data-lucide="truck" style="width:11px;height:11px;" class="me-1"></i> Logistics
                    </span>
                </div>
                <p class="text-sm text-ink-secondary mb-0">Configure the delivery carriers that sellers can route shipments through.</p>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCarrierModal">
                    <i data-lucide="plus" style="width:15px;height:15px;"></i> Add Carrier
                </button>
            </div>
        </div>
    </div>
</section>

{{-- Flash --}}
@if (session('success'))
    <div class="px-4 py-2 rounded-sm bg-feedback-success/10 text-feedback-success text-sm mb-3 alert-dismissible fade show">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="px-4 py-2 rounded-sm bg-feedback-danger/10 text-feedback-danger text-sm mb-3 alert-dismissible fade show">{{ session('error') }}</div>
@endif

{{-- ═══ KPI TILES ═══ --}}
@php
    $tiles = [
        ['key' => 'total',    'label' => 'Total Carriers', 'top' => '#0ea5e9', 'text' => 'text-feedback-info',         'icon' => 'truck'],
        ['key' => 'active',   'label' => 'Active',         'top' => '#10b981', 'text' => 'text-feedback-success',      'icon' => 'check-circle-2'],
        ['key' => 'inactive', 'label' => 'Inactive',       'top' => '#6b7280', 'text' => 'text-ink-secondary',         'icon' => 'pause-circle'],
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
        <i data-lucide="truck" style="width:14px;height:14px;" class="text-ink-tertiary"></i>
        <h3 class="text-sm font-bold text-ink-emphasis mb-0">All Carriers</h3>
    </div>

    <div class="overflow-x-auto px-4 pb-4">
        <table class="w-full text-left text-sm border-collapse">
            <thead class="bg-surface-muted">
                <tr>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary w-12">#</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Name</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Slug</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">API Endpoint</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary text-center">Status</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($carriers as $carrier)
                    <tr class="border-t border-border hover:bg-surface-muted/40 transition-colors">
                        <td class="px-4 py-3 text-ink-tertiary">{{ $carrier->id }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-feedback-info/15 flex items-center justify-center text-feedback-info shrink-0">
                                    <i data-lucide="truck" style="width:14px;height:14px;"></i>
                                </div>
                                <span class="font-semibold text-ink-emphasis">{{ $carrier->name }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-xs">
                            <code class="px-1.5 py-0.5 rounded-xs bg-surface-muted text-ink-secondary">{{ $carrier->slug }}</code>
                        </td>
                        <td class="px-4 py-3 text-xs text-ink-secondary">{{ Str::limit($carrier->api_endpoint, 60) ?: '—' }}</td>
                        <td class="px-4 py-3 text-center">
                            @if ($carrier->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider bg-feedback-success/15 text-feedback-success">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current opacity-70 me-1.5"></span>
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider bg-surface-muted text-ink-tertiary">
                                    Inactive
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="inline-flex gap-1.5">
                                <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#editCarrierModal-{{ $carrier->id }}">
                                    <i data-lucide="pencil" style="width:13px;height:13px;"></i>
                                </button>
                                <form method="POST" action="{{ route('admin.shipping.carriers.destroy', $carrier) }}" class="inline"
                                      onsubmit="return confirm('Delete this carrier?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-light btn-sm text-feedback-danger" style="color:#dc2625;">
                                        <i data-lucide="trash-2" style="width:13px;height:13px;"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="py-10 text-center">
                                <i data-lucide="truck" class="text-ink-tertiary mx-auto mb-2" style="width:36px;height:36px;"></i>
                                <p class="text-ink-soft font-semibold mb-1">No carriers configured</p>
                                <p class="text-ink-tertiary text-xs">Click <strong>Add Carrier</strong> to onboard your first one.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($carriers->hasPages())
        <div class="flex justify-end p-4 border-t border-border">
            {{ $carriers->links() }}
        </div>
    @endif
</section>

{{-- Edit modals --}}
@foreach ($carriers as $carrier)
    <div class="modal fade" id="editCarrierModal-{{ $carrier->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.shipping.carriers.update', $carrier) }}">
                    @csrf
                    <div class="modal-header">
                        <div>
                            <h5 class="modal-title font-bold">Edit Carrier</h5>
                            <small class="text-ink-tertiary">{{ $carrier->name }}</small>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        @include('admin.shipping._carrier_form', ['carrier' => $carrier])
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i data-lucide="save" style="width:14px;height:14px;"></i> Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

{{-- Add Carrier Modal --}}
<div class="modal fade" id="addCarrierModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.shipping.carriers.store') }}">
                @csrf
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title font-bold">Add Shipping Carrier</h5>
                        <small class="text-ink-tertiary">Onboard a new logistics partner</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @include('admin.shipping._carrier_form', ['carrier' => null])
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i data-lucide="save" style="width:14px;height:14px;"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
