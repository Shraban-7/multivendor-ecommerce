@extends('admin.layouts.app')
@section('title', 'Home Mid Banners')
@section('content')
    <div class="mb-3 d-flex justify-content-between align-items-end">
        <h4 class="mb-0">Home Mid Banners</h4>
        @if (hasPermission('admin.settings.banners.store'))
            @if (count($banners) < 10)
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                    <i data-feather="plus" class="icon-xs"></i> Add Home Mid Banner
                </button>
            @endif
        @endif
    </div>
    <div class="row">
        @foreach ($banners as $banner)
            <div class="col-md-6 col-lg-4 mb-3">
                <div class="card shadow-sm h-100">
                    <img src="{{ storage_url($banner->image) }}" class="card-img-top border-bottom" alt="Banner Image">
                    <div class="card-body">
                        <h3 class="card-title mb-2">{{ $banner->title }}</h3>
                        <p class="card-text text-muted mb-1">{{ $banner->subtitle }}</p>
                        <p class="mb-1"><strong>Button Text:</strong> {{ $banner->button_text }}</p>
                        <p class="mb-1"><strong>Link:</strong> <a href="{{ $banner->button_link }}"
                                target="_blank">{{ $banner->button_link }}</a></p>
                        <p class="mb-2"><strong>Position:</strong> {{ $banner->position }}</p>

                        @if (hasPermission('admin.settings.banners.update'))
                            <button class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#editModal-{{ $banner->id }}">
                                <i data-feather="edit" class="icon-xs"></i> Edit
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <div class="modal fade" id="editModal-{{ $banner->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5">Edit Home Mid Banner</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('admin.settings.banners.update', $banner->id) }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">
                                <div class="row">
                                    <div class="mb-3 col-md-12">
                                        <label class="form-label">Position</label>
                                        <select name="position" class="form-select w-100" required>
                                            <option value="" disabled>--Choose--</option>
                                            @for ($i = 1; $i <= 5; $i++)
                                                <option value="{{ $i }}"
                                                    @if ($banner->position == $i) selected
                                                @elseif (in_array($i, $usedPositions) && $banner->position != $i) disabled @endif>
                                                    {{ $i }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="mb-3 col-12">
                                        <label class="form-label">Title</label>
                                        <input name="title" type="text" value="{{ $banner->title }}"
                                            class="form-control">
                                    </div>
                                    <div class="mb-3 col-12">
                                        <label class="form-label">Subtitle</label>
                                        <input name="subtitle" type="text" value="{{ $banner->subtitle }}"
                                            class="form-control">
                                    </div>
                                    <div class="mb-3 col-md-12">
                                        <label class="form-label">Description</label>
                                        <textarea name="description" class="form-control">{{ $banner->description }}</textarea>
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label">Button Text</label>
                                        <input name="button_text" type="text" value="{{ $banner->button_text }}"
                                            class="form-control">
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label">Button Link</label>
                                        <input name="button_link" type="text" value="{{ $banner->button_link }}"
                                            class="form-control">
                                    </div>
                                    <div class="mb-3 col-md-12">
                                        <label class="form-label">Upload New Image (optional)</label>
                                        <input name="image" type="file" class="form-control">
                                        @if ($banner->image)
                                            <div class="mt-2">
                                                <label class="form-label d-block">Current Image:</label>
                                                <img src="{{ storage_url($banner->image) }}" alt="Current Banner Image"
                                                    class="img-fluid rounded shadow" style="max-height: 100px;">
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Add Home Mid Banner</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.settings.banners.store') }}" method="post" enctype="multipart/form-data">
                    @CSRF
                    <div class="modal-body">
                        <div class="row">
                            <div class="mb-3 col-md-12">
                                <label class="form-label">Position</label>
                                <select name="position" class="form-select w-100" id="positionSelect" required>
                                    <option value="" selected disabled>--Choose--</option>
                                    @foreach ($availablePositions as $position)
                                        <option value="{{ $position }}">{{ $position }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3 col-12">
                                <label class="form-label">Title</label>
                                <input name="title" type="text" value="" class="form-control">
                            </div>
                            <div class="mb-3 col-12">
                                <label class="form-label">Subtitle</label>
                                <input name="subtitle" type="text" value="" class="form-control">
                            </div>
                            <div class="mb-3 col-md-12">
                                <label class="form-label">description</label>
                                <textarea name="description" id="" class="form-control"></textarea>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Button Text</label>
                                <input name="button_text" type="text" value="" class="form-control">
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Button Link</label>
                                <input name="button_link" type="text" value="" class="form-control">
                            </div>
                            <div class="mb-3 col-md-12">
                                <label class="form-label">Upload</label>
                                <input name="image" type="file" value="" class="form-control">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-theme">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @push('scripts')
    @endpush

@endsection
