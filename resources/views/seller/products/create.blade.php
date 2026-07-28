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
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0 text-dark">Add Product</h4>
        <button type="button" id="submitBtn" class="btn btn-primary d-inline-flex align-items-center gap-1">
            <i class="fas fa-save"></i> Save Product
        </button>
    </div>

    <div class="row">
        {{-- LEFT COLUMN: Main form fields --}}
        <div class="col-lg-8">
            {{-- Basic Information --}}
            <div class="card section-card">
                <div class="card-header">
                    <h5><i class="fas fa-info-circle me-2 text-primary"></i>Basic Information</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Product Name</label>
                            <input name="name" type="text" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Category</label>
                            <select id="categorySelect" name="category_id" class="form-select" required>
                                <option disabled selected>-- Select Category --</option>
                                @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Subcategory</label>
                            <select id="subcategorySelect" name="subcategory_id" class="form-select" disabled>
                                <option disabled selected>-- Select Subcategory --</option>
                                @foreach ($categories as $category)
                                @foreach ($category->subcategories as $subcategory)
                                <option value="{{ $subcategory->id }}" data-category="{{ $category->id }}">{{ $subcategory->name }}</option>
                                @endforeach
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Brand</label>
                            <select name="brand" class="form-select brand-select">
                                <option disabled selected>-- Select Brand --</option>
                                @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Unit Value</label>
                            <input type="number" name="unit_value" class="form-control" placeholder="Enter value" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Unit</label>
                            <select name="unit_id" class="form-select" required>
                                <option disabled selected>--</option>
                                @foreach ($units as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->short_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Tags <span class="text-muted small">(comma separated)</span></label>
                            <input type="text" name="tags" class="form-control" placeholder="e.g. cotton, summer, casual">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Pricing & Inventory --}}
            <div class="card section-card">
                <div class="card-header">
                    <h5><i class="fas fa-tags me-2 text-primary"></i>Pricing & Inventory</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Cost Price</label>
                            <input name="cost_price" type="number" min="0" step="0.01" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Price</label>
                            <input name="price" type="number" min="0" step="0.01" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Compare Price <span class="text-muted small">(optional sale)</span></label>
                            <input name="compare_price" type="number" min="0" step="0.01" class="form-control" placeholder="Leave empty for no sale">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Low Stock Quantity</label>
                            <input name="low_stock_quantity" type="number" min="0" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Payment Type</label>
                            <select name="payment_type" class="form-select" required>
                                @foreach (App\Enums\PaymentType::cases() as $paymentType)
                                <option value="{{ $paymentType->value }}">{{ $paymentType->title() }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Description & Specifications --}}
            <div class="card section-card">
                <div class="card-header">
                    <h5><i class="fas fa-align-left me-2 text-primary"></i>Description & Specifications</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Short Description</label>
                            <textarea name="short_description" class="form-control" rows="2" placeholder="Brief summary for search results"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="4" placeholder="Full product description"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Specifications <span class="text-muted small">(key:value pairs, one per line)</span></label>
                            <textarea name="specifications" class="form-control" rows="3" placeholder="e.g. Material: Cotton&#10;Color: Red&#10;Warranty: 1 Year"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Shipping & Manufacturer --}}
            <div class="card section-card">
                <div class="card-header">
                    <h5><i class="fas fa-truck me-2 text-primary"></i>Shipping & Manufacturer</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Weight (kg)</label>
                            <input type="number" step="0.01" name="weight" class="form-control" placeholder="0.00">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Height (cm)</label>
                            <input type="number" step="0.01" name="height" class="form-control" placeholder="0.00">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Width (cm)</label>
                            <input type="number" step="0.01" name="width" class="form-control" placeholder="0.00">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Length (cm)</label>
                            <input type="number" step="0.01" name="length" class="form-control" placeholder="0.00">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Country of Origin</label>
                            <input type="text" name="country_of_origin" class="form-control" placeholder="e.g. Bangladesh">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Manufacturer Name</label>
                            <input type="text" name="manufacturer_name" class="form-control" placeholder="Manufacturer name">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Manufacturer Details</label>
                            <input type="text" name="manufacturer_details" class="form-control" placeholder="Address / contact">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN: Thumbnail + Variants --}}
        <div class="col-lg-4">
            <div class="sticky-sidebar">
                {{-- Product Thumbnail --}}
                <div class="card section-card">
                    <div class="card-header">
                        <h5><i class="fas fa-camera me-2 text-primary"></i>Thumbnail</h5>
                    </div>
                    <div class="card-body text-center">
                        <x-image-input name="thumbnail" />
                        <span class="text-muted small mt-2 d-block">JPG/PNG/WEBP, max 10MB</span>
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
