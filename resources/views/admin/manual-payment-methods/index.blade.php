@extends('admin.layouts.app')
@section('title', 'Manual Payment Gateways')

@section('content')
    <div class="container-fluid py-4">
        <div class="flex justify-between items-center mb-4">
            <h4 class="mb-0">Manual Payment Methods</h4>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEditModal" onclick="openAddModal()">
                <i data-lucide="circle-plus" class="me-1"></i> Add New
            </button>
        </div>

        <!-- Table Card -->
        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden shadow-sm">
            <div class="p-5 p-0">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-ink border-collapse">
                        <thead class="bg-surface-muted">
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
                                        <small class="text-ink-tertiary">{{ $method->slug }}</small>
                                    </td>
                                    <td>{{ $method->account_name }}</td>
                                    <td>{{ $method->account_number }}</td>
                                    <td>
                                        @if ($method->is_active)
                                            <span class="badge bg-feedback-success">Active</span>
                                        @else
                                            <span class="badge bg-surface-muted">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-outline-primary btn-sm me-2" data-bs-toggle="modal"
                                            data-bs-target="#addEditModal" onclick="openEditModal({{ $method }})">
                                            <i data-lucide="pencil"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-danger btn-sm"
                                            onclick="confirmDelete('{{ route('admin.manualGateways.delete', $method->id) }}')">
                                            <i data-lucide="trash-2"></i>
                                        </button>

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-ink-tertiary">
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
                        <div class="grid grid-cols-1 gap-3">
                            <div class="md:col-span-1">
                                <label class="block text-xs font-medium text-ink-secondary mb-1">Name</label>
                                <input type="text" name="name" id="name" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" required />
                            </div>
                            <div class="md:col-span-1">
                                <label class="block text-xs font-medium text-ink-secondary mb-1">Active</label>
                                <select name="is_active" id="is_active" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors">
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                            <div class="md:col-span-full">
                                <label class="block text-xs font-medium text-ink-secondary mb-1">Description</label>
                                <textarea name="description" id="description" rows="2" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors"></textarea>
                            </div>
                            <div class="md:col-span-1">
                                <label class="block text-xs font-medium text-ink-secondary mb-1">Account Name</label>
                                <input type="text" name="account_name" id="account_name" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" required />
                            </div>
                            <div class="md:col-span-1">
                                <label class="block text-xs font-medium text-ink-secondary mb-1">Account Number</label>
                                <input type="text" name="account_number" id="account_number" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors"
                                    required />
                            </div>
                            <div class="md:col-span-1">
                                <label class="block text-xs font-medium text-ink-secondary mb-1">Logo</label>
                                <input type="file" name="image" id="image" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" accept="image/*" />
                                <small class="text-ink-tertiary">Upload text-sm logo image.</small>
                                <img id="methodPreviewImage" src="" alt="Preview" class="rounded-lg border mt-2"
                                    width="55" height="55" style="display:none;">
                            </div>
                            <div class="md:col-span-1">
                                <label class="block text-xs font-medium text-ink-secondary mb-1">QR Image</label>
                                <input type="file" name="qr_image" id="qr_image" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors"
                                    accept="image/*" />
                                <small class="text-ink-tertiary">Upload text-sm QR image.</small>
                                <img id="methodPreviewQRImage" src="" alt="Preview"
                                    class="rounded-lg border mt-2" width="55" height="55" style="display:none;">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
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
