@php
    $counts = $counts ?? ['total' => 0, 'unread' => 0, 'read' => 0];
@endphp
@extends('seller.layouts.app')
@section('title', 'Product Bundles')

@section('content')

<section class="bg-white rounded-sm shadow-sm overflow-hidden mb-3 relative">
    <div class="absolute top-0 left-0 right-0 h-1" style="background: linear-gradient(90deg, #F85606, #fb923c, #fbbf24);"></div>
    <div class="p-5 lg:p-6 pt-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="min-w-0">
                <nav class="flex items-center gap-1 mb-2 text-xs text-ink-tertiary">
                    <i data-lucide="package-2" class="text-feedback-info" style="width:12px;height:12px;"></i>
                    <span>Workspace</span>
                    <i data-lucide="chevron-right" style="width:12px;height:12px;"></i>
                    <span class="text-ink-soft font-semibold">Bundles</span>
                </nav>
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <h1 class="text-xl font-bold text-ink-emphasis mb-0">Product Bundles</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-feedback-info/15 text-feedback-info">
                        <i data-lucide="package-2" style="width:11px;height:11px;" class="me-1"></i> Curated Kits
                    </span>
                </div>
                <p class="text-sm text-ink-secondary mb-0">Bundle related products together to lift average order value.</p>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('seller.bundles.create') }}" class="btn btn-primary">
                    <i data-lucide="plus" style="width:15px;height:15px;"></i> Create Bundle
                </a>
            </div>
        </div>
    </div>
</section>

@if(session('success'))
    <div class="flex items-start gap-2 p-4 rounded-sm bg-feedback-success/10 text-feedback-success text-sm mb-3">{{ session('success') }}</div>
@endif

@php
    $tiles = [
        ['key' => 'total',  'label' => 'Total Bundles', 'top' => '#F85606', 'text' => 'text-brand-deep',        'icon' => 'package-2'],
        ['key' => 'active', 'label' => 'Active',          'top' => '#10b981', 'text' => 'text-feedback-success',  'icon' => 'check-circle-2'],
        ['key' => 'draft',  'label' => 'Draft',           'top' => '#fb923c', 'text' => 'text-feedback-warning',  'icon' => 'file-text'],
        ['key' => 'hidden', 'label' => 'Hidden',          'top' => '#6b7280', 'text' => 'text-ink-secondary',     'icon' => 'eye-off'],
    ];
@endphp
<section class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-3">
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
        <i data-lucide="package-2" style="width:14px;height:14px;" class="text-ink-tertiary"></i>
        <h3 class="text-sm font-bold text-ink-emphasis mb-0">All Bundles</h3>
    </div>

    <div class="overflow-x-auto px-4 pb-4">
        <table class="w-full text-left text-sm border-collapse">
            <thead class="bg-surface-muted">
                <tr>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Bundle</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">SKU</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Price</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary text-center">Stock</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary text-center">Items</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Status</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Type</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Date</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bundles as $bundle)
                    @php
                        $pillBg = match ($bundle->status) {
                            $bundle::STATUS_ACTIVE            => 'bg-feedback-success/15 text-feedback-success',
                            $bundle::STATUS_PENDING_APPROVAL  => 'bg-feedback-warning/15 text-feedback-warning',
                            $bundle::STATUS_INACTIVE          => 'bg-surface-muted text-ink-tertiary',
                            $bundle::STATUS_DRAFT              => 'bg-feedback-info/15 text-feedback-info',
                            default                            => 'bg-surface-muted text-ink-tertiary',
                        };
                        $stockPill = match (true) {
                            $bundle->total_stock <= 0  => 'bg-feedback-danger/15 text-feedback-danger',
                            $bundle->total_stock <= 5  => 'bg-feedback-warning/15 text-feedback-warning',
                            default                    => 'bg-surface-muted text-ink-emphasis',
                        };
                    @endphp
                    <tr class="border-t border-border hover:bg-surface-muted/40 transition-colors">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <img src="{{ $bundle->thumbnail_url }}" alt="" width="40" height="40"
                                     style="object-fit:cover;border-radius:6px;" class="shrink-0">
                                <div class="min-w-0">
                                    <div class="font-semibold text-ink-emphasis text-sm">{{ $bundle->name }}</div>
                                    <div>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold uppercase tracking-wider {{ $bundle->is_visible ? 'bg-feedback-success/15 text-feedback-success' : 'bg-surface-muted text-ink-tertiary' }}">
                                            <i data-lucide="{{ $bundle->is_visible ? 'eye' : 'eye-off' }}" style="width:10px;height:10px;" class="me-1"></i>
                                            {{ $bundle->is_visible ? 'Visible' : 'Hidden' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-xs">
                            <code class="px-1.5 py-0.5 rounded-xs bg-surface-muted text-ink-secondary">{{ $bundle->sku }}</code>
                        </td>
                        <td class="px-4 py-3 font-semibold text-ink-emphasis">{{ money($bundle->calculatePrice()) }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold {{ $stockPill }}">
                                {{ $bundle->total_stock }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center font-semibold text-ink-emphasis">{{ $bundle->items->count() }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider {{ $pillBg }}">
                                <span class="w-1.5 h-1.5 rounded-full bg-current opacity-70 me-1.5"></span>
                                {{ ucfirst(str_replace('_', ' ', $bundle->status)) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-surface-muted text-ink-secondary capitalize">
                                {{ $bundle->type }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-xs text-ink-secondary">
                            <i data-lucide="calendar" style="width:11px;height:11px;" class="me-1 align-text-bottom text-ink-tertiary"></i>
                            {{ $bundle->created_at->format('d M Y') }}
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="dropdown inline-block">
                                <button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i data-lucide="more-horizontal" style="width:14px;height:14px;"></i>
                                    <span class="ms-1">Manage</span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end py-1" style="min-width:170px;">
                                    <li>
                                        <a class="dropdown-item py-1.5" href="{{ route('seller.bundles.show', $bundle) }}">
                                            <i data-lucide="eye" style="width:13px;height:13px;" class="me-2 text-ink-tertiary"></i> View
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-1.5" href="{{ route('seller.bundles.edit', $bundle) }}">
                                            <i data-lucide="pencil" style="width:13px;height:13px;" class="me-2 text-ink-tertiary"></i> Edit
                                        </a>
                                    </li>
                                    <li>
                                        <form action="{{ route('seller.bundles.duplicate', $bundle) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item py-1.5">
                                                <i data-lucide="copy" style="width:13px;height:13px;" class="me-2 text-ink-tertiary"></i> Duplicate
                                            </button>
                                        </form>
                                    </li>
                                    <li><hr class="dropdown-divider my-1"></li>
                                    <li>
                                        <form action="{{ route('seller.bundles.destroy', $bundle) }}" method="POST" onsubmit="return confirm('Delete this bundle?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="dropdown-item py-1.5 text-feedback-danger">
                                                <i data-lucide="trash-2" style="width:13px;height:13px;" class="me-2"></i> Delete
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9">
                            <div class="py-10 text-center">
                                <i data-lucide="package-2" class="text-ink-tertiary mx-auto mb-2" style="width:36px;height:36px;"></i>
                                <p class="text-ink-soft font-semibold mb-1">No bundles yet</p>
                                <p class="text-ink-tertiary text-xs mb-3">Combine related products to boost average order value.</p>
                                <a href="{{ route('seller.bundles.create') }}" class="btn btn-primary btn-sm">
                                    <i data-lucide="plus" style="width:14px;height:14px;"></i> Create your first bundle
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(method_exists($bundles, 'hasPages') && $bundles->hasPages())
        <div class="flex justify-end p-4 border-t border-border">
            {{ $bundles->links() }}
        </div>
    @endif
</section>

@endsection
