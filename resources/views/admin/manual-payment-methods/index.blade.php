@extends('admin.layouts.app')
@section('title','Manual Payment Gateways')

@section('content')
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">Manual Payment Methods</h4>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEditModal" onclick="openAddModal()">
                <i class="bi bi-plus-circle me-1"></i> Add New
            </button>
        </div>

        <!-- Table Card -->
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Logo</th>
                                <th>QR</th>
                                <th>Name</th>
                                <th>Account Name</th>
                                <th>Account Number</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($methods as $method)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <img src="{{ storage_url($method->image) }}" alt="{{ $method->name }}"
                                            class="rounded" width="55" height="55" />
                                    </td>
                                    <td>
                                        <img src="{{ storage_url($method->qr_image) }}" alt="{{ $method->name }}"
                                            class="rounded" width="55" height="55" />
                                    </td>
                                    <td>
                                        <strong>{{ $method->name }}</strong><br />
                                        <small class="text-muted">{{ $method->slug }}</small>
                                    </td>
                                    <td>{{ $method->account_name }}</td>
                                    <td>{{ $method->account_number }}</td>
                                    <td>
                                        @if ($method->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary me-2" data-bs-toggle="modal"
                                            data-bs-target="#addEditModal" onclick="openEditModal({{ $method }})">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <form action="{{ route('admin.manualGateways.delete', $method->id) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Are you sure?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        No manual payment methods found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div class="modal fade" id="addEditModal" tabindex="-1" aria-labelledby="addEditModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form id="manualPaymentForm" method="POST" enctype="multipart/form-data"
                    action="{{ route('admin.manualGateways.store') }}">
                    @csrf
                    <input type="hidden" name="id" id="method_id" />
                    <div class="modal-header">
                        <h5 class="modal-title" id="addEditModalLabel">
                            Add Manual Payment Method
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" id="name" class="form-control" required />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Active</label>
                                <select name="is_active" id="is_active" class="form-select">
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Description</label>
                                <textarea name="description" id="description" rows="2" class="form-control"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Account Name</label>
                                <input type="text" name="account_name" id="account_name" class="form-control" required />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Account Number</label>
                                <input type="text" name="account_number" id="account_number" class="form-control"
                                    required />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Logo</label>
                                <input type="file" name="image" id="image" class="form-control" accept="image/*" />
                                <small class="text-muted">Upload small logo image.</small>
                                <img id="methodPreviewImage" src="" alt="Preview" class="rounded-lg border mt-2"
                                    width="55" height="55" style="display:none;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">QR Image</label>
                                <input type="file" name="qr_image" id="qr_image" class="form-control"
                                    accept="image/*" />
                                <small class="text-muted">Upload small QR image.</small>
                                <img id="methodPreviewQRImage" src="" alt="Preview"
                                    class="rounded-lg border mt-2" width="55" height="55" style="display:none;">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function openAddModal() {
            document.getElementById("manualPaymentForm").reset();
            document.getElementById("addEditModalLabel").textContent =
                "Add Manual Payment Method";
            document.getElementById("method_id").value = "";
            document
                .getElementById("manualPaymentForm")
                .setAttribute("action", "{{ route('admin.manualGateways.store') }}");
        }

        function openEditModal(method) {
            const manualGatewayUpdateUrl = "{{ route('admin.manualGateways.update', ':id') }}";
            document.getElementById("addEditModalLabel").textContent =
                "Edit Manual Payment Method";
            document.getElementById("method_id").value = method.id;
            document.getElementById("name").value = method.name;
            document.getElementById("description").value = method.description || "";
            document.getElementById("account_name").value = method.account_name;
            document.getElementById("account_number").value = method.account_number;
            document.getElementById("is_active").value = method.is_active ? 1 : 0;
            document
                .getElementById("manualPaymentForm")
                .setAttribute(
                    "action",
                    manualGatewayUpdateUrl.replace(":id", method.id)
                );

            const form = document.getElementById("manualPaymentForm");
            const methodInput =
                document.getElementById("_method") ||
                Object.assign(document.createElement("input"), {
                    type: "hidden",
                    name: "_method",
                    id: "_method",
                    value: "PUT",
                });
            form.appendChild(methodInput);

            const imagePreview = document.getElementById("methodPreviewImage");
            if (method.image) {
                const imageUrl = `/storage/${method.image}`;
                imagePreview.src = imageUrl;
                imagePreview.style.display = "block";
            } else {
                imagePreview.src = "";
                imagePreview.style.display = "none";
            }
            const QrImagePreview = document.getElementById("methodPreviewQRImage");
            if (method.image) {
                const imageUrl = `/storage/${method.qr_image}`;
                QrImagePreview.src = imageUrl;
                QrImagePreview.style.display = "block";
            } else {
                QrImagePreview.src = "";
                QrImagePreview.style.display = "none";
            }
        }
    </script>
@endpush
