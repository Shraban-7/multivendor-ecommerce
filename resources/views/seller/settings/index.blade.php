@extends('seller.layouts.app')
@section('title', 'Shop Settings')

@push('styles')
<style>
    .settings-tabs { border-bottom: 2px solid #e9ecef; }
    .settings-tabs .nav-link {
        color: #6c757d; font-weight: 500; border: none; padding: 0.75rem 1.25rem;
        border-bottom: 2px solid transparent; margin-bottom: -2px; transition: all 0.15s;
    }
    .settings-tabs .nav-link:hover { color: var(--bs-primary); border-bottom-color: #dee2e6; }
    .settings-tabs .nav-link.active { color: var(--bs-primary); border-bottom-color: var(--bs-primary); background: none; }
    .settings-tabs .nav-link i { margin-right: 6px; }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold mb-0 text-dark">Shop Settings</h4>
</div>

<div class="card border-0 shadow-sm" style="border-radius: 12px;">
    <div class="card-header bg-white px-0 pt-0 border-0">
        <ul class="nav settings-tabs px-3" id="settingsTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab">
                    <i data-feather="info"></i> Shop Info
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="address-tab" data-bs-toggle="tab" data-bs-target="#address" type="button" role="tab">
                    <i data-feather="map-pin"></i> Address & Shipping
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="identity-tab" data-bs-toggle="tab" data-bs-target="#identity" type="button" role="tab">
                    <i data-feather="file-text"></i> Identity & Documents
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="media-tab" data-bs-toggle="tab" data-bs-target="#media" type="button" role="tab">
                    <i data-feather="image"></i> Media
                </button>
            </li>
        </ul>
    </div>

    <div class="card-body p-4">
        <form id="businessSettingsForm" action="{{ route('seller.settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="tab-content" id="settingsTabContent">

                {{-- TAB 1: Shop Info --}}
                <div class="tab-pane fade show active" id="info" role="tabpanel">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="business_name" class="form-label">Business Name</label>
                            <input type="text" class="form-control" id="business_name" name="business_name"
                                value="{{ old('business_name', $seller->business_name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="business_email" class="form-label">Business Email</label>
                            <input type="email" class="form-control" id="business_email" name="business_email"
                                value="{{ old('business_email', $seller->business_email) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="shop_type" class="form-label">Shop Type</label>
                            <select name="shop_type" id="shop_type" class="form-select">
                                <option value="individual" {{ old('shop_type', $seller->shop_type) == 'individual' ? 'selected' : '' }}>Individual</option>
                                <option value="business" {{ old('shop_type', $seller->shop_type) == 'business' ? 'selected' : '' }}>Business</option>
                                <option value="company" {{ old('shop_type', $seller->shop_type) == 'company' ? 'selected' : '' }}>Company</option>
                            </select>
                        </div>
                    </div>
                    <div class="row g-3 mt-2">
                        <div class="col-12">
                            <label for="business_description" class="form-label">Shop Description</label>
                            <textarea name="business_description" id="business_description" class="form-control" rows="4" maxlength="5000">{{ old('business_description', $seller->business_description) }}</textarea>
                            <small class="text-muted">Tell customers about your shop (max 5000 characters).</small>
                        </div>
                    </div>
                    <div class="mt-4 text-end">
                        <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-1">
                            <i data-feather="save" style="width: 16px; height: 16px;"></i> Save Changes
                        </button>
                    </div>
                </div>

                {{-- TAB 2: Address & Shipping --}}
                <div class="tab-pane fade" id="address" role="tabpanel">
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="business_address" class="form-label">Business Address</label>
                            <textarea name="business_address" id="business_address" class="form-control" rows="2">{{ old('business_address', $seller->business_address) }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="divisionSelect" class="form-label">Division</label>
                            <select name="division_id" id="divisionSelect" class="form-select">
                                <option value="">Select Division</option>
                                @foreach ($divisions as $division)
                                    <option value="{{ $division->id }}" {{ $seller->division_id == $division->id ? 'selected' : '' }}>
                                        {{ $division->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="districtSelect" class="form-label">District</label>
                            <select name="district_id" id="districtSelect" class="form-select">
                                <option value="">Select District</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="shipping_cost" class="form-label">Shipping Cost</label>
                            <input type="number" class="form-control" id="shipping_cost" name="shipping_cost"
                                value="{{ old('shipping_cost', $seller->shipping_cost) }}" step="0.01">
                            <small class="text-muted">Default shipping cost for your products.</small>
                        </div>
                    </div>
                    <div class="mt-4 text-end">
                        <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-1">
                            <i data-feather="save" style="width: 16px; height: 16px;"></i> Save Changes
                        </button>
                    </div>
                </div>

                {{-- TAB 3: Identity & Documents --}}
                <div class="tab-pane fade" id="identity" role="tabpanel">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">NID Number</label>
                            <input type="text" name="nid_no" class="form-control" value="{{ old('nid_no', $seller->nid_no) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Trade License No</label>
                            <input type="text" name="trade_license_no" class="form-control" value="{{ old('trade_license_no', $seller->trade_license_no) }}">
                        </div>
                    </div>
                    <div class="row g-4 mt-2">
                        <div class="col-md-4">
                            <label class="form-label">NID Front</label>
                            <x-image-input name="nid_front_image" :image="auth('seller')->user()->nid_front_image
                                ? storage_url(auth('seller')->user()->nid_front_image)
                                : asset('assets/frontend/images/default.png')" />
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">NID Back</label>
                            <x-image-input name="nid_back_image" :image="auth('seller')->user()->nid_back_image
                                ? storage_url(auth('seller')->user()->nid_back_image)
                                : asset('assets/frontend/images/default.png')" />
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Trade License</label>
                            <x-image-input name="trade_license_image" :image="auth('seller')->user()->trade_license_image
                                ? storage_url(auth('seller')->user()->trade_license_image)
                                : asset('assets/frontend/images/default.png')" />
                        </div>
                    </div>
                    <div class="mt-4 text-end">
                        <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-1">
                            <i data-feather="save" style="width: 16px; height: 16px;"></i> Save Changes
                        </button>
                    </div>
                </div>

                {{-- TAB 4: Media --}}
                <div class="tab-pane fade" id="media" role="tabpanel">
                    <div class="row g-4">
                        <div class="col-md-4">
                            <label class="form-label">Business Logo</label>
                            <x-image-input name="business_logo" :image="storage_url($seller->business_logo)" />
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Cover Photo</label>
                            <x-image-input name="cover_image" :image="storage_url($seller->cover_image)" />
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Shop Image</label>
                            <x-image-input name="shop_image" :image="storage_url($seller->shop_image)" />
                        </div>
                    </div>
                    <div class="mt-4 text-end">
                        <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-1">
                            <i data-feather="save" style="width: 16px; height: 16px;"></i> Save Changes
                        </button>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let sellerDivision = "{{ $seller->division_id }}";
    let sellerDistrict = "{{ $seller->district_id }}";

    function loadDistricts(divisionId, selectedDistrict = null) {
        let districtSelect = $('#districtSelect');
        districtSelect.html('<option value="">Loading...</option>');

        if (divisionId) {
            $.get("{{ url('/get-districts') }}/" + divisionId, function(data) {
                districtSelect.html('<option value="">Select District</option>');
                $.each(data, function(key, district) {
                    let selected = selectedDistrict == key ? 'selected' : '';
                    districtSelect.append('<option value="' + key + '" ' + selected + '>' + district + '</option>');
                });
            });
        } else {
            districtSelect.html('<option value="">Select District</option>');
        }
    }

    if (sellerDivision) {
        loadDistricts(sellerDivision, sellerDistrict);
    }

    $('#divisionSelect').on('change', function() {
        loadDistricts($(this).val());
    });
</script>

<script>
    $(function() {
        const $form = $("#businessSettingsForm");
        const $submitBtn = $form.find("button[type='submit']");

        $form.on("submit", function(e) {
            e.preventDefault();
            $submitBtn.prop("disabled", true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');
            const fd = new FormData(this);

            $.ajax({
                url: $form.attr("action"),
                type: "POST",
                data: fd,
                processData: false,
                contentType: false,
                success: function(res) {
                    showSuccessToast(res.message || "Settings updated successfully!");
                    $submitBtn.prop("disabled", false).html('<i data-feather="save" style="width: 16px; height: 16px;"></i> Save Changes');
                    feather.replace();
                },
                error: function(xhr) {
                    $submitBtn.prop("disabled", false).html('<i data-feather="save" style="width: 16px; height: 16px;"></i> Save Changes');
                    feather.replace();
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors || {};
                        let msg = [];
                        $.each(errors, function(field, arr) {
                            const name = field.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
                            msg.push(`<strong>${name}:</strong> ${arr.join(', ')}`);
                        });
                        showErrorToast(msg.join('<br>'))
                    } else {
                        showErrorToast("Something went wrong!");
                    }
                }
            });
        });
    });
</script>
@endpush
