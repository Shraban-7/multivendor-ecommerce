@extends('admin.layouts.app')
@section('title', 'Edit Settings')
@section('content')

    <div class="mb-3 d-flex justify-content-between align-items-end">
        <h4 class="mb-0">Edit Settings</h4>
    </div>

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
