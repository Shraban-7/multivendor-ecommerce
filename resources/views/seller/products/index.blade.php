@extends('seller.layouts.app')
@section('title', 'My Products')
@section('content')

    <section class="bg-white rounded-sm shadow-sm overflow-hidden mb-4 relative">
        <div class="absolute top-0 left-0 right-0 h-1" style="background: linear-gradient(90deg, #F85606, #fb923c, #fbbf24);"></div>
        <div class="p-5 lg:p-6 pt-6">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div class="min-w-0">
                    <nav class="flex items-center gap-1 mb-2 text-xs text-ink-tertiary">
                        <i data-lucide="package" class="text-brand-deep" style="width:12px;height:12px;"></i>
                        <span>Catalog</span>
                        <i data-lucide="chevron-right" style="width:12px;height:12px;"></i>
                        <span class="text-ink-soft font-semibold">My Products</span>
                    </nav>
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        <h1 class="text-xl font-bold text-ink-emphasis mb-0">My Products</h1>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-brand-tint text-brand-deep">
                            <i data-lucide="package" style="width:11px;height:11px;" class="me-1"></i> {{ $products->total() }} Total
                        </span>
                    </div>
                    <p class="text-sm text-ink-secondary mb-0">Manage your product catalog and inventory.</p>
                </div>
                <div class="flex flex-wrap gap-2 shrink-0">
                    <a href="{{ route('seller.products.inventory') }}" class="btn btn-light btn-sm"><i data-lucide="warehouse" style="width:14px;height:14px;"></i> Inventory</a>
                    <a href="{{ route('seller.products.create') }}" class="btn btn-primary btn-sm">
                        <i data-lucide="plus" style="width:14px;height:14px;"></i> Add Product
                    </a>
                </div>
            </div>
        </div>
    </section>

    @php
        $prodKpis = [
            ['label' => 'Pending Approval', 'value' => $counts['pending'] ?? 0, 'icon' => 'hourglass', 'tone' => 'amber', 'link' => 'pending'],
            ['label' => 'Active',           'value' => $counts['active'] ?? 0,   'icon' => 'check-circle-2', 'tone' => 'emerald', 'link' => 'active'],
            ['label' => 'Draft',            'value' => $counts['draft'] ?? 0,    'icon' => 'file-pen', 'tone' => 'sky', 'link' => 'draft'],
            ['label' => 'Inactive',         'value' => $counts['inactive'] ?? 0, 'icon' => 'eye-off', 'tone' => 'neutral', 'link' => 'inactive'],
            ['label' => 'Deleted',          'value' => $counts['deleted'] ?? 0,  'icon' => 'trash-2', 'tone' => 'rose', 'link' => 'deleted'],
        ];
    @endphp
    <section class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3 mb-4">
        @foreach ($prodKpis as $kpi)
            @php
                $toneClass = match ($kpi['tone']) {
                    'amber'   => 'text-amber-700',
                    'emerald' => 'text-emerald-700',
                    'sky'     => 'text-sky-700',
                    'rose'    => 'text-rose-700',
                    default   => 'text-neutral-600',
                };
                $barClass = match ($kpi['tone']) {
                    'amber'   => 'bg-amber-400',
                    'emerald' => 'bg-emerald-400',
                    'sky'     => 'bg-sky-400',
                    'rose'    => 'bg-rose-400',
                    default   => 'bg-neutral-400',
                };
                $iconBg = match ($kpi['tone']) {
                    'amber'   => 'bg-amber-50 text-amber-600',
                    'emerald' => 'bg-emerald-50 text-emerald-600',
                    'sky'     => 'bg-sky-50 text-sky-600',
                    'rose'    => 'bg-rose-50 text-rose-600',
                    default   => 'bg-surface-muted text-ink-tertiary',
                };
            @endphp
            <a href="{{ route('seller.products.index', ['status' => $kpi['link']]) }}"
                class="block bg-white border border-border rounded-sm shadow-sm p-4 transition-shadow hover:shadow-md relative overflow-hidden">
                <div class="h-1 absolute top-0 left-0 right-0 {{ $barClass }}"></div>
                <div class="flex items-start justify-between gap-3 mt-1">
                    <div class="min-w-0 flex-1">
                        <p class="text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider mb-0">{{ $kpi['label'] }}</p>
                        <h3 class="mb-0 font-bold text-2xl {{ $toneClass }} mt-1">{{ number_format($kpi['value']) }}</h3>
                    </div>
                    <span class="shrink-0 w-10 h-10 rounded-sm flex items-center justify-center {{ $iconBg }}">
                        <i data-lucide="{{ $kpi['icon'] }}" style="width:20px;height:20px;"></i>
                    </span>
                </div>
            </a>
        @endforeach
    </section>

    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden mb-4">
        <div class="px-4 py-3 border-b border-border bg-surface-muted flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="shrink-0 w-7 h-7 rounded-sm bg-brand-tint text-brand flex items-center justify-center">
                    <i data-lucide="sliders-horizontal" style="width:14px;height:14px;"></i>
                </span>
                <h6 class="text-sm font-semibold text-ink mb-0">Search & Filter</h6>
            </div>
            @if(request('search') || request('status'))
                <a href="{{ route('seller.products.index') }}" class="btn btn-light btn-sm">Clear</a>
            @endif
        </div>
        <div class="p-4">
            <form method="GET" action="{{ route('seller.products.index') }}">
                <div class="flex items-center gap-3 flex-wrap">
                    <div class="flex-1 min-w-[200px]">
                        <input type="text" name="search" class="w-full px-3 py-2 text-sm text-ink bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors"
                            placeholder="Search by product name or SKU..." value="{{ request('search') }}">
                    </div>
                    <div class="w-48">
                        <select name="status" class="w-full px-3 py-2 text-sm text-ink bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="deleted" {{ request('status') == 'deleted' ? 'selected' : '' }}>Deleted</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i data-lucide="search" class="icon-xs"></i> Search
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
        <div class="px-4 py-3 border-b border-border bg-surface-muted flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="shrink-0 w-7 h-7 rounded-sm bg-brand-tint text-brand flex items-center justify-center">
                    <i data-lucide="package" style="width:14px;height:14px;"></i>
                </span>
                <h5 class="mb-0 font-bold text-ink">Product List</h5>
            </div>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider bg-brand-tint text-brand-deep">{{ $products->total() }} products</span>
        </div>
        <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-ink border-collapse">
            <thead>
                <tr class="bg-surface-muted/50">
                    <th scope="col" class="px-4 py-2.5 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Product</th>
                    <th scope="col" class="px-4 py-2.5 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Price</th>
                    <th scope="col" class="px-4 py-2.5 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Stock</th>
                    <th scope="col" class="px-4 py-2.5 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Date</th>
                    <th scope="col" class="px-4 py-2.5 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Visibility</th>
                    <th scope="col" class="px-4 py-2.5 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Status</th>
                    <th scope="col" class="px-4 py-2.5 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    @php
                        $vc = $product->variants->count();
                        $minP = $product->variants->min('price') ?? $product->price;
                        $maxP = $product->variants->max('price') ?? $product->price;
                        $totalStock = $product->totalStock;
                    @endphp
                    <tr class="border-t border-border hover:bg-surface-muted/40 transition-colors">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <img src="{{ $product->imageUrl }}" class="w-12 h-12 rounded-full border object-cover" alt="Image">
                                <div>
                                    <div class="font-semibold text-ink">
                                        <a href="{{ route('seller.products.show', $product->slug) }}" target="__blank" class="no-underline text-ink">{{ $product->name }}</a>
                                    </div>
                                    <div class="text-xs text-ink-tertiary leading-tight">
                                        SKU: {{ $product->sku }}
                                        @if ($vc > 0)
                                            | <a href="#" class="text-ink-tertiary underline" data-bs-toggle="modal" data-bs-target="#variantsModal-{{ $product->id }}">{{ $vc }} variant(s)</a>
                                        @endif
                                        @if ($product->category)
                                            <br> Category: {{ $product->category->name }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            @if($vc > 0)
                                <span class="font-semibold">{{ money($minP) }}</span>
                                @if ($maxP != $minP)
                                    <span class="text-ink-tertiary"> – {{ money($maxP) }}</span>
                                @endif
                            @else
                                <span class="font-semibold">{{ money($product->price) }}</span>
                            @endif
                            @if($product->compare_price)
                                <div class="text-xs text-ink-tertiary"><s>{{ money($product->compare_price) }}</s></div>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @php
                                $stockPill = $totalStock <= $product->low_stock_quantity
                                    ? ['bg-rose-50 text-rose-700', 'bg-rose-400']
                                    : ($totalStock == 0 ? ['bg-neutral-100 text-neutral-600', 'bg-neutral-400'] : ['bg-emerald-50 text-emerald-700', 'bg-emerald-400']);
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider {{ $stockPill[0] }}">
                                <span class="w-1.5 h-1.5 rounded-full bg-current opacity-70 me-1.5" style="background: {{ $stockPill[1] }};"></span>
                                {{ $totalStock }} {{ $product->unit->short_name ?? 'pcs' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-ink-tertiary text-xs">{{ $product->created_at->format('d/m/y h:i A') }}</td>
                        <td class="px-4 py-3">
                            @if ($product->is_visible && $product->status == $product::STATUS_ACTIVE)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider bg-surface-muted text-ink-tertiary">
                                    <i data-lucide="eye" class="icon-xs me-1"></i> Visible
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider bg-surface-muted text-ink-tertiary">
                                    <i data-lucide="eye-off" class="icon-xs me-1"></i> Hidden
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @php
                                $statusKey = (int) $product->status;
                                $statusPillMap = [
                                    $product::STATUS_PENDING_APPROVAL => ['bg-amber-50 text-amber-700', 'bg-amber-400', 'Pending'],
                                    $product::STATUS_ACTIVE => ['bg-emerald-50 text-emerald-700', 'bg-emerald-400', 'Active'],
                                    $product::STATUS_DRAFT => ['bg-sky-50 text-sky-700', 'bg-sky-400', 'Draft'],
                                    $product::STATUS_INACTIVE => ['bg-neutral-100 text-neutral-600', 'bg-neutral-400', 'Inactive'],
                                    $product::STATUS_DELETED => ['bg-rose-50 text-rose-700', 'bg-rose-400', 'Deleted'],
                                ];
                                [$pillBg, $dotBg, $pillLabel] = $statusPillMap[$statusKey] ?? ['bg-neutral-100 text-neutral-600', 'bg-neutral-400', 'Unknown'];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider {{ $pillBg }}">
                                <span class="w-1.5 h-1.5 rounded-full bg-current opacity-70 me-1.5" style="background: {{ $dotBg }};"></span>{{ $pillLabel }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="dropdown">
                                <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i data-lucide="edit" class="icon-xs"></i>
                                    <span>Manage</span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 py-1" style="min-width:170px;">
                                    <li><a class="dropdown-item py-1.5" href="{{ route('seller.products.show', $product->slug) }}" target="__blank"><i data-lucide="eye" class="icon-xs me-2"></i>View Details</a></li>
                                    <li><a class="dropdown-item py-1.5" href="{{ route('seller.products.edit', $product->slug) }}"><i data-lucide="edit" class="icon-xs me-2"></i>Edit</a></li>
                                    <li><a class="dropdown-item py-1.5" href="{{ route('seller.products.media.index', $product) }}"><i data-lucide="image" class="icon-xs me-2"></i>Media</a></li>
                                    @if ($product->status != $product::STATUS_DELETED)
                                    <li><hr class="dropdown-divider my-1"></li>
                                    <li>
                                        <form action="{{ route('seller.products.duplicate', $product) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="dropdown-item py-1.5"><i data-lucide="copy" class="icon-xs me-2"></i>Clone</button>
                                        </form>
                                    </li>
                                    <li>
                                        <form action="{{ route('seller.products.toggleVisibility', $product) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="dropdown-item py-1.5">
                                                @if ($product->is_visible)
                                                <i data-lucide="eye-off" class="icon-xs me-2"></i>Hide
                                                @else
                                                <i data-lucide="eye" class="icon-xs me-2"></i>Show
                                                @endif
                                            </button>
                                        </form>
                                    </li>
                                    @endif
                                    <li><hr class="dropdown-divider my-1"></li>
                                    <li>
                                        <button type="button" class="dropdown-item py-1.5 text-feedback-danger" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $product->id }}">
                                            <i data-lucide="trash-2" class="icon-xs me-2"></i>Delete
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-8 text-ink-tertiary">No products found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
        <div class="px-4 py-3 border-t border-border bg-surface-muted/40">
            <div class="flex justify-end">
                {{ $products->links() }}
            </div>
        </div>
    </div>

    @foreach ($products as $product)
    <div class="modal fade" id="deleteModal-{{ $product->id }}" tabindex="-1"
        aria-labelledby="deleteModalLabel-{{ $product->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('seller.products.delete', $product) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header border-b border-border">
                        <h5 class="modal-title text-sm font-semibold text-ink" id="deleteModalLabel-{{ $product->id }}">Delete Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-2">Are you sure you want to delete <strong>{{ $product->name }}</strong>?</p>
                        <div class="p-3 rounded-sm bg-red-50 border border-red-200 text-feedback-danger text-sm">This action cannot be undone. All variants, images, and stock history will be permanently removed.</div>
                    </div>
                    <div class="modal-footer border-t border-border">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="variantsModal-{{ $product->id }}" tabindex="-1"
        aria-labelledby="variantsModalLabel-{{ $product->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header border-b border-border">
                    <h5 class="modal-title text-sm font-semibold text-ink" id="variantsModalLabel-{{ $product->id }}">Variants – {{ $product->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if ($product->variants->count())
                    <table class="w-full text-left text-sm text-ink border-collapse">
                        <thead class="bg-surface-muted">
                            <tr>
                                <th class="px-4 py-2.5">SKU</th>
                                <th class="px-4 py-2.5">Options</th>
                                <th class="px-4 py-2.5 text-center">Price</th>
                                <th class="px-4 py-2.5 text-center">Stock</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border">
                            @foreach ($product->variants as $variant)
                            <tr>
                                <td class="px-4 py-3">{{ $variant->sku }}</td>
                                <td class="px-4 py-3"><span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full bg-surface-muted text-ink-secondary">{{ $variant->label }}</span></td>
                                <td class="px-4 py-3 text-center">{{ money($variant->compare_price ?? $variant->price) }}</td>
                                <td class="px-4 py-3 text-center">{{ $variant->availableStock }} {{ $product->unit->short_name }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <div class="p-3 text-center text-ink-tertiary">No variants found.</div>
                    @endif
                </div>
                <div class="modal-footer border-t border-border">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    @endforeach

@endsection