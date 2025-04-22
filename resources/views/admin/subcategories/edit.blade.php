@extends('admin.layouts.app')
@section('title', 'Edit Subcategory')
@section('content')
    <div class="mb-3 d-flex justify-content-between align-items-end">
        <h4 class="mb-0">Edit Subcategory</h4>
    </div>

    <div class="row">
        <div class="col-8">
            <div class="card card-body">
                <form id="form" action="{{ route('admin.subcategories.update', $subcategory->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="mb-3 col-md-4">
                            <label class="form-label">Select Category</label>
                            <select name="category_id" class="form-select select2" required>
                                <option value="">-- Select Category --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ $subcategory->category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3 col-md-4">
                            <label class="form-label">Name</label>
                            <input name="name" type="text" value="{{ old('name', $subcategory->name) }}" class="form-control" required>
                        </div>

                        <div class="mb-3 col-md-4">
                            <label class="form-label">Cover Title</label>
                            <input name="cover_title" type="text" value="{{ old('cover_title', $subcategory->cover_title) }}" class="form-control" required>
                        </div>

                        <div class="mb-3 col-md-12">
                            <label class="form-label">Cover Description</label>
                            <x-textarea-input name="cover_description" :value="old('description', $subcategory->cover_description)" />
                        </div>

                        <div class="mb-3 col-md-4">
                            <label class="form-label">Cover Background Color</label>
                            <input name="cover_bg_color" type="color" value="{{ old('cover_bg_color', $subcategory->cover_bg_color) }}" class="form-control form-control-color" required>
                        </div>

                        <div class="mb-3 col-md-4">
                            <label class="form-label">Cover Text Color</label>
                            <input name="cover_text_color" type="color" value="{{ old('cover_text_color', $subcategory->cover_text_color) }}" class="form-control form-control-color" required>
                        </div>

                        <div class="mb-3 col-md-4">
                            <label class="form-label">Cover Button Color</label>
                            <input name="cover_button_color" type="color" value="{{ old('cover_button_color', $subcategory->cover_button_color) }}" class="form-control form-control-color" required>
                        </div>

                        <div class="mb-3 col-6">
                            <label class="form-label">Image</label>
                            <x-image-input name="image" :image="storage_url($subcategory->image)" />
                        </div>
                        <div class="mb-3 col-6">
                            <label class="form-label">Cover Photo</label>
                            <x-image-input name="cover_image" :image="storage_url($subcategory->cover_image)" />
                        </div>
                    </div>
                    <button type="submit" id="submitBtn" class="btn btn-theme">Update</button>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('.select2').select2({
                    placeholder: "-- Select Category --",
                    allowClear: true
                });
            });
        </script>
    @endpush
@endsection
