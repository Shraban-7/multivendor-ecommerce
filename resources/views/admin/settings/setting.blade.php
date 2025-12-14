@extends('admin.layouts.app')
@section('title', 'Edit Settings')
@section('content')

<h4 class="mb-3">Edit Settings</h4>

    <div class="row">
        <div class="col-6">
            <div class="card card-body">
                <form id="form" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="form-label">App Name</label>
                            <input type="text" name="app_name" class="form-control mt-2" value="{{ old('app_name',$setting->app_name) }}">
                        </div>
                        <div class="mb-3 col-12">
                            @if (!empty($setting->favicon))
                                <div class="mb-2">
                                    <img src="{{ asset($setting->favicon) }}" alt="Favicon" width="40" height="40">
                                </div>
                            @endif

                            <label class="form-label">Favicon</label>
                            <input type="file" name="favicon" class="form-control mt-2" accept="image/*">
                        </div>
                        <div class="mb-3 col-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control mt-2" value="{{ old('email',$setting->email) }}">
                        </div>
                        <div class="mb-3 col-6">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control mt-2" value="{{ old('phone',$setting->phone) }}">
                        </div>
                        <div class="mb-3 col-12">
                            <label class="form-label">Address</label>
                            <textarea name="address" class="form-control" id="">{{ old('address',$setting->address) }}</textarea>
                        </div>
                        <div class="mb-3 col-12">
                            <label class="form-label">Footer Text</label>
                            <textarea name="footer_text" class="form-control" id="">{{ old('footer_text',$setting->footer_text) }}</textarea>
                        </div>
                        <div class="mb-3 col-12">
                            <label class="form-label">Logo</label>
                            <x-image-input name="logo" :image="storage_url($setting->logo)" />
                        </div>
                        <div class="mb-3 col-12">
                            <label class="form-label">Logo White</label>
                            <x-image-input name="logo_white" :image="storage_url($setting->logo_white)" />
                        </div>
                    </div>
                    <button type="submit" id="updateBtn" class="btn btn-theme">Update</button>
                </form>
            </div>
        </div>
    </div>
@endsection
