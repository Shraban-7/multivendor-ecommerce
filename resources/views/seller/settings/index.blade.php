@extends('seller.layouts.app')

@section('title', 'Business Settings')

@section('content')
    <div class="d-flex justify-content-between align-items-end mb-3">
        <h4 class="mb-0">Settings</h4>
    </div>
    <div class="row">
        <div class="col-8">
            <div class="card card-body">
                <form id="businessSettingsForm" action="{{ route('seller.settings.index') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="business_name" class="form-label">Business Name</label>
                            <input type="text" class="form-control" id="business_name" name="business_name"
                                value="{{ old('business_name', $seller->business_name) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="business_email" class="form-label">Business Email</label>
                            <input type="email" class="form-control" id="business_email" name="business_email"
                                value="{{ old('business_email', $seller->business_email) }}" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="business_address" class="form-label">Business Address</label>
                            <textarea name="business_address" id="business_address" class="form-control" rows="2">{{ old('business_address', $seller->business_address) }}</textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="trade_license_no" class="form-label">Trade License Number</label>
                            <input type="text" class="form-control" id="trade_license_no"
                                value="{{ old('trade_license_no', $seller->trade_license_no ?? 'Not provided') }}" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="trade_license_no" class="form-label">Trade License Number</label>
                            <input type="text" class="form-control" id="shipping_cost" name="shipping_cost"
                                value="{{ old('shipping_cost', $seller->shipping_cost ?? 'Not provided') }}" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="country" class="form-label">Country</label>
                            <select name="country" id="country" class="form-select" required>
                                <option value="">Select Country</option>
                                <option value="Bangladesh"
                                    {{ old('country', $seller->country) == 'Bangladesh' ? 'selected' : '' }}>Bangladesh
                                </option>
                                <option value="India" {{ old('country', $seller->country) == 'India' ? 'selected' : '' }}>
                                    India</option>
                                <option value="USA" {{ old('country', $seller->country) == 'USA' ? 'selected' : '' }}>USA
                                </option>
                                <!-- Add more countries as needed -->
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="state" class="form-label">State</label>
                            <select name="state" id="state" class="form-select" required>
                                <option value="">Select State</option>
                                <option value="Dhaka" {{ old('state', $seller->state) == 'Dhaka' ? 'selected' : '' }}>Dhaka
                                </option>
                                <option value="Chattogram"
                                    {{ old('state', $seller->state) == 'Chattogram' ? 'selected' : '' }}>Chattogram
                                </option>
                                <option value="Delhi" {{ old('state', $seller->state) == 'Delhi' ? 'selected' : '' }}>
                                    Delhi</option>
                                <option value="California"
                                    {{ old('state', $seller->state) == 'California' ? 'selected' : '' }}>California
                                </option>
                                <!-- Add more states or conditionally show based on country -->
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Business Logo</label>
                            <x-image-input name="business_logo" :image="storage_url($seller->business_logo)" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Shop Image</label>
                            <x-image-input name="shop_image" :image="storage_url($seller->shop_image)" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Trade License Image</label><br>
                            <img src="{{ storage_url($seller->trade_license_image) }}" alt="Trade License"
                                class="img-fluid rounded border" style="max-height: 200px;">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">NID Front Image</label><br>
                            <img src="{{ storage_url($seller->nid_front_image) }}" alt="NID Front"
                                class="img-fluid rounded border" style="max-height: 200px;">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">NID Back Image</label><br>
                            <img src="{{ storage_url($seller->nid_back_image) }}" alt="NID Back"
                                class="img-fluid rounded border" style="max-height: 200px;">
                        </div>

                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
@endsection
