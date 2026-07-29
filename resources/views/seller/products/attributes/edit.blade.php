@extends('seller.layouts.app')
@section('title', 'Edit Attribute')
@section('content')

    <div class="mb-4 flex justify-between items-center">
        <h4 class="font-bold mb-0 text-ink">Edit Product Attribute</h4>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2">
        <div class="md:col-span-1">
            <div class="rounded-lg bg-white border border-border shadow-sm overflow-hidden border-0" style="border-radius: 12px;">
                <div class="p-5">
                    <form id="editForm">
                        @CSRF
                        <div class="grid grid-cols-1">
                            <div class="mb-3 col-span-full">
                                <label class="block text-xs font-medium text-ink-secondary mb-1 font-bold">Product Attribute Name</label>
                                <input name="name" type="text" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors"
                                    value="{{ $productAttribute->name }}" placeholder="Enter Attribute Name" required>
                            </div>
                        </div>
                        <div id="optionsContainer">
                            <div class="mb-3 flex justify-between items-center">
                                <h5 class="font-semibold mb-0">Edit Options</h5>
                                <button type="button" id="addOption" class="inline-flex items-center justify-center px-3 py-1.5 bg-brand-deep text-white text-sm font-medium rounded-xs hover:bg-brand focus:outline-none focus:ring-2 focus:ring-brand-tint disabled:opacity-50 transition-colors gap-1"><i
                                        data-feather="plus"></i> Add Option</button>
                            </div>
                            @foreach ($productAttribute->options as $index => $option)
                                <div class="mb-3 optionRow">
                                    <input name="options[{{ $index }}][value]" type="text"
                                        value="{{ $option->value }}" placeholder="Option Value" class="mb-2 w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors"
                                        required>
                                    <input name="options[{{ $index }}][additional_price]" type="number"
                                        step="0.01" value="{{ $option->additional_price }}"
                                        placeholder="Additional Price" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors" required>
                                    <button type="button" class="mt-2 inline-flex items-center justify-center px-3 py-1.5 bg-feedback-danger text-white text-sm font-medium rounded-xs hover:bg-red-700 focus:outline-none transition-colors removeOption">Remove</button>
                                    <button type="button" class="mt-2 inline-flex items-center justify-center px-3 py-1.5 bg-feedback-warning text-white text-sm font-medium rounded-xs hover:bg-yellow-700 focus:outline-none transition-colors deleteOption"
                                        data-option-id="{{ $option->id }}">Delete</button>
                                </div>
                            @endforeach
                        </div>
                        <div class="flex justify-between">
                            <a href="{{ route('seller.products.addAttributes', $productAttribute->product_id) }}"
                                class="inline-flex items-center justify-center px-4 py-2 bg-surface-muted text-ink text-sm font-medium border border-border rounded-xs hover:bg-border/30 focus:outline-none transition-colors gap-1">
                                Back
                            </a>
                            <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-feedback-success text-white text-sm font-medium rounded-xs hover:bg-green-700 focus:outline-none transition-colors gap-1">Update</button>
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
            <input name="options[${optionIndex}][value]" type="text" placeholder="Option Value" class="mb-2 w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" required>
            <input name="options[${optionIndex}][additional_price]" type="number" step="0.01" placeholder="Additional Price" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors" required>
            <button type="button" class="mt-2 inline-flex items-center justify-center px-3 py-1.5 bg-feedback-danger text-white text-sm font-medium rounded-xs hover:bg-red-700 focus:outline-none transition-colors removeOption">Remove</button>
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