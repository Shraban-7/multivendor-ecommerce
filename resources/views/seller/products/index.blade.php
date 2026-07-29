@extends('seller.layouts.app')
@section('title', 'My Products')
@section('content')

<div class="flex flex-wrap justify-between items-center gap-2 mb-3">
    <h4 class="font-bold mb-0 text-ink">My Products</h4>
    <div class="flex gap-2">
        <input type="text" id="tableSearch" class="w-full px-3 py-1.5 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" placeholder="Search products..." style="width:200px;">
        <a href="{{ route('seller.products.create') }}" class="btn btn-primary btn-sm">
            <i data-feather="plus" class="icon-xs"></i> Add Product
        </a>
    </div>
</div>

<div class="overflow-x-auto">
    <table class="w-full text-left text-sm text-ink border-collapse table-hover align-middle bg-white mb-0" id="product-table" style="border-collapse: separate; border-spacing: 0 4px;">
        <thead class="bg-surface-muted">
            <tr>
                <th scope="col" class="text-sm font-semibold text-ink-tertiary ps-3">Product</th>
                <th scope="col" class="text-sm font-semibold text-ink-tertiary">SKU</th>
                <th scope="col" class="text-sm font-semibold text-ink-tertiary">Price</th>
                <th scope="col" class="text-sm font-semibold text-ink-tertiary text-center">Stock</th>
                <th scope="col" class="text-sm font-semibold text-ink-tertiary">Status</th>
                <th scope="col" class="text-sm font-semibold text-ink-tertiary">Added</th>
                <th scope="col" class="text-sm font-semibold text-ink-tertiary text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
            @php
                $vc = $product->variants->count();
                $minP = min($product->variants->min('price') ?: INF, $product->price);
                $maxP = max($product->variants->max('price') ?: -INF, $product->price);
            @endphp
            <tr class="product-row">
                <td class="ps-3">
                    <div class="flex items-center gap-2">
                        <img src="{{ $product->imageUrl }}" class="rounded-xs" style="width:44px;height:44px;object-fit:cover;flex-shrink:0;">
                        <div>
                            <a href="{{ route('seller.products.show', $product->slug) }}" target="__blank" class="no-underline text-ink font-semibold">{{ $product->name }}</a>
                            @if ($vc > 0)
                            <br><a href="#" class="text-sm text-ink-tertiary underline" data-bs-toggle="modal" data-bs-target="#variantsModal-{{ $product->id }}">{{ $vc }} variant(s)</a>
                            @endif
                        </div>
                    </div>
                </td>
                <td class="text-sm text-ink-tertiary">{{ $product->sku }}</td>
                <td class="whitespace-nowrap">
                    @if($vc > 0)
                    <span class="font-semibold">{{ money($minP) }}</span>
                    @if ($maxP != $minP)
                    <span class="text-ink-tertiary"> – {{ money($maxP) }}</span>
                    @endif
                    @else
                    <span class="font-semibold">{{ money($product->price) }}</span>
                    @endif
                    @if($product->compare_price)
                    <br><span class="text-sm text-ink-tertiary"><s>{{ money($product->compare_price) }}</s></span>
                    @endif
                </td>
                <td class="text-center">
                    <span class="badge px-2 py-1 rounded-md font-normal
                        @if($product->totalStock <= $product->low_stock_quantity) badge-soft-danger
                        @elseif($product->totalStock == 0) badge-soft-secondary
                        @else badge-soft-success @endif">
                        {{ $product->totalStock }} {{ $product->unit->short_name ?? 'pcs' }}
                    </span>
                </td>
                <td>
                    @if ($product->status == $product::STATUS_ACTIVE)
                    <span class="badge badge-soft-success">Active</span>
                    @elseif ($product->status == $product::STATUS_PENDING_APPROVAL)
                    <span class="badge badge-soft-warning">Pending</span>
                    @elseif ($product->status == $product::STATUS_INACTIVE)
                    <span class="badge badge-soft-secondary">Inactive</span>
                    @elseif ($product->status == $product::STATUS_DRAFT)
                    <span class="badge badge-soft-info">Draft</span>
                    @elseif ($product->status == $product::STATUS_DELETED)
                    <span class="badge badge-soft-danger">Deleted</span>
                    @endif
                    <br>
                    @if ($product->is_visible && $product->status == $product::STATUS_ACTIVE)
                    <span class="text-sm text-feedback-success"><i data-feather="eye" class="icon-xs"></i> Visible</span>
                    @elseif (!$product->is_visible && $product->status != $product::STATUS_DELETED)
                    <span class="text-sm text-ink-tertiary"><i data-feather="eye-off" class="icon-xs"></i> Hidden</span>
                    @endif
                </td>
                <td class="text-sm whitespace-nowrap">{{ $product->created_at->format('d/m/y') }}<br><span class="text-ink-tertiary">{{ $product->created_at->format('h:ia') }}</span></td>
                <td class="text-center">
                    <div class="dropdown">
                        <button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i data-feather="more-vertical" class="icon-xs"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 py-1" style="min-width:150px;">
                            <li><a class="dropdown-item py-1.5" href="{{ route('seller.products.show', $product->slug) }}" target="__blank"><i data-feather="eye" class="icon-xs me-2"></i>View Details</a></li>
                            <li><a class="dropdown-item py-1.5" href="{{ route('seller.products.edit', $product->slug) }}" target="__blank"><i data-feather="edit" class="icon-xs me-2"></i>Edit</a></li>
                            <li><a class="dropdown-item py-1.5" href="{{ route('seller.products.media.index', $product) }}"><i data-feather="image" class="icon-xs me-2"></i>Media</a></li>
                            @if ($product->status != $product::STATUS_DELETED)
                            <li><hr class="dropdown-divider my-1"></li>
                            <li>
                                <form action="{{ route('seller.products.duplicate', $product) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item py-1.5"><i data-feather="copy" class="icon-xs me-2"></i>Clone</button>
                                </form>
                            </li>
                            <li>
                                <form action="{{ route('seller.products.toggleVisibility', $product) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item py-1.5">
                                        @if ($product->is_visible)
                                        <i data-feather="eye-off" class="icon-xs me-2"></i>Hide
                                        @else
                                        <i data-feather="eye" class="icon-xs me-2"></i>Show
                                        @endif
                                    </button>
                                </form>
                            </li>
                            @endif
                            <li><hr class="dropdown-divider my-1"></li>
                            <li>
                                <button type="button" class="dropdown-item py-1.5 text-feedback-danger" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $product->id }}">
                                    <i data-feather="trash-2" class="icon-xs me-2"></i>Delete
                                </button>
                            </li>
                        </ul>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="flex justify-end mt-3">
    {{ $products->links() }}
</div>

@foreach ($products as $product)
<div class="modal fade" id="deleteModal-{{ $product->id }}" tabindex="-1" aria-labelledby="deleteModalLabel-{{ $product->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel-{{ $product->id }}">Delete Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-1">Are you sure you want to delete <strong>{{ $product->name }}</strong>?</p>
                <p class="text-feedback-danger text-sm mb-0">This action cannot be undone. All variants, images, and stock history will be permanently removed.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('seller.products.delete', $product) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="variantsModal-{{ $product->id }}" tabindex="-1" aria-labelledby="variantsModalLabel-{{ $product->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="variantsModalLabel-{{ $product->id }}">Variants – {{ $product->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if ($product->variants->count())
                <table class="w-full text-left text-sm text-ink border-collapse table-hover table-bordered mb-0">
                    <thead class="bg-surface-muted">
                        <tr>
                            <th class="text-sm font-semibold text-ink-tertiary">SKU</th>
                            <th class="text-sm font-semibold text-ink-tertiary">Options</th>
                            <th class="text-sm font-semibold text-ink-tertiary text-center">Price</th>
                            <th class="text-sm font-semibold text-ink-tertiary text-center">Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($product->variants as $variant)
                        <tr>
                            <td class="text-sm">{{ $variant->sku }}</td>
                            <td><span class="badge badge-soft-secondary">{{ $variant->label }}</span></td>
                            <td class="text-center">{{ money($variant->compare_price ?? $variant->price) }}</td>
                            <td class="text-center">{{ $variant->availableStock }} {{ $product->unit->short_name }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="p-3 text-center text-ink-tertiary">No variants found.</div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endforeach

@push('scripts')
<script>
    feather.replace();
    document.getElementById('tableSearch')?.addEventListener('keyup', function() {
        const q = this.value.toLowerCase();
        document.querySelectorAll('.product-row').forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
        });
    });
</script>
@endpush

@endsection