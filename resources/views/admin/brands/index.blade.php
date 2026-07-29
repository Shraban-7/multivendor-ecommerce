@extends('admin.layouts.app')
@section('title', 'Brands')
@section('content')
    <div class="flex justify-between items-start mb-4">
        <div>
            <h1 class="text-xl font-semibold text-ink">Brands</h1>
            <p class="text-sm text-ink-secondary mt-1">Manage your product brands</p>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
            <i data-lucide="plus" class="icon-xs"></i> Add Brand
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-ink border-collapse">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Logo</th>
                    <th>Brand Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($brands as $brand)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <img src="{{ storage_url($brand->image) }}" alt="{{ $brand->name }}"
                                class="border rounded-xs" style="height: 50px;">
                        </td>
                        <td>{{ $brand->name }}</td>

                        <td>
                            <div class="flex items-center gap-2">
                                @if (hasPermission('admin.brands.toggleStatus'))
                                <button type="button" class="btn btn-sm {{ $brand->status ? 'btn-danger' : 'btn-success' }}"
                                    data-bs-toggle="modal" data-bs-target="#toggleStatusModal{{ $brand->id }}">
                                    {{ $brand->status ? 'Inactive' : 'Active' }}
                                </button>
                                @endif

                                @if (hasPermission('admin.brands.update'))
                                    <button class="btn btn-light btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#editModal-{{ $brand->id }}">
                                        <i data-lucide="edit" class="icon-xs"></i> Edit
                                    </button>
                                @endif
                            </div>

                            <div class="modal fade" id="toggleStatusModal{{ $brand->id }}" tabindex="-1"
                                aria-labelledby="toggleStatusModalLabel{{ $brand->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header border-b border-border">
                                            <h5 class="modal-title text-sm font-semibold text-ink" id="toggleStatusModalLabel{{ $brand->id }}">Confirm Action</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-sm text-ink-secondary">
                                            Are you sure you want to {{ $brand->status ? 'deactivate' : 'activate' }}
                                            this brand?
                                        </div>
                                        <div class="modal-footer border-t border-border">
                                            <form action="{{ route('admin.brands.toggleStatus', $brand->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-primary">Yes, Confirm</button>
                                                <button type="button" class="btn btn-light"
                                                    data-bs-dismiss="modal">Cancel</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <div class="modal fade" id="editModal-{{ $brand->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <form action="{{ route('admin.brands.update', $brand->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-header border-b border-border">
                                        <h5 class="modal-title text-sm font-semibold text-ink">Edit Brand</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="block text-xs font-medium text-ink-secondary mb-1">Brand Name</label>
                                            <input type="text" name="name" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors"
                                                value="{{ $brand->name }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="block text-xs font-medium text-ink-secondary mb-1">Image</label>
                                            <x-image-input name="image" :image="storage_url($brand->image)" />
                                        </div>
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="flex justify-end mt-4">
        {{ $brands->links() }}
    </div>

    <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header border-b border-border">
                        <h5 class="modal-title text-sm font-semibold text-ink">Add New Brand</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Brand Name</label>
                            <input type="text" name="name" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" required>
                        </div>
                        <div class="mb-3">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Image</label>
                            <x-image-input name="image" />
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection