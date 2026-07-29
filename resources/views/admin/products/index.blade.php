@extends('admin.layouts.app')
@section('title', 'Products')
@section('content')

    <div class="mb-3 flex justify-between items-end">
        <h4 class="mb-0">Products</h4>
    </div>

    <div class="overflow-x-auto">
        <table id="product-table" class="w-full text-left text-sm text-ink border-collapse mb-3 bg-white table-bordered">
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
                @foreach ($products as $product)
                    @php
                        $totalStockIn = $product->variants->sum('stock_in');
                        $totalStockOut = $product->variants->sum('stock_out');
                        $totalStock = $totalStockIn = $totalStockOut;
                    @endphp
                    <tr>
                        <td>
                            <div class="flex">
                                <img src="{{ storage_url($product->thumbnail) }}" class="border rounded-full"
                                    alt="Image" style="height:64px; width:64px">
                                <div class="ms-3">
                                    <div class="font-bold">{{ $product->name }}</div>
                                    <div class="small">
                                        Category: {{ $product->category->name }}
                                        @if ($product->brand)
                                            <br> Brand: {{ $product->brand->name }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            {{ money($product->price) }}
                        </td>
                        <td>
                            {{ $totalStock }} {{ $product->unit->short_name }}
                        </td>
                        <td>{{ $product->created_at->format('d/m/y h:i A') }} </td>
                        <td>
                            @if ($product->status == $product::STATUS_PENDING_APPROVAL)
                                <span class="badge text-bg-surface-muted">Pending Approval</span>
                            @elseif ($product->status == $product::STATUS_ACTIVE)
                                <span class="badge text-bg-feedback-success">Active</span>
                            @elseif ($product->status == $product::STATUS_INACTIVE)
                                <span class="badge text-bg-feedback-warning">Inactive</span>
                            @elseif ($product->status == $product::STATUS_DELETED)
                                <span class="badge text-bg-feedback-danger">Deleted</span>
                            @else
                                <span class="badge text-bg-surface-muted">Unknown</span>
                            @endif

                        </td>
                        <td class="flex">
                            <x-seller :seller="$product->seller" />
                        </td>
                        <td>
                            <button class="btn btn-primary btn-sm"
                                data-bs-toggle="modal" data-bs-target="#statusModal-{{ $product->id }}">
                                <i data-feather="edit" class="icon-xs"></i>
                                <span>Edit</span>
                            </button>

                            <div class="modal fade" id="statusModal-{{ $product->id }}" tabindex="-1"
                                aria-labelledby="statusModalLabel-{{ $product->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <form action="{{ route('admin.products.updateStatus', $product->id) }}"
                                            method="POST">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="statusModalLabel-{{ $product->id }}">
                                                    Update Product Status
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="status-{{ $product->id }}" class="block text-xs font-medium text-ink-secondary mb-1">Select
                                                        Status</label>
                                                    <select class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors" id="status-{{ $product->id }}"
                                                        name="status">
                                                        <option value="{{ \App\Domain\Product\Models\Product::STATUS_PENDING_APPROVAL }}"
                                                            {{ $product->status == \App\Domain\Product\Models\Product::STATUS_PENDING_APPROVAL ? 'selected' : '' }}>
                                                            Pending Approval
                                                        </option>
                                                        <option value="{{ \App\Domain\Product\Models\Product::STATUS_ACTIVE }}"
                                                            {{ $product->status == \App\Domain\Product\Models\Product::STATUS_ACTIVE ? 'selected' : '' }}>
                                                            Active
                                                        </option>
                                                        <option value="{{ \App\Domain\Product\Models\Product::STATUS_INACTIVE }}"
                                                            {{ $product->status == \App\Domain\Product\Models\Product::STATUS_INACTIVE ? 'selected' : '' }}>
                                                            Inactive
                                                        </option>
                                                        <option value="{{ \App\Domain\Product\Models\Product::STATUS_DELETED }}"
                                                            {{ $product->status == \App\Domain\Product\Models\Product::STATUS_DELETED ? 'selected' : '' }}>
                                                            Deleted
                                                        </option>
                                                    </select>

                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light"
                                                    data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title text-base">Add Product</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="post">
                    @CSRF
                    <div class="modal-body">
                        <div class="grid grid-cols-1">
                            <div class="mb-3 md:col-span-1">
                                <label class="block text-xs font-medium text-ink-secondary mb-1">Category</label>
                                <select name="game_id" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors" id="gameSelect" required>
                                    <option value="" selected disabled>--Choose--</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3 md:col-span-1">
                                <label class="block text-xs font-medium text-ink-secondary mb-1">Subcategory</label>
                                <select name="game_id" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors" id="gameSelect" required>
                                    <option value="" selected disabled>--Choose--</option>

                                    <option value=""></option>

                                </select>
                            </div>

                            <div class="mb-3 md:col-span-1">
                                <label class="block text-xs font-medium text-ink-secondary mb-1">Brand</label>
                                <select name="game_id" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors" id="gameSelect" required>
                                    <option value="" selected disabled>--Choose--</option>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3 md:col-span-1">
                                <label class="block text-xs font-medium text-ink-secondary mb-1">Name</label>
                                <input name="name" type="text" value="" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" required>
                            </div>
                            <div class="mb-3 md:col-span-1">
                                <label class="block text-xs font-medium text-ink-secondary mb-1">Buying Price</label>
                                <input name="name" type="text" value="" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" required>
                            </div>
                            <div class="mb-3 md:col-span-1">
                                <label class="block text-xs font-medium text-ink-secondary mb-1">Selling Price</label>
                                <input name="name" type="text" value="" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" required>
                            </div>
                            <div class="mb-3 md:col-span-1">
                                <label class="block text-xs font-medium text-ink-secondary mb-1">Quantity</label>
                                <input name="name" type="text" value="" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" required>
                            </div>
                            <div class="mb-3 md:col-span-1">
                                <label class="block text-xs font-medium text-ink-secondary mb-1">Stock in</label>
                                <input name="name" type="text" value="" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" required>
                            </div>

                        </div>
                        <button type="submit" class="btn btn-primary">Save Contest</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            new DataTable('#product-table');
        </script>
    @endpush

@endsection
