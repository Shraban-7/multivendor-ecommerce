@extends('seller.layouts.app')
@section('title', 'Add Product')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .section-card { border-radius: 12px; border: 0; box-shadow: 0 1px 3px rgba(0,0,0,.08); margin-bottom: 1.25rem; }
    .section-card .card-header { background: #fff; border-bottom: 1px solid #e9ecef; padding: .85rem 1.25rem; }
    .section-card .card-header h5 { font-size: .95rem; font-weight: 600; margin: 0; }
    .section-card .card-body { padding: 1.25rem; }
    .sticky-sidebar { position: sticky; top: 1.25rem; }
    .attr-selects .select2-container { width: 100% !important; }
</style>
@endpush

@section('content')
<form id="productForm" autocomplete="off" method="POST" action="{{ route('seller.products.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="flex justify-between items-center mb-3">
        <h4 class="font-bold mb-0 text-ink">Add Product</h4>
        <button type="button" id="submitBtn" class="btn btn-primary">
            <i data-lucide="save"></i> Save Product
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        {{-- LEFT COLUMN: Main form fields --}}
        <div class="lg:col-span-2">
            {{-- Basic Information --}}
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden section-card">
                <div class="card-header">
                    <h5><i data-lucide="circle-info" class="me-2 text-brand"></i>Basic Information</h5>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                        <div class="md:col-span-12">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Product Name</label>
                            <input name="name" type="text" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" required>
                        </div>
                        <div class="md:col-span-4">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Category</label>
                            <select id="categorySelect" name="category_id" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors" required>
                                <option disabled selected>-- Select Category --</option>
                                @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:col-span-4">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Subcategory</label>
                            <select id="subcategorySelect" name="subcategory_id" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors" disabled>
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
                            <select name="brand" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors brand-select">
                                <option disabled selected>-- Select Brand --</option>
                                @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:col-span-6">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Unit Value</label>
                            <input type="number" name="unit_value" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" placeholder="Enter value" required>
                        </div>
                        <div class="md:col-span-6">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Unit</label>
                            <select name="unit_id" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors" required>
                                <option disabled selected>--</option>
                                @foreach ($units as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->short_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:col-span-12">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Tags <span class="text-ink-tertiary text-sm">(comma separated)</span></label>
                            <input type="text" name="tags" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" placeholder="e.g. cotton, summer, casual">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Pricing & Inventory --}}
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden section-card">
                <div class="card-header">
                    <h5><i data-lucide="tags" class="me-2 text-brand"></i>Pricing & Inventory</h5>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                        <div class="md:col-span-4">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Cost Price</label>
                            <input name="cost_price" type="number" min="0" step="0.01" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" required>
                        </div>
                        <div class="md:col-span-4">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Price</label>
                            <input name="price" type="number" min="0" step="0.01" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" required>
                        </div>
                        <div class="md:col-span-4">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Compare Price <span class="text-ink-tertiary text-sm">(optional sale)</span></label>
                            <input name="compare_price" type="number" min="0" step="0.01" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" placeholder="Leave empty for no sale">
                        </div>
                        <div class="md:col-span-6">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Low Stock Quantity</label>
                            <input name="low_stock_quantity" type="number" min="0" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" required>
                        </div>
                        <div class="md:col-span-6">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Payment Type</label>
                            <select name="payment_type" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors" required>
                                @foreach (App\Enums\PaymentType::cases() as $paymentType)
                                <option value="{{ $paymentType->value }}">{{ $paymentType->title() }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Description & Specifications --}}
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden section-card">
                <div class="card-header">
                    <h5><i data-lucide="align-left" class="me-2 text-brand"></i>Description & Specifications</h5>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                        <div class="md:col-span-12">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Short Description</label>
                            <textarea name="short_description" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" rows="2" placeholder="Brief summary for search results"></textarea>
                        </div>
                        <div class="md:col-span-12">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Description</label>
                            <textarea name="description" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" rows="4" placeholder="Full product description"></textarea>
                        </div>
                        <div class="md:col-span-12">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Specifications <span class="text-ink-tertiary text-sm">(key:value pairs, one per line)</span></label>
                            <textarea name="specifications" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" rows="3" placeholder="e.g. Material: Cotton&#10;Color: Red&#10;Warranty: 1 Year"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Shipping & Manufacturer --}}
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden section-card">
                <div class="card-header">
                    <h5><i data-lucide="truck" class="me-2 text-brand"></i>Shipping & Manufacturer</h5>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                        <div class="md:col-span-3">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Weight (kg)</label>
                            <input type="number" step="0.01" name="weight" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" placeholder="0.00">
                        </div>
                        <div class="md:col-span-3">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Height (cm)</label>
                            <input type="number" step="0.01" name="height" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" placeholder="0.00">
                        </div>
                        <div class="md:col-span-3">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Width (cm)</label>
                            <input type="number" step="0.01" name="width" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" placeholder="0.00">
                        </div>
                        <div class="md:col-span-3">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Length (cm)</label>
                            <input type="number" step="0.01" name="length" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" placeholder="0.00">
                        </div>
                        <div class="md:col-span-4">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Country of Origin</label>
                            <input type="text" name="country_of_origin" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" placeholder="e.g. Bangladesh">
                        </div>
                        <div class="md:col-span-4">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Manufacturer Name</label>
                            <input type="text" name="manufacturer_name" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" placeholder="Manufacturer name">
                        </div>
                        <div class="md:col-span-4">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Manufacturer Details</label>
                            <input type="text" name="manufacturer_details" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" placeholder="Address / contact">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN: Thumbnail + Variants --}}
        <div class="lg:col-span-1">
            <div class="sticky-sidebar">
                {{-- Product Thumbnail --}}
                <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden section-card">
                    <div class="card-header">
                        <h5><i data-lucide="camera" class="me-2 text-brand"></i>Thumbnail</h5>
                    </div>
                    <div class="card-body text-center">
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
