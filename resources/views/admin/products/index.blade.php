@php
    use App\Domain\Product\Models\Product;
    $counts = $counts ?? [];
@endphp
@extends('admin.layouts.app')
@section('title', 'Products')

@section('content')

{{-- ═══ HERO ═══ --}}
<section class="bg-white rounded-sm shadow-sm overflow-hidden mb-3 relative">
    <div class="absolute top-0 left-0 right-0 h-1" style="background: linear-gradient(90deg, #F85606, #fb923c, #fbbf24);"></div>
    <div class="p-5 lg:p-6 pt-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="min-w-0">
                <nav class="flex items-center gap-1 mb-2 text-xs text-ink-tertiary">
                    <i data-lucide="package" class="text-feedback-info" style="width:12px;height:12px;"></i>
                    <span>Catalog</span>
                    <i data-lucide="chevron-right" style="width:12px;height:12px;"></i>
                    <span class="text-ink-soft font-semibold">Products</span>
                </nav>
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <h1 class="text-xl font-bold text-ink-emphasis mb-0">Products</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-feedback-info/15 text-feedback-info">
                        <i data-lucide="globe" style="width:11px;height:11px;" class="me-1"></i> Marketplace Catalog
                    </span>
                </div>
                <p class="text-sm text-ink-secondary mb-0">Review and moderate every product across all sellers.</p>
            </div>
        </div>
    </div>
</section>

{{-- ═══ KPI TILES ═══ --}}
@php
    $tiles = [
        ['key' => 'total',     'label' => 'Total Products',  'top' => '#F85606', 'text' => 'text-brand-deep',        'icon' => 'package'],
        ['key' => 'pending',   'label' => 'Pending Approval','top' => '#0ea5e9', 'text' => 'text-feedback-info',     'icon' => 'hourglass'],
        ['key' => 'active',    'label' => 'Active',          'top' => '#10b981', 'text' => 'text-feedback-success',  'icon' => 'check-circle-2'],
        ['key' => 'inactive',  'label' => 'Inactive',        'top' => '#b7791a', 'text' => 'text-feedback-warning',  'icon' => 'pause-circle'],
        ['key' => 'deleted',   'label' => 'Deleted',         'top' => '#ef4444', 'text' => 'text-feedback-danger',   'icon' => 'trash-2'],
    ];
@endphp
<section class="grid grid-cols-2 lg:grid-cols-5 gap-3 mb-3">
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

{{-- ═══ FILTER + TABLE CARD ═══ --}}
<section class="bg-white rounded-sm shadow-sm overflow-hidden">
    <div class="px-5 py-3 bg-surface-muted flex items-center gap-2">
        <i data-lucide="sliders-horizontal" style="width:14px;height:14px;" class="text-ink-tertiary"></i>
        <h3 class="text-sm font-bold text-ink-emphasis mb-0">Search & Filter</h3>
        <div class="grow"></div>
        @if(request('search') || request('status'))
            <a href="{{ route('admin.products.index') }}" class="text-[11px] font-semibold text-ink-tertiary hover:text-ink-emphasis inline-flex items-center gap-1">
                <i data-lucide="x" style="width:11px;height:11px;"></i> Clear
            </a>
        @endif
    </div>
    <div class="p-4 border-t border-border">
        <form method="GET" action="{{ route('admin.products.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-2">
            <div class="md:col-span-7 relative">
                <i data-lucide="search" class="absolute top-1/2 -translate-y-1/2 text-ink-tertiary" style="width:14px;height:14px; left: 10px;"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search by product name…"
                       class="w-full pl-8 pr-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
            </div>
            <div class="md:col-span-3">
                <select name="status"
                        class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors">
                    <option value="">All Status</option>
                    <option value="pending_approval" @selected(request('status') == 'pending_approval')>Pending Approval</option>
                    <option value="active"           @selected(request('status') == 'active')>Active</option>
                    <option value="inactive"         @selected(request('status') == 'inactive')>Inactive</option>
                    <option value="deleted"          @selected(request('status') == 'deleted')>Deleted</option>
                </select>
            </div>
            <div class="md:col-span-2">
                <button type="submit" class="btn btn-primary w-full">
                    <i data-lucide="search" style="width:14px;height:14px;"></i> Filter
                </button>
            </div>
        </form>
    </div>

    <div class="px-4 pt-4 pb-1 text-xs text-ink-tertiary">
        Showing <span class="text-ink-emphasis font-semibold">{{ $products->firstItem() ?? 0 }}</span>
        – <span class="text-ink-emphasis font-semibold">{{ $products->lastItem() ?? 0 }}</span>
        of <span class="text-ink-emphasis font-semibold">{{ $products->total() }}</span> products
    </div>

    <div class="overflow-x-auto px-4 pb-4">
        <table class="w-full text-left text-sm border-collapse">
            <thead class="bg-surface-muted">
                <tr>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Product</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Price</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Stock</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Date</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Status</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Seller</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary text-right">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    @php
                        $totalStockIn  = $product->variants->sum('stock_in');
                        $totalStockOut = $product->variants->sum('stock_out');
                        $totalStock    = $totalStockIn - $totalStockOut;
                        $low = $product->low_stock_quantity ?? 5;
                        $stockPill = $totalStock <= 0 ? 'bg-feedback-danger/15 text-feedback-danger'
                                    : ($totalStock <= $low ? 'bg-feedback-warning/15 text-feedback-warning'
                                    : 'bg-feedback-success/15 text-feedback-success');
                        $pillBg = match ($product->status) {
                            Product::STATUS_PENDING_APPROVAL => 'bg-feedback-info/15 text-feedback-info',
                            Product::STATUS_ACTIVE            => 'bg-feedback-success/15 text-feedback-success',
                            Product::STATUS_INACTIVE          => 'bg-feedback-warning/15 text-feedback-warning',
                            Product::STATUS_DELETED           => 'bg-feedback-danger/15 text-feedback-danger',
                            default                            => 'bg-surface-muted text-ink-tertiary',
                        };
                        $pillLabel = match ($product->status) {
                            Product::STATUS_PENDING_APPROVAL => 'Pending Approval',
                            Product::STATUS_ACTIVE            => 'Active',
                            Product::STATUS_INACTIVE          => 'Inactive',
                            Product::STATUS_DELETED           => 'Deleted',
                            default                            => ucfirst((string) $product->status),
                        };
                    @endphp
                    <tr class="border-t border-border hover:bg-surface-muted/40 transition-colors">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <img src="{{ storage_url($product->thumbnail) }}" alt="" width="40" height="40"
                                     style="object-fit:cover;border-radius:8px;" class="shrink-0">
                                <div class="min-w-0">
                                    <div class="font-semibold text-ink-emphasis text-sm">{{ $product->name }}</div>
                                    <small class="text-ink-tertiary">
                                        {{ $product->category->name ?? '—' }}
                                        @if ($product->brand)
                                            <span class="mx-1">·</span> {{ $product->brand->name }}
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 font-semibold text-ink-emphasis">{{ money($product->price) }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold {{ $stockPill }}">
                                {{ $totalStock }} {{ $product->unit->short_name ?? 'pcs' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-xs text-ink-secondary">
                            <i data-lucide="calendar" style="width:11px;height:11px;" class="me-1 align-text-bottom text-ink-tertiary"></i>
                            {{ $product->created_at->format('d M Y · H:i') }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider {{ $pillBg }}">
                                <span class="w-1.5 h-1.5 rounded-full bg-current opacity-70 me-1.5"></span>
                                {{ $pillLabel }}
                            </span>
                        </td>
                        <td class="px-4 py-3"><x-seller :seller="$product->seller" /></td>
                        <td class="px-4 py-3 text-right">
                            <button class="btn btn-primary btn-sm"
                                    data-bs-toggle="modal" data-bs-target="#statusModal-{{ $product->id }}">
                                <i data-lucide="settings" style="width:13px;height:13px;"></i> Update
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="py-10 text-center">
                                <i data-lucide="package" class="text-ink-tertiary mx-auto mb-2" style="width:36px;height:36px;"></i>
                                <p class="text-ink-soft font-semibold mb-1">No products found</p>
                                <p class="text-ink-tertiary text-xs">Marketplace products will appear here once sellers add them.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="flex justify-end p-4 border-t border-border">
        {{ $products->links() }}
    </div>
</section>

@foreach ($products as $product)
    <div class="modal fade" id="statusModal-{{ $product->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('admin.products.updateStatus', $product->id) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <div>
                            <h5 class="modal-title font-bold">Update Status</h5>
                            <small class="text-ink-tertiary">{{ $product->name }}</small>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Select Status</label>
                        <select class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors" name="status">
                            <option value="{{ Product::STATUS_PENDING_APPROVAL }}" @selected($product->status == Product::STATUS_PENDING_APPROVAL)>Pending Approval</option>
                            <option value="{{ Product::STATUS_ACTIVE }}"           @selected($product->status == Product::STATUS_ACTIVE)>Active</option>
                            <option value="{{ Product::STATUS_INACTIVE }}"         @selected($product->status == Product::STATUS_INACTIVE)>Inactive</option>
                            <option value="{{ Product::STATUS_DELETED }}"          @selected($product->status == Product::STATUS_DELETED)>Deleted</option>
                        </select>
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

@endsection
