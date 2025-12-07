@extends('admin.layouts.app')
@section('title', 'Edit Static Pages')

@section('content')

    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <h4>{{ isset($page) ? 'Edit ' . $page->title : 'Add New Page' }}</h4>
                    <hr>

                    @if (isset($page))
                        <form action="{{ route('admin.staticPages.update', $page->slug) }}" method="POST">
                            @method('PUT')
                        @else
                            <form action="{{ route('admin.staticPages.store') }}" method="POST">
                    @endif
                    @csrf

                    <div class="mb-3">
                        <label for="title" class="form-label">Page Title</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title"
                            name="title" value="{{ old('title', $page->title ?? '') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="content" class="form-label">Page Content</label>

                        <div id="content-editor" style="height: 300px;">
                            {!! old('content', $page->content ?? '') !!}
                        </div>

                        <input type="hidden" name="content" id="content">

                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-check form-switch mb-4">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                            {{ old('is_active', $page->is_active ?? 1) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            Publish Page (Active)
                        </label>
                        <small class="form-text text-muted d-block">If unchecked, the page will not be accessible to the
                            public.</small>
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
        <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var quill = new Quill('#content-editor', {
                    theme: 'snow'
                });

                document.getElementById('content').value = quill.root.innerHTML;
                
                quill.on('text-change', function() {
                    document.getElementById('content').value = quill.root.innerHTML;
                });
            });
        </script>
    @endpush
@endsection
