@extends('seller.layouts.app')
@section('title', 'Product Bundles')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold mb-0 text-dark">Product Bundles</h4>
    <a href="{{ route('seller.bundles.create') }}" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1">
        <i data-feather="plus" class="icon-xs"></i> Create Bundle
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="table-responsive">
    <table class="table table-bordered table-hover align-middle bg-white">
        <thead class="table-light">
            <tr>
                <th class="small fw-semibold text-muted">Bundle</th>
                <th class="small fw-semibold text-muted">SKU</th>
                <th class="small fw-semibold text-muted">Price</th>
                <th class="small fw-semibold text-muted">Stock</th>
                <th class="small fw-semibold text-muted">Status</th>
                <th class="small fw-semibold text-muted">Items</th>
                <th class="small fw-semibold text-muted">Type</th>
                <th class="small fw-semibold text-muted">Date</th>
                <th class="small fw-semibold text-muted">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bundles as $bundle)
            <tr>
                <td>
                    <div class="d-flex align-items-center">
                        <img src="{{ $bundle->thumbnail_url }}" class="rounded me-2"
                            style="width:50px;height:50px;object-fit:cover">
                        <div>
                            <p class="mb-0 fw-bold small">{{ $bundle->name }}</p>
                            @if($bundle->is_visible)
                                <span class="badge badge-soft-success" style="font-size:10px;">Visible</span>
                            @else
                                <span class="badge badge-soft-secondary" style="font-size:10px;">Hidden</span>
                            @endif
                        </div>
                    </div>
                </td>
                <td class="small">{{ $bundle->sku }}</td>
                <td class="small">{{ money($bundle->calculatePrice()) }}</td>
                <td class="text-center">
                    <span class="badge px-2 py-1 rounded-3
                        @if($bundle->total_stock <= 0) badge-soft-danger
                        @elseif($bundle->total_stock <= 5) badge-soft-warning
                        @else badge-soft-secondary @endif">
                        {{ $bundle->total_stock }}
                    </span>
                </td>
                <td>
                    @if($bundle->status === $bundle::STATUS_ACTIVE)
                        <span class="badge badge-soft-success">Active</span>
                    @elseif($bundle->status === $bundle::STATUS_PENDING_APPROVAL)
                        <span class="badge badge-soft-warning">Pending</span>
                    @elseif($bundle->status === $bundle::STATUS_INACTIVE)
                        <span class="badge badge-soft-secondary">Inactive</span>
                    @elseif($bundle->status === $bundle::STATUS_DRAFT)
                        <span class="badge badge-soft-info">Draft</span>
                    @endif
                </td>
                <td class="text-center small">{{ $bundle->items->count() }}</td>
                <td>
                    <span class="badge bg-light text-dark border text-capitalize">{{ $bundle->type }}</span>
                </td>
                <td class="small">{{ $bundle->created_at->format('d/m/Y') }}</td>
                <td>
                    <div class="d-flex text-nowrap">
                        <a href="{{ route('seller.bundles.show', $bundle) }}"
                            class="btn btn-light btn-sm border me-1" title="View">
                            <i data-feather="eye" class="icon-xs"></i>
                        </a>
                        <a href="{{ route('seller.bundles.edit', $bundle) }}"
                            class="btn btn-light btn-sm border me-1" title="Edit">
                            <i data-feather="edit" class="icon-xs"></i>
                        </a>
                        <form action="{{ route('seller.bundles.duplicate', $bundle) }}" method="POST" class="d-inline me-1">
                            @csrf
                            <button type="submit" class="btn btn-light btn-sm border" title="Duplicate">
                                <i data-feather="copy" class="icon-xs"></i>
                            </button>
                        </form>
                        <form action="{{ route('seller.bundles.destroy', $bundle) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-light btn-sm border text-danger"
                                onclick="return confirm('Delete this bundle?')" title="Delete">
                                <i data-feather="trash-2" class="icon-xs"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center text-muted py-4">No bundles created yet.
                    <a href="{{ route('seller.bundles.create') }}">Create your first bundle</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
{{ $bundles->links() }}

@endsection
