@extends('admin.layouts.app')
@section('title', 'Add Category')
@section('content')
    <div class="flex justify-between items-start mb-4">
        <div>
            <h1 class="text-xl font-semibold text-ink">Add Category</h1>
            <p class="text-sm text-ink-secondary mt-1">Create a new product category</p>
        </div>
    </div>

    <div class="max-w-2xl">
        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden p-5">
            <form id="form" action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                @CSRF
                <div class="space-y-5">
                    <div>
                        <label class="block text-xs font-medium text-ink-secondary mb-1">Name</label>
                        <input name="name" type="text" value=""
                            class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors"
                            required>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-ink-secondary mb-1">Icon (FontAwesome)</label>
                        <div class="flex">
                            <span
                                class="inline-flex items-center px-3 py-2 text-sm text-ink-tertiary bg-surface-muted border border-border rounded-l-xs">
                                <i id="iconPreview" data-lucide="circle-help"></i>
                            </span>
                            <input type="text" name="icon" id="iconInput"
                                class="flex-1 px-3 py-2 text-sm text-ink bg-white border border-border rounded-r-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors"
                                placeholder="e.g. fa-brands fa-facebook" required>
                            <a href="https://fontawesome.com/icons" target="_blank" class="btn btn-light ms-2">
                                <i data-lucide="square-arrow-out-up-right"></i>
                            </a>
                        </div>
                        <small class="text-ink-tertiary mt-1 block">
                            Example: <b>fas fa-facebook</b>, <b>fas fa-youtube</b>
                        </small>
                    </div>

                    <div>
                        <div class="flex items-center gap-6">
                            <div class="flex items-center gap-2">
                                <input type="hidden" name="is_nav" value="0">
                                <input class="h-4 w-4 rounded border-border text-brand focus:ring-brand" type="checkbox"
                                    name="is_nav" value="1" id="is_nav">
                                <label class="text-sm text-ink" for="is_nav">Show Top Navbar</label>
                            </div>
                            <div class="flex items-center gap-2">
                                <input type="hidden" name="is_special" value="0">
                                <input class="h-4 w-4 rounded border-border text-brand focus:ring-brand" type="checkbox"
                                    name="is_special" value="1" id="is_special">
                                <label class="text-sm text-ink" for="is_special">Special</label>
                            </div>
                            <div class="flex items-center gap-2">
                                <input type="hidden" name="is_slider" value="0">
                                <input class="h-4 w-4 rounded border-border text-brand focus:ring-brand" type="checkbox"
                                    name="is_slider" value="1" id="is_slider">
                                <label class="text-sm text-ink" for="is_slider">Slider Category</label>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-ink-secondary mb-1">
                            App Icon

                            <small class="text-ink-tertiary">(Supported formats: PNG, SVG)</small>
                        </label>
                        <x-image-input name="app_icon" />
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-border">
                    <button type="submit" id="submitBtn" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            document.getElementById('iconInput').addEventListener('input', function() {
                const value = this.value.trim().toLowerCase();
                const preview = document.getElementById('iconPreview');
                if (value) {
                    preview.className = value;
                } else {
                    preview.className = 'fa fa-question-circle';
                }
            });
        </script>
    @endpush
@endsection
