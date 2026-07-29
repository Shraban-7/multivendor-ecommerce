@extends('admin.layouts.app')
@section('title', 'Edit Static Pages')

@section('content')

    <div class="grid grid-cols-1">
        <div class="lg:col-span-10 mx-auto">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden shadow mb-4">
                <div class="p-5">
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
                        <label for="title" class="block text-xs font-medium text-ink-secondary mb-1">Page Title</label>
                        <input type="text" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors @error('title') is-invalid @enderror" id="title"
                            name="title" value="{{ old('title', $page->title ?? '') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="content" class="block text-xs font-medium text-ink-secondary mb-1">Page Content</label>

                        <div id="content-editor" style="height: 300px;">
                            {!! old('content', $page->content ?? '') !!}
                        </div>

                        <input type="hidden" name="content" id="content">

                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="flex items-center gap-2 form-switch mb-4">
                        <input class="h-4 w-4 rounded border-border text-brand focus:ring-brand" type="checkbox" id="is_active" name="is_active" value="1"
                            {{ old('is_active', $page->is_active ?? 1) ? 'checked' : '' }}>
                        <label class="text-sm text-ink" for="is_active">
                            Publish Page (Active)
                        </label>
                        <small class="form-text text-ink-tertiary block">If unchecked, the page will not be accessible to the
                            public.</small>
                    </div>

                    <button type="submit" class="btn btn-success mt-3">
                        <i data-lucide="save" class="me-1"></i> {{ isset($page) ? 'Update Page' : 'Save Page' }}
                    </button>
                    <a href="{{ route('admin.staticPages.index') }}" class="btn btn-light mt-3 ms-2">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
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
