@extends('admin.layouts.app')
@section('title', 'Subcategories')

@section('content')
    <div class="flex justify-between items-start mb-4">
        <div>
            <h1 class="text-xl font-semibold text-ink">Subcategories</h1>
            <p class="text-sm text-ink-secondary mt-1">Manage subcategory details and covers</p>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSubcategoryModal">
            <i data-lucide="plus" class="icon-xs"></i> Add Subcategory
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-ink border-collapse">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Cover</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($subcategories as $subcategory)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <div class="flex items-center gap-3">
                                <img src="{{ storage_url($subcategory->image) }}"
                                    class="w-10 h-10 rounded-full border object-cover" alt="Image">
                                <span class="font-medium text-ink">{{ $subcategory->name }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="flex items-center gap-3">
                                <img src="{{ storage_url($subcategory->cover_image) }}"
                                    class="w-10 h-10 rounded-full border object-cover" alt="Cover Image">
                                <div>
                                    <div class="font-medium text-ink">{{ $subcategory->cover_title }}</div>
                                    <div class="flex items-center gap-1 mt-0.5">
                                        <span class="text-xs text-ink-tertiary">BG:</span>
                                        <span class="inline-block w-4 h-4 border border-border rounded-xs"
                                            style="background-color: {{ $subcategory->cover_bg_color }};"></span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="flex items-center gap-2">
                                @if (hasPermission('admin.subcategories.toggleStatus'))
                                    <button type="button"
                                        class="btn btn-sm {{ $subcategory->status ? 'btn-danger' : 'btn-success' }}"
                                        data-bs-toggle="modal" data-bs-target="#toggleStatusModal{{ $subcategory->id }}">
                                        {{ $subcategory->status ? 'Inactive' : 'Active' }}
                                    </button>
                                @endif
                                @if (hasPermission('admin.subcategories.edit'))
                                    <button class="btn btn-light btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#editSubcategoryModal-{{ $subcategory->id }}">
                                        <i data-lucide="edit" class="icon-xs"></i> Edit
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @foreach ($subcategories as $subcategory)
    <div class="modal fade" id="toggleStatusModal{{ $subcategory->id }}" tabindex="-1"
        aria-labelledby="toggleStatusModalLabel{{ $subcategory->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-b border-border">
                    <h5 class="modal-title text-sm font-semibold text-ink" id="toggleStatusModalLabel{{ $subcategory->id }}">Confirm Action</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-sm text-ink-secondary">
                    Are you sure you want to {{ $subcategory->status ? 'deactivate' : 'activate' }} this subcategory?
                </div>
                <div class="modal-footer border-t border-border">
                    <form action="{{ route('admin.subcategories.toggleStatus', $subcategory->id) }}" method="POST">
                        @csrf
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Yes, Confirm</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editSubcategoryModal-{{ $subcategory->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('admin.subcategories.update', $subcategory->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header border-b border-border">
                        <h5 class="modal-title text-sm font-semibold text-ink">Edit Subcategory</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-medium text-ink-secondary mb-1">Select Category</label>
                                <select name="category_id" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors" required>
                                    <option value="">-- Select Category --</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ $subcategory->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-ink-secondary mb-1">Name</label>
                                <input name="name" type="text" value="{{ old('name', $subcategory->name) }}" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" required>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-ink-secondary mb-1">Icon (FontAwesome)</label>
                                <input name="icon" type="text" value="{{ old('icon', $subcategory->icon) }}" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" placeholder="e.g. fas fa-tag">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-ink-secondary mb-1">Cover Title</label>
                                <input name="cover_title" type="text" value="{{ old('cover_title', $subcategory->cover_title) }}" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" required>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-ink-secondary mb-1">Cover Description</label>
                                <textarea name="cover_description" rows="3" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">{{ old('description', $subcategory->cover_description) }}</textarea>
                            </div>
                            <div class="grid grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-ink-secondary mb-1">Cover BG Color</label>
                                    <input name="cover_bg_color" type="color" value="{{ old('cover_bg_color', $subcategory->cover_bg_color) }}" class="w-full h-10 px-1 py-1 text-sm text-ink bg-white border border-border rounded-xs cursor-pointer" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-ink-secondary mb-1">Cover Text Color</label>
                                    <input name="cover_text_color" type="color" value="{{ old('cover_text_color', $subcategory->cover_text_color) }}" class="w-full h-10 px-1 py-1 text-sm text-ink bg-white border border-border rounded-xs cursor-pointer" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-ink-secondary mb-1">Cover Button Color</label>
                                    <input name="cover_button_color" type="color" value="{{ old('cover_button_color', $subcategory->cover_button_color) }}" class="w-full h-10 px-1 py-1 text-sm text-ink bg-white border border-border rounded-xs cursor-pointer" required>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-ink-secondary mb-1">Image</label>
                                    <x-image-input name="image" :image="storage_url($subcategory->image)" />
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-ink-secondary mb-1">Cover Photo</label>
                                    <x-image-input name="cover_image" :image="storage_url($subcategory->cover_image)" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-t border-border">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach

    <div class="flex justify-end mt-4">
        {{ $subcategories->links() }}
    </div>
@endsection

@push('modals')
    @foreach ($subcategories as $subcategory)
    <div class="modal fade" id="toggleStatusModal{{ $subcategory->id }}" tabindex="-1"
        aria-labelledby="toggleStatusModalLabel{{ $subcategory->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-b border-border">
                    <h5 class="modal-title text-sm font-semibold text-ink" id="toggleStatusModalLabel{{ $subcategory->id }}">Confirm Action</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-sm text-ink-secondary">
                    Are you sure you want to {{ $subcategory->status ? 'deactivate' : 'activate' }} this subcategory?
                </div>
                <div class="modal-footer border-t border-border">
                    <form action="{{ route('admin.subcategories.toggleStatus', $subcategory->id) }}" method="POST">
                        @csrf
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Yes, Confirm</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editSubcategoryModal-{{ $subcategory->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('admin.subcategories.update', $subcategory->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header border-b border-border">
                        <h5 class="modal-title text-sm font-semibold text-ink">Edit Subcategory</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-medium text-ink-secondary mb-1">Select Category</label>
                                <select name="category_id" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors" required>
                                    <option value="">-- Select Category --</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ $subcategory->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-ink-secondary mb-1">Name</label>
                                <input name="name" type="text" value="{{ old('name', $subcategory->name) }}" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" required>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-ink-secondary mb-1">Icon (FontAwesome)</label>
                                <input name="icon" type="text" value="{{ old('icon', $subcategory->icon) }}" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" placeholder="e.g. fas fa-tag">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-ink-secondary mb-1">Cover Title</label>
                                <input name="cover_title" type="text" value="{{ old('cover_title', $subcategory->cover_title) }}" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" required>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-ink-secondary mb-1">Cover Description</label>
                                <textarea name="cover_description" rows="3" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">{{ old('description', $subcategory->cover_description) }}</textarea>
                            </div>
                            <div class="grid grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-ink-secondary mb-1">Cover BG Color</label>
                                    <input name="cover_bg_color" type="color" value="{{ old('cover_bg_color', $subcategory->cover_bg_color) }}" class="w-full h-10 px-1 py-1 text-sm text-ink bg-white border border-border rounded-xs cursor-pointer" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-ink-secondary mb-1">Cover Text Color</label>
                                    <input name="cover_text_color" type="color" value="{{ old('cover_text_color', $subcategory->cover_text_color) }}" class="w-full h-10 px-1 py-1 text-sm text-ink bg-white border border-border rounded-xs cursor-pointer" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-ink-secondary mb-1">Cover Button Color</label>
                                    <input name="cover_button_color" type="color" value="{{ old('cover_button_color', $subcategory->cover_button_color) }}" class="w-full h-10 px-1 py-1 text-sm text-ink bg-white border border-border rounded-xs cursor-pointer" required>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-ink-secondary mb-1">Image</label>
                                    <x-image-input name="image" :image="storage_url($subcategory->image)" />
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-ink-secondary mb-1">Cover Photo</label>
                                    <x-image-input name="cover_image" :image="storage_url($subcategory->cover_image)" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-t border-border">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach

    <div class="modal fade" id="addSubcategoryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('admin.subcategories.store') }}" method="POST" enctype="multipart/form-data">
                    @CSRF
                    <div class="modal-header border-b border-border">
                        <h5 class="modal-title text-sm font-semibold text-ink">Add Subcategory</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-medium text-ink-secondary mb-1">Select Category</label>
                                <select name="category_id" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors select2-add" required>
                                    <option value="">-- Select Category --</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-ink-secondary mb-1">Name</label>
                                <input name="name" type="text" value="" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" required>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-ink-secondary mb-1">Icon (FontAwesome)</label>
                                <input name="icon" type="text" value="" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" placeholder="e.g. fas fa-tag">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-ink-secondary mb-1">Cover Title</label>
                                <input name="cover_title" type="text" value="" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" required>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-ink-secondary mb-1">Cover Description</label>
                                <textarea name="cover_description" rows="3" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors"></textarea>
                            </div>
                            <div class="grid grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-ink-secondary mb-1">Cover BG Color</label>
                                    <input name="cover_bg_color" type="color" value=""
                                        class="w-full h-10 px-1 py-1 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep cursor-pointer" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-ink-secondary mb-1">Cover Text Color</label>
                                    <input name="cover_text_color" type="color" value=""
                                        class="w-full h-10 px-1 py-1 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep cursor-pointer" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-ink-secondary mb-1">Cover Button Color</label>
                                    <input name="cover_button_color" type="color" value=""
                                        class="w-full h-10 px-1 py-1 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep cursor-pointer" required>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-ink-secondary mb-1">Image</label>
                                    <x-image-input name="image" />
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-ink-secondary mb-1">Cover Photo</label>
                                    <x-image-input name="cover_image" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-t border-border">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush

@push('scripts')
        <script>
            $(document).ready(function() {
                $('.select2-add').select2({
                    placeholder: "-- Select Category --",
                    allowClear: true,
                    dropdownParent: $('#addSubcategoryModal')
                });
            });
        </script>
    @endpush
@endsection