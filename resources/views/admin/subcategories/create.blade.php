@extends('admin.layouts.app')
@section('title', 'Add Subcategory')
@section('content')
    <div class="mb-3 flex justify-between items-end">
        <h4 class="mb-0">Add Subcategory</h4>
    </div>

    <div class="grid grid-cols-1">
        <div class="col-span-2">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden p-5">
                <form id="form" action="{{ route('admin.subcategories.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @CSRF
                    <div class="grid grid-cols-1">
                        <div class="mb-3 md:col-span-1">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Select Category</label>
                            <select name="category_id" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors select2" required>
                                <option value="">-- Select Category --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 md:col-span-1">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Name</label>
                            <input name="name" type="text" value="" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" required>
                        </div>
                        <div class="mb-3 md:col-span-1">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Cover Title</label>
                            <input name="cover_title" type="text" value="" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" required>
                        </div>
                        <div class="mb-3 md:col-span-full">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Cover Description</label>
                            <x-textarea-input name="cover_description" value="" />
                        </div>
                        <div class="mb-3 md:col-span-1">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Cover Background Color</label>
                            <input name="cover_bg_color" type="color" value=""
                                class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" required>
                        </div>
                        <div class="mb-3 md:col-span-1">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Cover Text Color</label>
                            <input name="cover_text_color" type="color" value=""
                                class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" required>
                        </div>
                        <div class="mb-3 md:col-span-1">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Cover Button Color</label>
                            <input name="cover_button_color" type="color" value=""
                                class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" required>
                        </div>

                        <div class="mb-3 col-span-1">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Image</label>
                            <x-image-input name="image" />
                        </div>
                        <div class="mb-3 col-span-1">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Cover Photo</label>
                            <x-image-input name="cover_image" />
                        </div>
                    </div>
                    <button type="submit" id="submitBtn" class="btn btn-primary">Save</button>
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
