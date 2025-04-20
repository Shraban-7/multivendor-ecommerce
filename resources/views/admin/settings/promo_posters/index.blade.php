@extends('admin.layouts.app')
@section('title', 'Promo Posters')
@section('content')
    <div class="mb-3 d-flex justify-content-between align-items-end">
        <h4 class="mb-0">Home Promo Poster</h4>
        @if (count($posters) < 4)
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                <i data-feather="plus" class="icon-xs"></i> Add Promo Poster
            </button>
        @endif
    </div>
    <div class="row">
        @foreach ($posters as $poster)
            <div class="col-md-6 col-lg-4 mb-3">
                <div class="card shadow-sm h-100">
                    <img src="{{ storage_url($poster->image) }}" class="card-img-top border-bottom" alt="Banner Image">
                    <div class="card-body">
                        <h3 class="card-title mb-2">{{ $poster->title }}</h3>
                        <p class="card-text text-muted mb-1">{{ $poster->subtitle }}</p>
                        <p class="mb-1"><strong>Button Text:</strong> {{ $poster->button_text }}</p>
                        <p class="mb-1"><strong>Link:</strong> <a href="{{ $poster->button_link }}"
                                target="_blank">{{ $poster->button_link }}</a></p>
                        <p class="mb-2"><strong>Position:</strong> {{ $poster->position }}</p>
                        <button class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#editModal-{{ $poster->id }}">
                            <i data-feather="edit" class="icon-xs"></i> Edit
                        </button>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="editModal-{{ $poster->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5">Edit Promo Poster</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('admin.settings.posters.update', $poster->id) }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">
                                <div class="row">
                                    <div class="mb-3 col-md-12">
                                        <label class="form-label">Position</label>
                                        <select name="position" class="form-select w-100" required>
                                            <option value="" disabled>--Choose--</option>
                                            @for ($i = 1; $i <= 2; $i++)
                                                <option value="{{ $i }}"
                                                    @if ($poster->position == $i) selected
                                                @elseif (in_array($i, $usedPositions) && $poster->position != $i) disabled @endif>
                                                    {{ $i }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="mb-3 col-12">
                                        <label class="form-label">Title</label>
                                        <input name="title" type="text" value="{{ $poster->title }}"
                                            class="form-control">
                                    </div>
                                    <div class="mb-3 col-12">
                                        <label class="form-label">Link</label>
                                        <input name="link" type="text" value="{{ $poster->link }}"
                                            class="form-control">
                                    </div>
                                    <div class="mb-3 col-md-12">
                                        <label class="form-label">Upload New Image (optional)</label>
                                        <input name="image" type="file" class="form-control">
                                        @if ($poster->image)
                                            <div class="mt-2">
                                                <label class="form-label d-block">Current Image:</label>
                                                <img src="{{ storage_url($poster->image) }}" alt="Current Banner Image"
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
                    <h1 class="modal-title fs-5">Add Promo Poster</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.settings.posters.store') }}" method="post" enctype="multipart/form-data">
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
                                <label class="form-label">Link</label>
                                <input name="link" type="text" value="" class="form-control">
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
