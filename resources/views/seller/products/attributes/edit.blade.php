@extends('seller.layouts.app')
@section('title', 'Edit Attribute')
@section('content')

    <div class="mb-4 d-flex justify-content-between align-items-center">
        <h4 class="fw-bold mb-0 text-dark">Edit Product Attribute</h4>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="rounded-lg shadow-sm card border-0" style="border-radius: 12px;">
                <div class="card-body">
                    <form id="editForm">
                        @CSRF
                        <div class="row">
                            <div class="mb-3 col-12">
                                <label class="form-label fw-bold">Product Attribute Name</label>
                                <input name="name" type="text" class="form-control"
                                    value="{{ $productAttribute->name }}" placeholder="Enter Attribute Name" required>
                            </div>
                        </div>
                        <div id="optionsContainer">
                            <div class="mb-3 d-flex justify-content-between align-items-center">
                                <h5 class="fw-semibold mb-0">Edit Options</h5>
                                <button type="button" id="addOption" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1"><i
                                        data-feather="plus"></i> Add Option</button>
                            </div>
                            @foreach ($productAttribute->options as $index => $option)
                                <div class="mb-3 optionRow">
                                    <input name="options[{{ $index }}][value]" type="text"
                                        value="{{ $option->value }}" placeholder="Option Value" class="mb-2 form-control"
                                        required>
                                    <input name="options[{{ $index }}][additional_price]" type="number"
                                        step="0.01" value="{{ $option->additional_price }}"
                                        placeholder="Additional Price" class="form-control" required>
                                    <button type="button" class="mt-2 btn btn-danger btn-sm removeOption">Remove</button>
                                    <button type="button" class="mt-2 btn btn-warning btn-sm deleteOption"
                                        data-option-id="{{ $option->id }}">Delete</button>
                                </div>
                            @endforeach
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('seller.products.addAttributes', $productAttribute->product_id) }}"
                                class="btn btn-secondary d-inline-flex align-items-center gap-1">
                                Back
                            </a>
                            <button type="submit" class="btn btn-success d-inline-flex align-items-center gap-1">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            let optionIndex = {{ count($productAttribute->options) }};

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

            document.querySelectorAll(".removeOption").forEach(button => {
                button.addEventListener("click", function() {
                    button.parentElement.remove();
                });
            });

            document.querySelectorAll(".deleteOption").forEach(button => {
                button.addEventListener("click", function() {
                    const optionId = button.getAttribute('data-option-id');
                    if (confirm('Are you sure you want to delete this option?')) {
                        fetch(`/seller/products/deleteOption/${optionId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        }).then(response => response.json()).then(data => {
                            if (data.success) {
                                button.parentElement.remove();
                            } else {
                                alert('Failed to delete option');
                            }
                        });
                    }
                });
            });

            $("#editForm").submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: "{{ route('seller.products.updateAttributes', $productAttribute->id) }}",
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
