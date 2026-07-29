@extends('seller.layouts.app')
@section('title', 'Edit Attribute')
@section('content')

    <div class="flex justify-between items-center mb-3">
        <div>
            <h4 class="font-bold mb-0">Edit Product Attribute</h4>
            <small class="text-ink-tertiary">Update attribute name and its option values</small>
        </div>
    </div>

    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
        <div class="bg-surface-muted px-4 py-2.5 border-b border-border">
            <h5 class="font-bold mb-0 text-sm"><i data-lucide="tag" class="me-2 text-brand" style="width:16px;height:16px;"></i>Attribute Details</h5>
        </div>
        <div class="p-5">
            <form id="editForm">
                @CSRF
                <div class="grid grid-cols-1">
                    <div class="mb-3 col-span-full">
                        <label class="block text-xs font-medium text-ink-secondary mb-1">Product Attribute Name</label>
                        <input name="name" type="text" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors"
                            value="{{ $productAttribute->name }}" placeholder="Enter Attribute Name" required>
                    </div>
                </div>
                <div id="optionsContainer">
                    <div class="mb-3 flex justify-between items-center pb-2 border-b border-border">
                        <h5 class="font-bold mb-0 text-sm text-ink-secondary">Edit Options</h5>
                        <button type="button" id="addOption" class="btn btn-primary btn-sm"><i
                                data-lucide="plus"></i> Add Option</button>
                    </div>
                    @foreach ($productAttribute->options as $index => $option)
                        <div class="mb-3 optionRow bg-surface-muted rounded-xs p-3 border border-border">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mb-2">
                                <div>
                                    <label class="block text-xs font-medium text-ink-secondary mb-1">Value</label>
                                    <input name="options[{{ $index }}][value]" type="text"
                                        value="{{ $option->value }}" placeholder="Option Value" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors"
                                        required>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-ink-secondary mb-1">Additional Price</label>
                                    <input name="options[{{ $index }}][additional_price]" type="number"
                                        step="0.01" value="{{ $option->additional_price }}"
                                        placeholder="Additional Price" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors" required>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <button type="button" class="btn btn-danger btn-sm removeOption"><i data-lucide="trash" class="icon-xs"></i> Remove</button>
                                <button type="button" class="btn btn-warning btn-sm deleteOption"
                                    data-option-id="{{ $option->id }}"><i data-lucide="x" class="icon-xs"></i> Delete from DB</button>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="flex justify-between pt-3 border-t border-border">
                    <a href="{{ route('seller.products.addAttributes', $productAttribute->product_id) }}"
                        class="btn btn-light">
                        <i data-lucide="arrow-left" class="icon-xs"></i> Back
                    </a>
                    <button type="submit" class="btn btn-success"><i data-lucide="save" class="icon-xs"></i> Update</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            let optionIndex = {{ count($productAttribute->options) }};

            document.getElementById("addOption").addEventListener("click", function() {
                const optionsContainer = document.getElementById("optionsContainer");
                const optionRow = document.createElement("div");
                optionRow.classList.add("optionRow", "mb-3", "bg-surface-muted", "rounded-xs", "p-3", "border", "border-border");
                optionRow.innerHTML = `
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mb-2">
                        <div>
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Value</label>
                            <input name="options[${optionIndex}][value]" type="text" placeholder="Option Value" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" required>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Additional Price</label>
                            <input name="options[${optionIndex}][additional_price]" type="number" step="0.01" placeholder="Additional Price" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors" required>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button type="button" class="btn btn-danger btn-sm removeOption"><i data-lucide="trash" class="icon-xs"></i> Remove</button>
                    </div>
                `;
                optionsContainer.appendChild(optionRow);

                optionRow.querySelector(".removeOption").addEventListener("click", function() {
                    optionsContainer.removeChild(optionRow);
                });

                optionIndex++;
            });

            document.querySelectorAll(".removeOption").forEach(button => {
                button.addEventListener("click", function() {
                    button.closest(".optionRow").remove();
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
                                button.closest(".optionRow").remove();
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