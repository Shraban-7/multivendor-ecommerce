@extends('seller.layouts.app')

@section('title', 'Seller Profile')

@section('content')
    <div class="d-flex justify-content-between align-items-end mb-3">
        <h4 class="mb-0">Edit Profile</h4>
    </div>
    <div class="row">
        <div class="col-8">
            <div class="card card-body">
                <form id="profileForm" action="{{ route('seller.profile', $seller->username) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="fullname" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="fullname" name="fullname"
                                value="{{ old('fullname', $seller->fullname) }}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                value="{{ old('email', $seller->email) }}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="phone" name="phone"
                                value="{{ old('phone', $seller->phone) }}" required>
                        </div>
                    </div>
                    <div class="row mb-4">
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
                            <label for="business_email" class="form-label">Business Address</label>
                            <textarea name="business_address" id="business_address" class="form-control"  cols="30" rows="2">{{ old('business_address', $seller->business_address) }}</textarea>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Profile Picture</label>
                            <x-image-input name="image" :image="storage_url($seller->image)"/>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Business Logo</label>
                            <x-image-input name="business_logo" :image="storage_url($seller->business_logo)"/>
                        </div>
                    </div>

                    <!-- Submit Button -->
                   <button type="submit" id="submitBtn" class="btn btn-theme">Update</button>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')

    @endpush
@endsection
