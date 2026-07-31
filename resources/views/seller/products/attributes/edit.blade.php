@extends('seller.layouts.app')
@section('title', 'Edit Attribute')

@section('content')

<section class="bg-white rounded-sm shadow-sm overflow-hidden mb-3 relative">
    <div class="absolute top-0 left-0 right-0 h-1" style="background: linear-gradient(90deg, #06b6d4, #38bdf8, #7dd3fc);"></div>
    <div class="p-5 lg:p-6 pt-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="min-w-0">
                <nav class="flex items-center gap-1 mb-2 text-xs text-ink-tertiary">
                    <i data-lucide="list-tree" class="text-[#06b6d4]" style="width:12px;height:12px;"></i>
                    <a href="{{ route('seller.productAttributes.index') }}" class="hover:text-ink-emphasis">Attributes</a>
                    <i data-lucide="chevron-right" style="width:12px;height:12px;"></i>
                    <span class="text-ink-soft font-semibold truncate">Edit · {{ $productAttribute->name }}</span>
                </nav>
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <h1 class="text-xl font-bold text-ink-emphasis mb-0">Edit Product Attribute</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-[#06b6d4]/15 text-[#06b6d4]">
                        <i data-lucide="tag" style="width:11px;height:11px;" class="me-1"></i> Inline Editor
                    </span>
                </div>
                <p class="text-sm text-ink-secondary mb-0">Update attribute name and its option values.</p>
            </div>
            <a href="{{ route('seller.products.addAttributes', $productAttribute->product_id) }}" class="btn btn-light shrink-0">
                <i data-lucide="arrow-left" style="width:14px;height:14px;"></i> Back
            </a>
        </div>
    </div>
</section>

<div class="bg-white rounded-sm shadow-sm overflow-hidden">
    <div class="px-5 py-3 bg-surface-muted flex items-center gap-2">
        <i data-lucide="tag" style="width:14px;height:14px;" class="text-ink-tertiary"></i>
        <h3 class="text-sm font-bold text-ink-emphasis mb-0">Attribute Details</h3>
    </div>
    <div class="p-5 border-t border-border">
        <form id="editForm">
            @CSRF
            <div class="mb-3">
                <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Product Attribute Name</label>
                <input name="name" type="text" required placeholder="Enter attribute name"
                       value="{{ $productAttribute->name }}"
                       class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
            </div>

            <div id="optionsContainer">
                <div class="mb-3 flex justify-between items-center pb-2 border-b border-border">
                    <h5 class="font-bold text-sm text-ink-secondary mb-0">Edit Options</h5>
                    <button type="button" id="addOption" class="btn btn-primary btn-sm">
                        <i data-lucide="plus" style="width:13px;height:13px;"></i> Add Option
                    </button>
                </div>
                @foreach ($productAttribute->options as $index => $option)
                    <div class="mb-3 optionRow bg-surface-muted rounded-xs p-3">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mb-2">
                            <div>
                                <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Value</label>
                                <input name="options[{{ $index }}][value]" type="text" required placeholder="Option Value" value="{{ $option->value }}"
                                       class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                            </div>
                            <div>
                                <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Additional Price</label>
                                <input name="options[{{ $index }}][additional_price]" type="number" step="0.01" value="{{ $option->additional_price }}" placeholder="Additional Price" required
                                       class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors">
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button type="button" class="btn btn-light btn-sm text-feedback-danger removeOption" style="color:#dc2625;">
                                <i data-lucide="trash-2" style="width:13px;height:13px;"></i> Remove
                            </button>
                            <button type="button" class="btn btn-light btn-sm text-feedback-warning deleteOption" data-option-id="{{ $option->id }}" style="color:#b7791a;">
                                <i data-lucide="x" style="width:13px;height:13px;"></i> Delete from DB
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="flex justify-end gap-2 pt-3 border-t border-border">
                <a href="{{ route('seller.products.addAttributes', $productAttribute->product_id) }}" class="btn btn-light">
                    <i data-lucide="arrow-left" style="width:14px;height:14px;"></i> Back
                </a>
                <button type="submit" class="btn btn-primary">
                    <i data-lucide="save" style="width:14px;height:14px;"></i> Update
                </button>
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
            optionRow.classList.add("optionRow", "mb-3", "bg-surface-muted", "rounded-xs", "p-3");
            optionRow.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mb-2">
                    <div>
                        <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Value</label>
                        <input name="options[${optionIndex}][value]" type="text" placeholder="Option Value" required
                               class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Additional Price</label>
                        <input name="options[${optionIndex}][additional_price]" type="number" step="0.01" placeholder="Additional Price" required
                               class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors">
                    </div>
                </div>
                <div class="flex gap-2">
                    <button type="button" class="btn btn-light btn-sm text-feedback-danger removeOption" style="color:#dc2625;">
                        <i data-lucide="trash-2" style="width:13px;height:13px;"></i> Remove
                    </button>
                </div>
            `;
            optionsContainer.appendChild(optionRow);

            optionRow.querySelector(".removeOption").addEventListener("click", function() {
                optionsContainer.removeChild(optionRow);
            });

            if (window.renderIcons) window.renderIcons(optionRow);
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
