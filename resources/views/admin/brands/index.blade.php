@extends('admin.layouts.app')
@section('title', 'Brands')
@section('content')
    <div class="mb-3 d-flex justify-content-between align-items-end">
        <h4 class="mb-0">Brands</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
            <i data-feather="plus" class="icon-xs"></i> Add Brand
        </button>
    </div>

    <div class="table-responsive">
        <table class="table mb-3 bg-white table-bordered">
            <thead class="table-white">
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
                            <img src="{{ storage_url($brand->image) }}" alt="{{ $brand->name }}" class="img-thumbnail"
                                style="height: 50px;">
                        </td>
                        <td>{{ $brand->name }}</td>

                        <td class="d-flex align-items-center gap-2">
                            <button type="button" class="btn btn-sm {{ $brand->status ? 'btn-danger' : 'btn-success' }}"
                                data-bs-toggle="modal" data-bs-target="#toggleStatusModal{{ $brand->id }}">
                                {{ $brand->status ? 'Inactive' : 'Active' }}
                            </button>

                            <!-- Confirmation Modal -->
                            <div class="modal fade" id="toggleStatusModal{{ $brand->id }}" tabindex="-1"
                                aria-labelledby="toggleStatusModalLabel{{ $brand->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="toggleStatusModalLabel{{ $brand->id }}">Confirm
                                                Action</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to {{ $brand->status ? 'deactivate' : 'activate' }}
                                            this category?
                                        </div>
                                        <div class="modal-footer">
                                            <form action="{{ route('admin.brands.toggleStatus', $brand->id) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-primary">Yes, Confirm</button>
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cancel</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-light border btn-sm" data-bs-toggle="modal"
                                data-bs-target="#editModal-{{ $brand->id }}">
                                <i data-feather="edit" class="icon-xs"></i> Edit
                            </button>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editModal-{{ $brand->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <form action="{{ route('admin.brands.update', $brand->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Brand</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Brand Name</label>
                                            <input type="text" name="name" class="form-control"
                                                value="{{ $brand->name }}" required>
                                        </div>
                                        <div class="mb-3 col-6">
                                            <label class="form-label">Image</label>
                                            <x-image-input name="image" :image="storage_url($brand->image)" />
                                        </div>
                                        <button type="submit" class="btn btn-theme">Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-end">
        {{ $brands->links() }}
    </div>

    <!-- Add Brand Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Brand</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Brand Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Image</label>
                            <x-image-input name="image" />
                        </div>
                        <button type="submit" class="btn btn-theme">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
