@extends('admin.layouts.app')
@section('title', 'Images')
@section('content')

<h4 class="mb-3">Images</h4>
<div class="row">
    <div class="col-6">
        <div class="card card-body">
            <form id="form" action="{{ route('admin.images.store') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <div class="row">
                    <div class="mb-3 col-12">
                        <label class="form-label">Upload Your Watermark</label>
                        <x-image-input name="watermark" />
                    </div>
                    <div class="mb-3 col-12">
                        <label class="form-label">Upload Your Images</label>
                        <input class="form-control" name="images[]" type="file" multiple>
                    </div>
                </div>
                <button type="submit" id="updateBtn" class="btn btn-theme">Save</button>
            </form>
        </div>
    </div>
</div>

@endsection