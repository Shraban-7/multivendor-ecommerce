@extends('admin.layouts.app')
@section('title', 'Product Options')

@section('content')
<div class="mb-3 d-flex justify-content-between align-items-center">
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
                <th scope="col">Categories</th>
                <th scope="col">Values</th>
                <th scope="col">Last Update</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($options as $productOption)
            <tr>
                <td>
                    <div class="fw-semibold">{{ $productOption->name }}</div>
                </td>
                <td>
                    <div class="d-flex flex-wrap">
                        @foreach ($productOption->categories as $category)
                        <span class="badge bg-light border text-dark me-1 mb-1">{{ $category->name }}</span>
                        @endforeach
                    </div>
                </td>
                <td>
                    <div class="d-flex flex-wrap">
                        @foreach ($productOption->option_values as $optionValue)
                        <span class="badge bg-light border text-dark me-1 mb-1">{{ $optionValue->value }}</span>
                        @endforeach
                    </div>
                </td>

                <td>{{ $productOption->updated_at->format('d-m-y h:i A') }}</td>
                <td class="d-flex gap-2">
                    @if (hasPermission('admin.options.delete'))
                    <button type="button" class="btn btn-light btn-sm d-flex align-items-center border"
                        data-bs-toggle="modal" data-bs-target="#editOptionModal-{{ $productOption->id }}">
                        <i data-feather="edit" class="icon-xs"></i>
                        <span>Edit</span>
                    </button>
                    @endif

                    @if (hasPermission('admin.options.delete'))
                    <button type="button" class="btn btn-danger btn-sm d-flex align-items-center border"
                        data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $productOption->id }}">
                        <i data-feather="trash" class="icon-xs"></i>
                        <span>Delete</span>
                    </button>
                    @endif
                </td>
            </tr>

            <div class="modal fade" id="editOptionModal-{{ $productOption->id }}" tabindex="-1"
                aria-labelledby="editOptionModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form method="POST" action="{{ route('admin.options.update', $productOption->id) }}"
                            id="editOptionForm-{{ $productOption->id }}">
                            @csrf

                            <div class="modal-header bg-white text-dark">
                                <h5 class="modal-title" id="editOptionModalLabel">Edit Product Option</h5>
                                <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="edit_option_name_{{ $productOption->id }}"
                                        class="form-label fw-bold">Option Name</label>
                                    <input type="text" class="form-control"
                                        id="edit_option_name_{{ $productOption->id }}" name="name"
                                        value="{{ $productOption->name }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_category_id_{{ $productOption->id }}"
                                        class="form-label fw-bold">Categories</label>
                                    <select class="form-select select2-categories"
                                        id="edit_category_id_{{ $productOption->id }}" name="categories[]"
                                        multiple="multiple" required>
                                        @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ in_array($category->id, $productOption->categories->pluck('id')->toArray()) ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_attribute_value_{{ $productOption->id }}"
                                        class="form-label fw-bold">Values</label>
                                    <select class="form-select select2-values"
                                        id="edit_attribute_value_{{ $productOption->id }}" name="values[]"
                                        multiple="multiple" required>
                                        @foreach ($productOption->option_values as $value)
                                        <option value="{{ $value->value }}" selected>{{ $value->value }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Type and press Enter or comma to add multiple
                                        values.</small>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary"
                                    data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


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
                                    Are you sure you want to delete {{ $productOption->name }} Option?
                                </p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">Cancel</button>
                            <form action="{{ route('admin.options.delete', $productOption->id) }}"
                                method="POST">
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

{{ $options->links() }}

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
                    <div class="mb-3">
                        <label for="option_name" class="form-label fw-bold">Option Name</label>
                        <input type="text" class="form-control" id="option_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="category_id" class="form-label fw-bold">Categories</label>
                        <select class="form-select" id="category_id" name="categories[]" multiple="multiple"
                            required>
                            @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="attribute_value" class="form-label fw-bold">Values</label>
                        <select class="form-select" id="attribute_value" name="values[]" multiple="multiple"
                            required></select>
                        <small class="text-muted">Type and press Enter or comma to add multiple values.</small>
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

@push('scripts')
<script>
    $(document).ready(function() {
        $('#addOptionModal').on('shown.bs.modal', function() {
            $('#attribute_value').select2({
                tags: true,
                placeholder: 'Enter values (e.g., Red, XL)',
                width: '100%',
                dropdownParent: $('#addOptionModal'),
                tokenSeparators: [',', ' '],
                createTag: function(params) {
                    var term = $.trim(params.term);
                    if (term === '') return null;
                    return {
                        id: term,
                        text: term,
                        newTag: true
                    };
                }
            });

            $('#category_id').select2({
                tags: true,
                placeholder: 'Select categories',
                width: '100%',
                allowClear: true,
                dropdownParent: $('#addOptionModal')
            });
        });

        $('[id^=editOptionModal-]').on('shown.bs.modal', function() {
            var $modal = $(this);

            $modal.find('.select2-values').select2({
                tags: true,
                tokenSeparators: [',', ' '],
                width: '100%',
                placeholder: "Enter or select values",
                dropdownParent: $modal
            });

            $modal.find('.select2-categories').select2({
                width: '100%',
                placeholder: "Select categories",
                dropdownParent: $modal
            });
        });

    });
</script>
@endpush