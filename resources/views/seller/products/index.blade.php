@extends('seller.layouts.app')
@section('title', 'My Products')
@section('content')

    <div class="flex justify-between items-start mb-4">
        <div>
            <h1 class="text-xl font-semibold text-ink">My Products</h1>
            <p class="text-sm text-ink-secondary mt-1">Manage your product catalog and inventory</p>
        </div>
        <a href="{{ route('seller.products.create') }}" class="btn btn-primary btn-sm">
            <i data-lucide="plus" class="icon-xs"></i> Add Product
        </a>
    </div>

    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden mb-4">
        <div class="px-4 py-3 border-b border-border bg-surface-muted flex items-center justify-between">
            <h6 class="text-xs font-semibold text-ink uppercase tracking-wider">Search & Filter</h6>
        </div>
        <div class="p-4">
            <form method="GET" action="{{ route('seller.products.index') }}">
                <div class="flex items-center gap-3">
                    <div class="flex-1">
                        <input type="text" name="search" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors"
                            placeholder="Search by product name or SKU..." value="{{ request('search') }}">
                    </div>
                    <div class="w-48">
                        <select name="status" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors">
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
                    @if(request('search') || request('status'))
                        <a href="{{ route('seller.products.index') }}" class="btn btn-light btn-sm">Clear</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-ink border-collapse">
            <thead>
                <tr>
                    <th scope="col">Product</th>
                    <th scope="col">Price</th>
                    <th scope="col">Stock</th>
                    <th scope="col">Date</th>
                    <th scope="col">Visibility</th>
                    <th scope="col">Status</th>
                    <th scope="col">Action</th>
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
                    <tr>
                        <td>
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
                        <td>
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
                        <td>
                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full text-white {{ $totalStock <= $product->low_stock_quantity ? 'bg-red-500' : ($totalStock == 0 ? 'bg-gray-500' : 'bg-green-500') }}">
                                {{ $totalStock }} {{ $product->unit->short_name ?? 'pcs' }}
                            </span>
                        </td>
                        <td class="text-ink-tertiary text-xs">{{ $product->created_at->format('d/m/y h:i A') }}</td>
                        <td>
                            @if ($product->is_visible && $product->status == $product::STATUS_ACTIVE)
                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full bg-surface-muted text-ink-tertiary">
                                    <i data-lucide="eye" class="icon-xs me-1"></i> Visible
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full bg-surface-muted text-ink-tertiary">
                                    <i data-lucide="eye-off" class="icon-xs me-1"></i> Hidden
                                </span>
                            @endif
                        </td>
                        <td>
                            @if ($product->status == $product::STATUS_PENDING_APPROVAL)
                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full text-ink-tertiary bg-surface-muted">Pending</span>
                            @elseif ($product->status == $product::STATUS_ACTIVE)
                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full text-white bg-green-500">Active</span>
                            @elseif ($product::STATUS_DRAFT !== null && $product->status == $product::STATUS_DRAFT)
                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full text-white bg-blue-500">Draft</span>
                            @elseif ($product->status == $product::STATUS_INACTIVE)
                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full text-ink bg-yellow-400">Inactive</span>
                            @elseif ($product->status == $product::STATUS_DELETED)
                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full text-white bg-red-500">Deleted</span>
                            @endif
                        </td>
                        <td>
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

    <div class="flex justify-end mt-4">
        {{ $products->links() }}
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