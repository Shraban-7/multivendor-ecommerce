@extends('admin.layouts.app')
@section('title', 'Edit Category')
@section('content')
    <div class="mb-3 d-flex justify-content-between align-items-end">
        <h4 class="mb-0">Edit Category</h4>
    </div>

    <div class="row">
        <div class="col-8">
            <div class="card card-body">
                <form id="form" action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Name</label>
                            <input name="name" type="text" value="{{ old('name', $category->name) }}" class="form-control" required>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Cover Title</label>
                            <input name="cover_title" type="text" value="{{ old('cover_title', $category->cover_title) }}" class="form-control" required>
                        </div>
                        <div class="mb-3 col-md-12">
                            <label class="form-label">Cover Description</label>
                            <x-textarea-input name="cover_description" :value="old('description', $category->cover_description)" />
                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label">Cover Background Color</label>
                            <input name="cover_bg_color" type="color" value="{{ old('cover_bg_color', $category->cover_bg_color) }}" class="form-control form-control-color" required>
                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label">Cover Text Color</label>
                            <input name="cover_text_color" type="color" value="{{ old('cover_text_color', $category->cover_text_color) }}" class="form-control form-control-color" required>
                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label">Cover Button Color</label>
                            <input name="cover_button_color" type="color" value="{{ old('cover_button_color', $category->cover_button_color) }}" class="form-control form-control-color" required>
                        </div>
                        <div class="mb-3 col-md-12">
                            <div class="gap-3 d-flex align-items-center">
                                <div class="form-check form-switch">
                                    <input type="hidden" name="is_trending" value="0">
                                    <input class="form-check-input" type="checkbox" name="is_nav" value="1"
                                        role="switch" id="is_nav"
                                        {{ old('is_nav', $category->is_nav) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_nav">Show Top Navbar</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input type="hidden" name="best_selling" value="0">
                                    <input class="form-check-input" type="checkbox" name="is_special" value="1"
                                        role="switch" id="is_special"
                                        {{ old('is_special', $category->is_special) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_special">Special</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input type="hidden" name="is_featured" value="0">
                                    <input class="form-check-input" type="checkbox" name="is_slider" value="1"
                                        role="switch" id="is_slider"
                                        {{ old('is_slider', $category->is_slider) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_slider">Slider Category</label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 col-6">
                            <label class="form-label">Image</label>
                            <x-image-input name="image" :image="storage_url($category->image)" />
                        </div>
                        <div class="mb-3 col-6">
                            <label class="form-label">Cover Photo</label>
                            <x-image-input name="cover_image" :image="storage_url($category->cover_image)" />
                        </div>
                    </div>
                    <button type="submit" id="submitBtn" class="btn btn-theme">Update</button>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>

        </script>
    @endpush
@endsection
