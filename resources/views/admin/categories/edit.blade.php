@extends('admin.layouts.app')
@section('title', 'Edit Category')
@section('content')
    <div class="mb-3 flex justify-between items-end">
        <h4 class="mb-0">Edit Category</h4>
    </div>

    <div class="grid grid-cols-1">
        <div class="col-span-2">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden p-5">
                <form id="form" action="{{ route('admin.categories.update', $category->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1">
                        <div class="mb-3 md:col-span-1">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Name</label>
                            <input name="name" type="text" value="{{ old('name', $category->name) }}"
                                class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" required>
                        </div>
                        <div class="mb-3 md:col-span-1">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Icon (FontAwesome)</label>

                            <div class="flex">
                                <span class="inline-flex items-center px-3 py-2 text-sm text-ink-tertiary bg-surface-muted border border-border">
                                    <i id="iconPreview"></i>
                                </span>

                                <input type="text" name="icon" id="iconInput" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors"
                                    value="{{ $category->icon ?? '' }}" required>

                                <!-- FontAwesome icon list link -->
                                <a href="https://fontawesome.com/icons" target="_blank" class="btn btn-light">
                                    <i data-lucide="square-arrow-out-up-right"></i>
                                </a>
                            </div>

                            <small class="text-ink-tertiary">
                                Example: <b>fas fa-facebook</b>, <b>fas fa-youtube</b>
                            </small>
                        </div>

                        {{-- <div class="mb-3 md:col-span-1">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Cover Title</label>
                            <input name="cover_title" type="text" value="{{ old('cover_title', $category->cover_title) }}" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" required>
                        </div>
                        <div class="mb-3 md:col-span-full">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Cover Description</label>
                            <x-textarea-input name="cover_description" :value="old('description', $category->cover_description)" />
                        </div>
                        <div class="mb-3 md:col-span-1">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Cover Background Color</label>
                            <input name="cover_bg_color" type="color" value="{{ old('cover_bg_color', $category->cover_bg_color) }}" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" required>
                        </div>
                        <div class="mb-3 md:col-span-1">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Cover Text Color</label>
                            <input name="cover_text_color" type="color" value="{{ old('cover_text_color', $category->cover_text_color) }}" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" required>
                        </div>
                        <div class="mb-3 md:col-span-1">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Cover Button Color</label>
                            <input name="cover_button_color" type="color" value="{{ old('cover_button_color', $category->cover_button_color) }}" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" required>
                        </div> --}}
                        <div class="mb-3 md:col-span-full">
                            <div class="gap-3 flex items-center">
                                <div class="flex items-center gap-2 form-switch">
                                    <input type="hidden" name="is_trending" value="0">
                                    <input class="h-4 w-4 rounded border-border text-brand focus:ring-brand" type="checkbox" name="is_nav" value="1"
                                        role="switch" id="is_nav"
                                        {{ old('is_nav', $category->is_nav) ? 'checked' : '' }}>
                                    <label class="text-sm text-ink" for="is_nav">Show Top Navbar</label>
                                </div>
                                <div class="flex items-center gap-2 form-switch">
                                    <input type="hidden" name="best_selling" value="0">
                                    <input class="h-4 w-4 rounded border-border text-brand focus:ring-brand" type="checkbox" name="is_special" value="1"
                                        role="switch" id="is_special"
                                        {{ old('is_special', $category->is_special) ? 'checked' : '' }}>
                                    <label class="text-sm text-ink" for="is_special">Special</label>
                                </div>
                                <div class="flex items-center gap-2 form-switch">
                                    <input type="hidden" name="is_featured" value="0">
                                    <input class="h-4 w-4 rounded border-border text-brand focus:ring-brand" type="checkbox" name="is_slider" value="1"
                                        role="switch" id="is_slider"
                                        {{ old('is_slider', $category->is_slider) ? 'checked' : '' }}>
                                    <label class="text-sm text-ink" for="is_slider">Slider Category</label>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="mb-3 col-span-1">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Image</label>
                            <x-image-input name="image" :image="storage_url($category->image)" />
                        </div> --}}

                        <div class="mb-3 col-span-1">
                            <label class="block text-xs font-medium text-ink-secondary mb-1 font-semibold mb-1">
                                App Icon
                                <span class="text-feedback-danger">*</span>
                                <small class="text-ink-tertiary">(Supported formats: PNG, SVG)</small>
                            </label>

                            <x-image-input name="app_icon" :image="storage_url($category->app_icon)" />
                        </div>
                        {{-- <div class="mb-3 col-span-1">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Cover Photo</label>
                            <x-image-input name="cover_image" :image="storage_url($category->cover_image)" />
                        </div> --}}
                    </div>
                    <button type="submit" id="submitBtn" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function updateIconPreview(value) {
                const preview = document.getElementById('iconPreview');

                if (value.trim()) {
                    preview.className = `${value.trim().toLowerCase()}`;
                } else {
                    preview.className = 'fa fa-question-circle';
                }
            }

            document.getElementById('iconInput').addEventListener('input', function() {
                updateIconPreview(this.value);
            });

            document.addEventListener('DOMContentLoaded', function() {
                const initialValue = document.getElementById('iconInput').value;
                updateIconPreview(initialValue);
            });
        </script>
    @endpush
@endsection
