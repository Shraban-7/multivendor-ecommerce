@extends('admin.layouts.app')
@section('title', 'Edit Static Pages')

@section('content')

<div class="row">
    <div class="col-xl-8 col-lg-10 mx-auto">
        <div class="card shadow mb-4">

            <div class="card-body">

                <h4>{{ isset($page) ? 'Edit Static Page' : 'Add New Page' }}</h4>
                <hr>

                {{-- Form setup for Create or Update --}}
                @if(isset($page))
                <form action="{{ route('admin.staticPages.update', $page) }}" method="POST">
                    @method('PUT')
                    @else
                    <form action="{{ route('admin.staticPages.store') }}" method="POST">
                        @endif
                        @csrf

                        {{-- Title Field --}}
                        <div class="mb-3">
                            <label for="title" class="form-label">Page Title</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $page->title ?? '') }}" required>
                            @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Content Field (Use a Rich Text Editor like TinyMCE/CKEditor for professionalism) --}}
                        <div class="mb-3">
                            <label for="content" class="form-label">Page Content</label>
                            {{-- Assuming you'll initialize a JS editor on this textarea --}}
                            <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="10">{{ old('content', $page->content ?? '') }}</textarea>
                            @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Status Checkbox --}}
                        <div class="form-check form-switch mb-4">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $page->is_active ?? 1) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Publish Page (Active)
                            </label>
                            <small class="form-text text-muted d-block">If unchecked, the page will not be accessible to the public.</small>
                        </div>

                        <button type="submit" class="btn btn-success mt-3">
                            <i class="fas fa-save me-1"></i> {{ isset($page) ? 'Update Page' : 'Save Page' }}
                        </button>
                        <a href="{{ route('admin.staticPages.index') }}" class="btn btn-secondary mt-3 ms-2">Cancel</a>
                    </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')

<script>

</script>
@endpush
@endsection