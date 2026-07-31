@extends('seller.layouts.app')
@section('title', 'Add Product')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .sticky-sidebar { position: sticky; top: 1.25rem; }
</style>
@endpush

@section('content')
<form id="productForm" autocomplete="off" method="POST" action="{{ route('seller.products.store') }}" enctype="multipart/form-data">
    @csrf
    <section class="bg-white rounded-sm shadow-sm overflow-hidden mb-4 relative">
        <div class="absolute top-0 left-0 right-0 h-1" style="background: linear-gradient(90deg, #F85606, #fb923c, #fbbf24);"></div>
        <div class="p-5 lg:p-6 pt-6">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div class="min-w-0">
                    <nav class="flex items-center gap-1 mb-2 text-xs text-ink-tertiary">
                        <i data-lucide="package-plus" class="text-brand-deep" style="width:12px;height:12px;"></i>
                        <a href="{{ route('seller.products.index') }}" class="hover:text-ink transition-colors">Products</a>
                        <i data-lucide="chevron-right" style="width:12px;height:12px;"></i>
                        <span class="text-ink-soft font-semibold">Add Product</span>
                    </nav>
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        <h1 class="text-xl font-bold text-ink-emphasis mb-0">Add Product</h1>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-brand-tint text-brand-deep">
                            <i data-lucide="package-plus" style="width:11px;height:11px;" class="me-1"></i> New Listing
                        </span>
                    </div>
                    <p class="text-sm text-ink-secondary mb-0">Create a new product listing.</p>
                </div>
                <div class="flex flex-wrap gap-2 shrink-0">
                    <a href="{{ route('seller.products.index') }}" class="btn btn-light btn-sm"><i data-lucide="arrow-left" style="width:14px;height:14px;"></i> Back</a>
                    <button type="button" id="submitBtn" class="btn btn-primary btn-sm">
                        <i data-lucide="save" style="width:14px;height:14px;"></i> Save Product
                    </button>
                </div>
            </div>
        </div>
    </section>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
                <div class="bg-surface-muted px-4 py-2.5 border-b border-border">
                    <h5 class="font-bold mb-0 text-sm"><i data-lucide="circle-info" class="me-2 text-brand" style="width:16px;height:16px;"></i>Basic Information</h5>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                        <div class="md:col-span-12">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Product Name</label>
                            <input name="name" type="text" class="w-full px-3 py-2 text-sm text-ink bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" required>
                        </div>
                        <div class="md:col-span-4">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Category</label>
                            <select id="categorySelect" name="category_id" class="w-full px-3 py-2 text-sm text-ink bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors" required>
                                <option disabled selected>-- Select Category --</option>
                                @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:col-span-4">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Subcategory</label>
                            <select id="subcategorySelect" name="subcategory_id" class="w-full px-3 py-2 text-sm text-ink bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors" disabled>
                                <option disabled selected>-- Select Subcategory --</option>
                                @foreach ($categories as $category)
                                @foreach ($category->subcategories as $subcategory)
                                <option value="{{ $subcategory->id }}" data-category="{{ $category->id }}">{{ $subcategory->name }}</option>
                                @endforeach
                                @endforeach
                            </select>
                        </div>
                        <div class="md:col-span-4">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Brand</label>
                            <select name="brand" class="w-full px-3 py-2 text-sm text-ink bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors brand-select">
                                <option disabled selected>-- Select Brand --</option>
                                @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:col-span-6">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Unit Value</label>
                            <input type="number" name="unit_value" class="w-full px-3 py-2 text-sm text-ink bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" placeholder="Enter value" required>
                        </div>
                        <div class="md:col-span-6">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Unit</label>
                            <select name="unit_id" class="w-full px-3 py-2 text-sm text-ink bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors" required>
                                <option disabled selected>--</option>
                                @foreach ($units as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->short_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:col-span-12">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Tags <span class="text-ink-tertiary text-sm">(comma separated)</span></label>
                            <input type="text" name="tags" class="w-full px-3 py-2 text-sm text-ink bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" placeholder="e.g. cotton, summer, casual">
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
                <div class="bg-surface-muted px-4 py-2.5 border-b border-border">
                    <h5 class="font-bold mb-0 text-sm"><i data-lucide="tags" class="me-2 text-brand" style="width:16px;height:16px;"></i>Pricing & Inventory</h5>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                        <div class="md:col-span-4">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Cost Price</label>
                            <input name="cost_price" type="number" min="0" step="0.01" class="w-full px-3 py-2 text-sm text-ink bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" required>
                        </div>
                        <div class="md:col-span-4">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Price</label>
                            <input name="price" type="number" min="0" step="0.01" class="w-full px-3 py-2 text-sm text-ink bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" required>
                        </div>
                        <div class="md:col-span-4">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Compare Price <span class="text-ink-tertiary text-sm">(optional sale)</span></label>
                            <input name="compare_price" type="number" min="0" step="0.01" class="w-full px-3 py-2 text-sm text-ink bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" placeholder="Leave empty for no sale">
                        </div>
                        <div class="md:col-span-6">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Low Stock Quantity</label>
                            <input name="low_stock_quantity" type="number" min="0" class="w-full px-3 py-2 text-sm text-ink bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" required>
                        </div>
                        <div class="md:col-span-6">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Payment Type</label>
                            <select name="payment_type" class="w-full px-3 py-2 text-sm text-ink bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors" required>
                                @foreach (App\Enums\PaymentType::cases() as $paymentType)
                                <option value="{{ $paymentType->value }}">{{ $paymentType->title() }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
                <div class="bg-surface-muted px-4 py-2.5 border-b border-border">
                    <h5 class="font-bold mb-0 text-sm"><i data-lucide="align-left" class="me-2 text-brand" style="width:16px;height:16px;"></i>Description & Specifications</h5>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                        <div class="md:col-span-12">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Short Description</label>
                            <x-textarea-input name="short_description" value="" />
                        </div>
                        <div class="md:col-span-12">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Description</label>
                            <x-textarea-input name="description" value="" />
                        </div>
                        <div class="md:col-span-12">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Specifications <span class="text-ink-tertiary text-sm">(key:value pairs, one per line)</span></label>
                            <textarea name="specifications" class="w-full px-3 py-2 text-sm text-ink bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" rows="3" placeholder="e.g. Material: Cotton&#10;Color: Red&#10;Warranty: 1 Year"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
                <div class="bg-surface-muted px-4 py-2.5 border-b border-border">
                    <h5 class="font-bold mb-0 text-sm"><i data-lucide="truck" class="me-2 text-brand" style="width:16px;height:16px;"></i>Shipping & Manufacturer</h5>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                        <div class="md:col-span-3">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Weight (kg)</label>
                            <input type="number" step="0.01" name="weight" class="w-full px-3 py-2 text-sm text-ink bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" placeholder="0.00">
                        </div>
                        <div class="md:col-span-3">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Height (cm)</label>
                            <input type="number" step="0.01" name="height" class="w-full px-3 py-2 text-sm text-ink bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" placeholder="0.00">
                        </div>
                        <div class="md:col-span-3">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Width (cm)</label>
                            <input type="number" step="0.01" name="width" class="w-full px-3 py-2 text-sm text-ink bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" placeholder="0.00">
                        </div>
                        <div class="md:col-span-3">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Length (cm)</label>
                            <input type="number" step="0.01" name="length" class="w-full px-3 py-2 text-sm text-ink bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" placeholder="0.00">
                        </div>
                        <div class="md:col-span-4">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Country of Origin</label>
                            <input type="text" name="country_of_origin" class="w-full px-3 py-2 text-sm text-ink bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" placeholder="e.g. Bangladesh">
                        </div>
                        <div class="md:col-span-4">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Manufacturer Name</label>
                            <input type="text" name="manufacturer_name" class="w-full px-3 py-2 text-sm text-ink bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" placeholder="Manufacturer name">
                        </div>
                        <div class="md:col-span-4">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Manufacturer Details</label>
                            <input type="text" name="manufacturer_details" class="w-full px-3 py-2 text-sm text-ink bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" placeholder="Address / contact">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="sticky-sidebar">
                <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
                    <div class="bg-surface-muted px-4 py-2.5 border-b border-border">
                        <h5 class="font-bold mb-0 text-sm"><i data-lucide="camera" class="me-2 text-brand" style="width:16px;height:16px;"></i>Thumbnail</h5>
                    </div>
                    <div class="p-5 text-center">
                        <x-image-input name="thumbnail" />
                        <span class="text-ink-tertiary text-sm mt-2 block">JPG/PNG/WEBP, max 10MB</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(function() {
        $(".brand-select").select2({ tags: true, theme: "bootstrap-5" });

        $('#categorySelect').change(function() {
            let catId = $(this).val();
            let hasOptions = false;
            $('#subcategorySelect').val('').trigger('change');
            $('#subcategorySelect option').each(function() {
                if (catId == $(this).data('category')) { $(this).show(); hasOptions = true; }
                else { $(this).hide(); }
            });
            $('#subcategorySelect').attr('disabled', !hasOptions);
        });

        $('#submitBtn').click(function(e) {
            e.preventDefault();
            let form = $('#productForm')[0];
            let formData = new FormData(form);
            const variants = collectVariantsData();
            formData.append('variants', JSON.stringify(variants));

            $.ajax({
                url: "{{ route('seller.products.store') }}",
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() { $('#submitBtn').attr('disabled', true).text('Saving...'); },
                success: function(response) {
                    showSuccessToast(response.message);
                    setTimeout(() => window.location.href = "{{ route('seller.products.index') }}", 1500);
                },
                error: function(xhr) {
                    $('#submitBtn').attr('disabled', false).text('Save Product');
                    if (xhr.status === 422) {
                        let msgs = Object.values(xhr.responseJSON.errors).map(i => i[0]).join('<br>');
                        showErrorToast(msgs);
                    } else { showErrorToast('Something went wrong.'); }
                }
            });
        });
    });
</script>
@endpush