@extends('admin.layouts.app')
@section('title', 'Edit Subcategory')
@section('content')
    <div class="mb-3 flex justify-between items-end">
        <h4 class="mb-0">Edit Subcategory</h4>
    </div>

    <div class="grid grid-cols-1">
        <div class="col-span-2">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden p-5">
                <form id="form" action="{{ route('admin.subcategories.update', $subcategory->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1">
                        <div class="mb-3 md:col-span-1">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Select Category</label>
                            <select name="category_id" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors select2" required>
                                <option value="">-- Select Category --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ $subcategory->category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3 md:col-span-1">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Name</label>
                            <input name="name" type="text" value="{{ old('name', $subcategory->name) }}" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" required>
                        </div>

                        <div class="mb-3 md:col-span-1">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Cover Title</label>
                            <input name="cover_title" type="text" value="{{ old('cover_title', $subcategory->cover_title) }}" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" required>
                        </div>

                        <div class="mb-3 md:col-span-full">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Cover Description</label>
                            <x-textarea-input name="cover_description" :value="old('description', $subcategory->cover_description)" />
                        </div>

                        <div class="mb-3 md:col-span-1">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Cover Background Color</label>
                            <input name="cover_bg_color" type="color" value="{{ old('cover_bg_color', $subcategory->cover_bg_color) }}" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" required>
                        </div>

                        <div class="mb-3 md:col-span-1">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Cover Text Color</label>
                            <input name="cover_text_color" type="color" value="{{ old('cover_text_color', $subcategory->cover_text_color) }}" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" required>
                        </div>

                        <div class="mb-3 md:col-span-1">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Cover Button Color</label>
                            <input name="cover_button_color" type="color" value="{{ old('cover_button_color', $subcategory->cover_button_color) }}" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" required>
                        </div>

                        <div class="mb-3 col-span-1">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Image</label>
                            <x-image-input name="image" :image="storage_url($subcategory->image)" />
                        </div>
                        <div class="mb-3 col-span-1">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Cover Photo</label>
                            <x-image-input name="cover_image" :image="storage_url($subcategory->cover_image)" />
                        </div>
                    </div>
                    <button type="submit" id="submitBtn" class="btn btn-primary">Update</button>
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
