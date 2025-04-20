@extends('admin.layouts.app')
@section('title', 'Payment Gateways')

@section('content')
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Payment Gateways</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
            <i data-feather="plus" class="icon-xs"></i> Add Gateway
        </button>
    </div>

    <div class="table-responsive">
        <table class="table mb-3 bg-white table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Image</th>
                    <th>Link</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($paymentGateways as $gateway)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $gateway->name }}</td>
                        <td>
                            @if ($gateway->image)
                                <img src="{{ storage_url($gateway->image) }}" alt="{{ $gateway->name }}"
                                    style="max-height: 40px;">
                            @else
                                <em class="text-muted">No Image</em>
                            @endif
                        </td>
                        <td>
                            @if ($gateway->link)
                                <a href="{{ $gateway->link }}" target="_blank">{{ $gateway->link }}</a>
                            @else
                            @endif
                        </td>
                        <td>
                            @if ($gateway->status)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-light border btn-sm" data-bs-toggle="modal"
                                data-bs-target="#editModal-{{ $gateway->id }}">
                                <i data-feather="edit" class="icon-xs"></i> Edit
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('admin.settings.paymentGateways.store') }}" method="POST" enctype="multipart/form-data"
                class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Payment Gateway</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input name="name" type="text" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Link</label>
                        <input name="link" type="url" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Upload Image</label>
                        <input name="image" type="file" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="1" selected>Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-theme">Save</button>
                </div>
            </form>
        </div>
    </div>


    @foreach ($paymentGateways as $gateway)
        <!-- Edit Modal -->
        <div class="modal fade" id="editModal-{{ $gateway->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <form action="{{ route('admin.settings.paymentGateways.update', $gateway->id) }}" method="POST"
                    enctype="multipart/form-data" class="modal-content">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Payment Gateway</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" value="{{ $gateway->name }}"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Link</label>
                            <input type="url" name="link" class="form-control" value="{{ $gateway->link }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Image (optional)</label>
                            <input type="file" name="image" class="form-control">
                            @if ($gateway->image)
                                <div class="mt-2">
                                    <label class="form-label">Current Image:</label><br>
                                    <img src="{{ storage_url($gateway->image) }}" class="img-fluid rounded"
                                        style="max-height: 60px;">
                                </div>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="1" {{ $gateway->status ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ !$gateway->status ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
@endsection
