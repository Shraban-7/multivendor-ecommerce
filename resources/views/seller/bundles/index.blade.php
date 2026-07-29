@extends('seller.layouts.app')
@section('title', 'Product Bundles')
@section('content')

<div class="flex justify-between items-center mb-3">
    <h4 class="font-bold mb-0 text-ink">Product Bundles</h4>
    <a href="{{ route('seller.bundles.create') }}" class="btn btn-primary btn-sm">
        <i data-lucide="plus" class="icon-xs"></i> Create Bundle
    </a>
</div>

@if(session('success'))
    <div class="p-4 rounded-sm bg-emerald-50 border border-emerald-200 text-feedback-success text-sm flex items-start gap-3">{{ session('success') }}</div>
@endif

<div class="overflow-x-auto">
    <table class="w-full text-left text-sm text-ink border-collapse">
        <thead class="bg-surface-muted">
            <tr>
                <th class="text-sm font-semibold text-ink-tertiary">Bundle</th>
                <th class="text-sm font-semibold text-ink-tertiary">SKU</th>
                <th class="text-sm font-semibold text-ink-tertiary">Price</th>
                <th class="text-sm font-semibold text-ink-tertiary">Stock</th>
                <th class="text-sm font-semibold text-ink-tertiary">Status</th>
                <th class="text-sm font-semibold text-ink-tertiary">Items</th>
                <th class="text-sm font-semibold text-ink-tertiary">Type</th>
                <th class="text-sm font-semibold text-ink-tertiary">Date</th>
                <th class="text-sm font-semibold text-ink-tertiary">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bundles as $bundle)
            <tr>
                <td>
                    <div class="flex items-center">
                        <img src="{{ $bundle->thumbnail_url }}" class="rounded me-2"
                            style="width:50px;height:50px;object-fit:cover">
                        <div>
                            <p class="mb-0 font-bold text-sm">{{ $bundle->name }}</p>
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
                    <span class="badge px-2 py-1 rounded-md
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
                <td class="text-center text-sm">{{ $bundle->items->count() }}</td>
                <td>
                    <span class="badge bg-surface-muted text-ink border capitalize">{{ $bundle->type }}</span>
                </td>
                <td class="small">{{ $bundle->created_at->format('d/m/Y') }}</td>
                <td>
                    <div class="flex whitespace-nowrap">
                        <a href="{{ route('seller.bundles.show', $bundle) }}"
                            class="btn btn-light btn-sm me-1" title="View">
                            <i data-lucide="eye" class="icon-xs"></i>
                        </a>
                        <a href="{{ route('seller.bundles.edit', $bundle) }}"
                            class="btn btn-light btn-sm me-1" title="Edit">
                            <i data-lucide="edit" class="icon-xs"></i>
                        </a>
                        <form action="{{ route('seller.bundles.duplicate', $bundle) }}" method="POST" class="inline me-1">
                            @csrf
                            <button type="submit" class="btn btn-light btn-sm" title="Duplicate">
                                <i data-lucide="copy" class="icon-xs"></i>
                            </button>
                        </form>
                        <form action="{{ route('seller.bundles.destroy', $bundle) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-light btn-sm btn-danger-text"
                                onclick="return confirm('Delete this bundle?')" title="Delete">
                                <i data-lucide="trash-2" class="icon-xs"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center text-ink-tertiary py-4">No bundles created yet.
                    <a href="{{ route('seller.bundles.create') }}">Create your first bundle</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
{{ $bundles->links() }}

@endsection
