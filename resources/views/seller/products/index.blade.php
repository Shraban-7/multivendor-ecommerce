@extends('seller.layouts.app')
@section('title', 'My Products')
@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <h4 class="fw-bold mb-0 text-dark">My Products</h4>
    <div class="d-flex gap-2">
        <input type="text" id="tableSearch" class="form-control form-control-sm" placeholder="Search products..." style="width:200px;">
        <a href="{{ route('seller.products.create') }}" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1">
            <i data-feather="plus" class="icon-xs"></i> Add Product
        </a>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-hover align-middle bg-white mb-0" id="product-table" style="border-collapse: separate; border-spacing: 0 4px;">
        <thead class="table-light">
            <tr>
                <th scope="col" class="small fw-semibold text-muted ps-3">Product</th>
                <th scope="col" class="small fw-semibold text-muted">SKU</th>
                <th scope="col" class="small fw-semibold text-muted">Price</th>
                <th scope="col" class="small fw-semibold text-muted text-center">Stock</th>
                <th scope="col" class="small fw-semibold text-muted">Status</th>
                <th scope="col" class="small fw-semibold text-muted">Added</th>
                <th scope="col" class="small fw-semibold text-muted text-center">Actions</th>
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
                    <div class="d-flex align-items-center gap-2">
                        <img src="{{ $product->imageUrl }}" class="rounded" style="width:44px;height:44px;object-fit:cover;flex-shrink:0;">
                        <div>
                            <a href="{{ route('seller.products.show', $product->slug) }}" target="__blank" class="text-decoration-none text-dark fw-semibold">{{ $product->name }}</a>
                            @if ($vc > 0)
                            <br><a href="#" class="small text-muted text-decoration-underline" data-bs-toggle="modal" data-bs-target="#variantsModal-{{ $product->id }}">{{ $vc }} variant(s)</a>
                            @endif
                        </div>
                    </div>
                </td>
                <td class="small text-muted">{{ $product->sku }}</td>
                <td class="text-nowrap">
                    @if($vc > 0)
                    <span class="fw-semibold">{{ money($minP) }}</span>
                    @if ($maxP != $minP)
                    <span class="text-muted"> – {{ money($maxP) }}</span>
                    @endif
                    @else
                    <span class="fw-semibold">{{ money($product->price) }}</span>
                    @endif
                    @if($product->compare_price)
                    <br><span class="small text-muted"><s>{{ money($product->compare_price) }}</s></span>
                    @endif
                </td>
                <td class="text-center">
                    <span class="badge px-2 py-1 rounded-3 fw-normal
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
                    <span class="small text-success"><i data-feather="eye" class="icon-xs"></i> Visible</span>
                    @elseif (!$product->is_visible && $product->status != $product::STATUS_DELETED)
                    <span class="small text-muted"><i data-feather="eye-off" class="icon-xs"></i> Hidden</span>
                    @endif
                </td>
                <td class="small text-nowrap">{{ $product->created_at->format('d/m/y') }}<br><span class="text-muted">{{ $product->created_at->format('h:ia') }}</span></td>
                <td class="text-center">
                    <div class="dropdown">
                        <button class="btn btn-light btn-sm border" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i data-feather="more-vertical" class="icon-xs"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 py-1" style="min-width:150px;">
                            <li><a class="dropdown-item py-1.5" href="{{ route('seller.products.show', $product->slug) }}" target="__blank"><i data-feather="eye" class="icon-xs me-2"></i>View Details</a></li>
                            <li><a class="dropdown-item py-1.5" href="{{ route('seller.products.edit', $product->slug) }}" target="__blank"><i data-feather="edit" class="icon-xs me-2"></i>Edit</a></li>
                            @if ($product->status != $product::STATUS_DELETED)
                            <li><hr class="dropdown-divider my-1"></li>
                            <li>
                                <form action="{{ route('seller.products.duplicate', $product) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item py-1.5"><i data-feather="copy" class="icon-xs me-2"></i>Clone</button>
                                </form>
                            </li>
                            <li>
                                <form action="{{ route('seller.products.toggleVisibility', $product) }}" method="POST" class="d-inline">
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
                                <button type="button" class="dropdown-item py-1.5 text-danger" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $product->id }}">
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

<div class="d-flex justify-content-end mt-3">
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
                <p class="text-danger small mb-0">This action cannot be undone. All variants, images, and stock history will be permanently removed.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
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
                <table class="table table-sm table-hover table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="small fw-semibold text-muted">SKU</th>
                            <th class="small fw-semibold text-muted">Options</th>
                            <th class="small fw-semibold text-muted text-center">Price</th>
                            <th class="small fw-semibold text-muted text-center">Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($product->variants as $variant)
                        <tr>
                            <td class="small">{{ $variant->sku }}</td>
                            <td><span class="badge badge-soft-secondary">{{ $variant->label }}</span></td>
                            <td class="text-center">{{ money($variant->compare_price ?? $variant->price) }}</td>
                            <td class="text-center">{{ $variant->availableStock }} {{ $product->unit->short_name }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="p-3 text-center text-muted">No variants found.</div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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