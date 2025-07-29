@extends('admin.layouts.app')
@section('title', 'Images')
@section('content')

<h4 class="mb-3">Images</h4>
<div class="row mb-3">
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

<div class="row">
    <div class="col-md-6">
        <div class="card card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Watermarked Images</h5>
                @if(count($watermarkedImages))
                <form action="{{ route('admin.images.delete-all') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete all images?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm">Delete All</button>
                </form>
                @endif
            </div>

            @if(count($watermarkedImages))
            <div class="row">
                @foreach ($watermarkedImages as $image)
                <div class="col-md-3 mb-4">
                    <div class="card">
                        <img src="{{ asset('storage/' . $image) }}"
                            class="card-img-top"
                            alt="Watermarked Image"
                            style="height: 200px; object-fit: cover; width: 100%;">

                        <div class="card-footer text-center">
                            <a href="{{ asset('storage/' . $image) }}" download class="btn btn-sm btn-light border w-100">
                                <i data-feather="download" class="nav-icon icon-xs me-2"></i> Download</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-muted">No watermarked images found.</div>
            @endif
        </div>
    </div>
</div>




@endsection