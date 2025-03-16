@extends('seller.layouts.app')
@section('title',$product->name. ' | Add Attributes')
@section('content')

    <div class="mb-4 d-flex justify-content-between align-items-center">
        <h4 class="mb-0">{{ $product->name }} Attributes</h4>
        <a href="{{ route('seller.products.details', $product->id) }}" class="btn btn-secondary">
            Back
        </a>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="table-responsive">
                <table class="table mb-3 bg-white table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">value (price)</th>
                            <th scope="col">Date</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($productAttributes as $productAttribute)
                            <tr>
                                <td>
                                    <div>{{ $productAttribute->name }}</div>
                                </td>
                                <td>
                                    @foreach ($productAttribute->options as $option)
                                        <small>{{ $option->value }} ({{ $option->additional_price }})</small><br>
                                    @endforeach
                                </td>
                                <td>{{ $productAttribute->created_at->format('d-m-y h:i A') }} </td>
                                <td class="d-flex">
                                    <a href="{{ route('seller.products.updateAttributes', $productAttribute->id) }}"
                                        class="border btn btn-light btn-sm me-1" title="Edit">
                                        <i data-feather="edit" class="icon-xs"></i> Edit
                                    </a>
                                    <button type="submit" class="border btn btn-danger btn-sm" title="Delete"
                                        data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $productAttribute->id }}">
                                        <i data-feather="trash" class="icon-xs"></i> Delete
                                    </button>
                                </td>
                            </tr>

                            <div class="modal fade" id="deleteModal-{{ $productAttribute->id }}" tabindex="-1"
                                aria-labelledby="deleteModalLabel-{{ $productAttribute->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteModalLabel-{{ $productAttribute->id }}">
                                                Confirm
                                                Delete</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="text-center modal-body">
                                            <div class="alert alert-warning d-flex" role="alert">
                                                <i class="bi bi-exclamation-circle-fill me-2 text-danger"
                                                    style="font-size: 1.5rem;"></i>
                                                <p class="mt-1 text-secondary">
                                                    Are you sure you want to delete this Product Attribute?
                                                </p>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Cancel</button>
                                            <form
                                                action="{{ route('seller.products.deleteAttributes', $productAttribute->id) }}"
                                                method="POST">
                                                @method('DELETE')
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
        </div>
        <div class="col-md-6">
            <div class="rounded-lg shadow-sm card">
                <div class="bg-white card-header">Add Attributes</div>
                <div class="card-body">
                    <form id="form">
                        @CSRF
                        <div class="row">
                            <div class="mb-3 col-12">
                                <label class="form-label fw-bold">Product Attribute Name</label>
                                <input name="name" type="text" class="form-control"
                                    placeholder="Enter Attribute Name">
                            </div>
                        </div>
                        <div id="optionsContainer">
                            <div class="mb-3 d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Add Options</h5>
                                <button type="button" id="addOption" class="btn btn-primary btn-sm"><i
                                        data-feather="plus"></i> Add Option</button>
                            </div>
                            <div class="mb-3 optionRow">
                                <input name="options[0][value]" type="text" placeholder="Option Value"
                                    class="mb-2 form-control" required>
                                <input name="options[0][additional_price]" type="number" step="0.01"
                                    placeholder="Additional Price" class="form-control" required>
                            </div>
                        </div>
                        <div class="text-end">
                            <button type="submit" id="submitBtn" class="btn btn-success">Save Attribute</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            let optionIndex = 1;

            document.getElementById("addOption").addEventListener("click", function() {
                const optionsContainer = document.getElementById("optionsContainer");
                const optionRow = document.createElement("div");
                optionRow.classList.add("optionRow", "mb-3");
                optionRow.innerHTML = `
            <input name="options[${optionIndex}][value]" type="text" placeholder="Option Value" class="mb-2 form-control" required>
            <input name="options[${optionIndex}][additional_price]" type="number" step="0.01" placeholder="Additional Price" class="form-control" required>
            <button type="button" class="mt-2 btn btn-danger btn-sm removeOption">Remove</button>
        `;
                optionsContainer.appendChild(optionRow);

                optionRow.querySelector(".removeOption").addEventListener("click", function() {
                    optionsContainer.removeChild(optionRow);
                });

                optionIndex++;
            });

            $("#form").submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: "{{ route('seller.products.addAttributes', $product->id) }}",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        location.reload();
                    }
                });
            });
        </script>
    @endpush

@endsection
