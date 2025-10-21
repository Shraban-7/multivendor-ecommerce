@extends('admin.layouts.app')
@section('title', 'Payment Gateways')

@section('content')
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Payment Gateways</h4>

        {{-- @if (hasPermission('admin.settings.payment_gateways.store')) --}}
        <a href="{{ route('admin.paymentGateways.create') }}" class="btn btn-primary">
            <i data-feather="plus" class="icon-xs"></i> Add Gateway
        </a>
        {{-- @endif --}}
    </div>

    <div class="table-responsive">
        <table class="table mb-3 bg-white table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Image</th>
                    <th>Link</th>
                    <th>Credential</th>
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
                            @if ($gateway->payment_url)
                                <a href="{{ $gateway->payment_url }}" target="_blank">{{ $gateway->payment_url }}</a>
                            @else
                            @endif
                        </td>
                        <td>
                            @if (!empty($gateway->credentials) && is_array($gateway->credentials))
                                @foreach ($gateway->credentials as $key => $value)
                                    <div><strong>{{ ucwords(str_replace('_', ' ', $key)) }}:</strong> {{ $value }}
                                    </div>
                                @endforeach
                            @endif
                        </td>

                        <td>
                            @if ($gateway->is_enabled)
                                <span class="badge bg-success">Enabled</span>
                            @else
                                <span class="badge bg-danger">Disabled</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.paymentGateways.edit', $gateway->id) }}"
                                class="btn btn-sm btn-light border">
                                Edit
                            </a>

                            <button type="button" class="btn btn-sm btn-danger border" data-bs-toggle="modal"
                                data-bs-target="#deleteModal-{{ $gateway->id }}">
                                Delete
                            </button>

                            <div class="modal fade" id="deleteModal-{{ $gateway->id }}" tabindex="-1"
                                aria-labelledby="deleteModalLabel-{{ $gateway->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteModalLabel-{{ $gateway->id }}">Confirm
                                                Deletion</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to delete <strong>{{ $gateway->name }}</strong>?
                                        </div>
                                        <div class="modal-footer">
                                            <form action="{{ route('admin.paymentGateways.destroy', $gateway->id) }}"
                                                method="POST">
                                                @csrf
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-danger">Yes, Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Add Modal -->
    {{-- <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('admin.settings.paymentOptions.store') }}" method="POST" enctype="multipart/form-data"
                class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Payment Gate</h5>
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


    @foreach ($paymentOptions as $Option)
        <!-- Edit Modal -->
        <div class="modal fade" id="editModal-{{ $option->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <form action="{{ route('admin.settings.paymentOptions.update', $option->id) }}" method="POST"
                    enctype="multipart/form-data" class="modal-content">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Payment Option</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" value="{{ $option->name }}"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Link</label>
                            <input type="url" name="link" class="form-control" value="{{ $option->link }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Image (optional)</label>
                            <input type="file" name="image" class="form-control">
                            @if ($option->image)
                                <div class="mt-2">
                                    <label class="form-label">Current Image:</label><br>
                                    <img src="{{ storage_url($option->image) }}" class="img-fluid rounded"
                                        style="max-height: 60px;">
                                </div>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="1" {{ $option->status ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ !$option->status ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach --}}
@endsection
