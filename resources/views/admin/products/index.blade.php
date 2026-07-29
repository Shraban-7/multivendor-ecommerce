@extends('admin.layouts.app')
@section('title', 'Products')
@section('content')

    <div class="flex justify-between items-start mb-4">
        <div>
            <h1 class="text-xl font-semibold text-ink">Products</h1>
            <p class="text-sm text-ink-secondary mt-1">Review and manage all marketplace products</p>
        </div>
    </div>

    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden mb-4">
        <div class="px-4 py-3 border-b border-border bg-surface-muted flex items-center justify-between">
            <h6 class="text-xs font-semibold text-ink uppercase tracking-wider">Search & Filter</h6>
        </div>
        <div class="p-4">
            <form method="GET" action="{{ route('admin.products.index') }}">
                <div class="flex items-center gap-3">
                    <div class="flex-1">
                        <input type="text" name="search" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors"
                            placeholder="Search by product name..." value="{{ request('search') }}">
                    </div>
                    <div class="w-48">
                        <select name="status" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors">
                            <option value="">All Status</option>
                            <option value="pending_approval" {{ request('status') == 'pending_approval' ? 'selected' : '' }}>Pending Approval</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="deleted" {{ request('status') == 'deleted' ? 'selected' : '' }}>Deleted</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i data-lucide="search" class="icon-xs"></i> Search
                    </button>
                    @if(request('search') || request('status'))
                        <a href="{{ route('admin.products.index') }}" class="btn btn-light btn-sm">Clear</a>
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
                    <th scope="col">Status</th>
                    <th scope="col">Seller</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    @php
                        $totalStockIn = $product->variants->sum('stock_in');
                        $totalStockOut = $product->variants->sum('stock_out');
                        $totalStock = $totalStockIn - $totalStockOut;
                    @endphp
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <img src="{{ storage_url($product->thumbnail) }}"
                                    class="w-12 h-12 rounded-full border object-cover" alt="Image">
                                <div>
                                    <div class="font-semibold text-ink">{{ $product->name }}</div>
                                    <div class="text-xs text-ink-tertiary leading-tight">
                                        Category: {{ $product->category->name }}
                                        @if ($product->brand)
                                            <br> Brand: {{ $product->brand->name }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>{{ money($product->price) }}</td>
                        <td>{{ $totalStock }} {{ $product->unit->short_name }}</td>
                        <td class="text-ink-tertiary text-xs">{{ $product->created_at->format('d/m/y h:i A') }}</td>
                        <td>
                            @if ($product->status == $product::STATUS_PENDING_APPROVAL)
                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-ink-tertiary bg-surface-muted rounded-full">Pending Approval</span>
                            @elseif ($product->status == $product::STATUS_ACTIVE)
                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-white bg-green-500 rounded-full">Active</span>
                            @elseif ($product->status == $product::STATUS_INACTIVE)
                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-ink bg-yellow-400 rounded-full">Inactive</span>
                            @elseif ($product->status == $product::STATUS_DELETED)
                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-white bg-red-500 rounded-full">Deleted</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-ink-tertiary bg-surface-muted rounded-full">Unknown</span>
                            @endif
                        </td>
                        <td>
                            <x-seller :seller="$product->seller" />
                        </td>
                        <td>
                            <button class="btn btn-primary btn-sm"
                                data-bs-toggle="modal" data-bs-target="#statusModal-{{ $product->id }}">
                                <i data-lucide="edit" class="icon-xs"></i>
                                <span>Edit</span>
                            </button>
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
    <div class="modal fade" id="statusModal-{{ $product->id }}" tabindex="-1"
        aria-labelledby="statusModalLabel-{{ $product->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('admin.products.updateStatus', $product->id) }}" method="POST">
                    @csrf
                    <div class="modal-header border-b border-border">
                        <h5 class="modal-title text-sm font-semibold text-ink" id="statusModalLabel-{{ $product->id }}">
                            Update Product Status
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="status-{{ $product->id }}" class="block text-xs font-medium text-ink-secondary mb-1">Select Status</label>
                            <select class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors" id="status-{{ $product->id }}" name="status">
                                <option value="{{ \App\Domain\Product\Models\Product::STATUS_PENDING_APPROVAL }}"
                                    {{ $product->status == \App\Domain\Product\Models\Product::STATUS_PENDING_APPROVAL ? 'selected' : '' }}>Pending Approval</option>
                                <option value="{{ \App\Domain\Product\Models\Product::STATUS_ACTIVE }}"
                                    {{ $product->status == \App\Domain\Product\Models\Product::STATUS_ACTIVE ? 'selected' : '' }}>Active</option>
                                <option value="{{ \App\Domain\Product\Models\Product::STATUS_INACTIVE }}"
                                    {{ $product->status == \App\Domain\Product\Models\Product::STATUS_INACTIVE ? 'selected' : '' }}>Inactive</option>
                                <option value="{{ \App\Domain\Product\Models\Product::STATUS_DELETED }}"
                                    {{ $product->status == \App\Domain\Product\Models\Product::STATUS_DELETED ? 'selected' : '' }}>Deleted</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-t border-border">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach

@endsection