@extends('admin.layouts.app')
@section('title', 'Sizes')

@section('content')
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Sizes</h4>
        <button class="btn btn-theme btn-sm" data-bs-toggle="modal" data-bs-target="#addSizeModal">
            <i data-feather="plus" class="icon-xs"></i> Add Size
        </button>
    </div>

    <div class="table-responsive">
        <table class="table mb-3 bg-white table-bordered">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Slug</th>
                    <th scope="col">Sort Order</th>
                    <th scope="col">Last Update</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sizes as $size)
                    <tr>
                        <td>{{ $size->id }}</td>
                        <td><strong>{{ $size->name }}</strong></td>
                        <td><code>{{ $size->slug }}</code></td>
                        <td>{{ $size->sort_order }}</td>
                        <td>{{ $size->updated_at->format('d-m-y h:i A') }}</td>
                        <td class="d-flex gap-2">
                            <button type="button" class="btn btn-light btn-sm d-flex align-items-center border"
                                data-bs-toggle="modal" data-bs-target="#editSizeModal-{{ $size->id }}">
                                <i data-feather="edit" class="icon-xs"></i>
                                <span>Edit</span>
                            </button>
                            <button type="button" class="btn btn-danger btn-sm d-flex align-items-center border"
                                data-bs-toggle="modal" data-bs-target="#deleteSizeModal-{{ $size->id }}">
                                <i data-feather="trash" class="icon-xs"></i>
                                <span>Delete</span>
                            </button>
                        </td>
                    </tr>

                    <div class="modal fade" id="editSizeModal-{{ $size->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <form method="POST" action="{{ route('admin.sizes.update', $size->id) }}">
                                    @csrf
                                    <div class="modal-header bg-white text-dark">
                                        <h5 class="modal-title">Edit Size</h5>
                                        <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Name</label>
                                            <input type="text" class="form-control" name="name" value="{{ $size->name }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Sort Order</label>
                                            <input type="number" class="form-control" name="sort_order" value="{{ $size->sort_order }}" min="0">
                                            <small class="text-muted">Lower numbers appear first in listings.</small>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="deleteSizeModal-{{ $size->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Confirm Delete</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="text-center modal-body">
                                    <div class="alert alert-warning d-flex" role="alert">
                                        <i class="bi bi-exclamation-circle-fill me-2 text-danger" style="font-size: 1.5rem;"></i>
                                        <p class="mt-1 text-secondary mb-0">
                                            Are you sure you want to delete size <strong>{{ $size->name }}</strong>?
                                            Variants using this size will not be affected (size will be set to null).
                                        </p>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <form action="{{ route('admin.sizes.delete', $size->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No sizes found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $sizes->links() }}

    <div class="modal fade" id="addSizeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.sizes.store') }}">
                    @csrf
                    <div class="modal-header bg-white text-dark">
                        <h5 class="modal-title">Add Size</h5>
                        <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Name</label>
                            <input type="text" class="form-control" name="name" placeholder="e.g. XL, 42, Large" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Sort Order</label>
                            <input type="number" class="form-control" name="sort_order" value="50" min="0">
                            <small class="text-muted">Lower numbers appear first in listings.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
