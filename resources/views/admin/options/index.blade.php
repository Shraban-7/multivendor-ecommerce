@extends('admin.layouts.app')
@section('title', 'Product Options')

@section('content')
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Product Options</h4>
        <button class="btn btn-theme btn-sm" data-bs-toggle="modal" data-bs-target="#addOptionModal">
            <i data-feather="plus" class="icon-xs"></i> Add Option
        </button>
    </div>

    <div class="table-responsive">
        <table id="product-attribute-table" class="table mb-3 bg-white table-bordered">
            <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Options</th>
                    <th scope="col">Date</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($productOptions as $productOption)
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $productOption->name }}</div>
                        </td>
                        <td>
                            @foreach ($productOption->options as $option)
                                <div class="d-flex align-items-center justify-content-between mb-2 border rounded p-2">
                                    <div>
                                        <small class="fw-semibold">{{ $option->value }}</small>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-sm btn-danger" title="Delete"
                                            data-bs-toggle="modal" data-bs-target="#deleteOptionModal-{{ $option->id }}">
                                            <i data-feather="trash" class="icon-xs"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Delete Confirmation Modal -->
                                <div class="modal fade" id="deleteOptionModal-{{ $option->id }}" tabindex="-1"
                                    aria-labelledby="deleteOptionModalLabel-{{ $option->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteOptionModalLabel-{{ $option->id }}">
                                                    Confirm Delete</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body text-center">
                                                <div class="alert alert-warning d-flex align-items-center" role="alert">
                                                    <i class="bi bi-exclamation-circle-fill me-2 text-danger"
                                                        style="font-size: 1.5rem;"></i>
                                                    <span>Are you sure you want to delete this option?</span>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cancel</button>
                                                <form action="{{ route('admin.options.option_value_delete', $option->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </td>

                        <td>{{ $productOption->created_at->format('d-m-y h:i A') }}</td>
                        <td class="d-flex">

                            @if (hasPermission('admin.productAttributes.delete'))
                                <button type="submit" class="border btn btn-danger btn-sm" title="Delete"
                                    data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $productOption->id }}">
                                    <i data-feather="trash" class="icon-xs"></i> Delete
                                </button>
                            @endif
                        </td>
                    </tr>

                    <!-- Delete Confirmation Modal -->
                    <div class="modal fade" id="deleteModal-{{ $productOption->id }}" tabindex="-1"
                        aria-labelledby="deleteModalLabel-{{ $productOption->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteModalLabel-{{ $productOption->id }}">Confirm
                                        Delete</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="text-center modal-body">
                                    <div class="alert alert-warning d-flex" role="alert">
                                        <i class="bi bi-exclamation-circle-fill me-2 text-danger"
                                            style="font-size: 1.5rem;"></i>
                                        <p class="mt-1 text-secondary">
                                            Are you sure you want to delete this Product Option?
                                        </p>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <form action="{{ route('admin.options.delete', $productOption->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="modal fade" id="addOptionModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.options.store') }}">
                    @csrf
                    <div class="modal-header bg-white text-dark">
                        <h5 class="modal-title" id="addOptionModalLabel">Add Product Option</h5>
                        <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-4">
                            <label for="attribute_name" class="form-label fw-bold">Select Existing Option</label>
                            <select class="form-select" id="attribute_name" name="option_id">
                                <option value="" disabled selected>Select an option</option>
                                @foreach ($productOptions as $option)
                                    <option value="{{ $option->id }}">{{ $option->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="text-center mb-3 fw-semibold text-muted">— or create new —</div>
                        <div class="mb-3">
                            <label for="new_attribute_name" class="form-label fw-bold">New Option Name</label>
                            <input type="text" class="form-control" id="new_attribute_name" name="name"
                                placeholder="Enter new attribute name">
                        </div>
                        <div class="mb-3">
                            <label for="attribute_value" class="form-label fw-bold">Value</label>
                            <input type="text" class="form-control" id="attribute_value" name="value"
                                placeholder="e.g., Red, XL" required>
                        </div>

                        <div class="mb-3">
                            <label for="category_id" class="form-label fw-bold">Categories</label>
                            <select class="form-select" id="category_id" name="category_id[]" multiple="multiple">
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">You can select or add new categories.</small>
                        </div>
                    </div>

                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            new DataTable('#product-attribute-table');
            $(document).ready(function() {
                $('#category_id').select2({
                    tags: true,
                    placeholder: 'Select or add categories',
                    width: '100%',
                    allowClear: true,
                    dropdownParent: $('#addOptionModal')
                });
            });
        </script>
    @endpush
@endsection
