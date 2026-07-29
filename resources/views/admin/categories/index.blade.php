@extends('admin.layouts.app')
@section('title', 'Categories')
@section('content')

<div class="flex justify-between items-start mb-4">
    <div>
        <h1 class="text-xl font-semibold text-ink">Categories</h1>
        <p class="text-sm text-ink-secondary mt-1">Organise your product catalog</p>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#categoryModal">
        <i data-lucide="plus" class="icon-xs"></i> Add Category
    </button>
</div>

<table class="w-full text-left text-sm text-ink border-collapse">
    <thead>
        <tr>
            <th>Image</th>
            <th>Name</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($categories as $category)
        <tr>
            <td><img src="{{ storage_url($category->image) }}" width="50" class="border rounded-xs"></td>
            <td class="font-semibold text-ink">{{ $category->name }}</td>
            <td>
                @if($category->status)
                    <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-white bg-brand-deep rounded-full">Active</span>
                @else
                    <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-ink-tertiary bg-surface-muted rounded-full">Inactive</span>
                @endif
            </td>
            <td>
                <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $category->id }}">
                    <i data-lucide="edit" class="icon-xs"></i> Edit
                </button>
            </td>
        </tr>
        @foreach($category->subcategories as $sub)
        <tr>
            <td></td>
            <td><span class="text-ink-tertiary pl-4">— {{ $sub->name }}</span></td>
            <td>
                @if($sub->status)
                    <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-white bg-brand-deep rounded-full">Active</span>
                @else
                    <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-ink-tertiary bg-surface-muted rounded-full">Inactive</span>
                @endif
            </td>
            <td>
                <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $sub->id }}">
                    <i data-lucide="edit" class="icon-xs"></i> Edit
                </button>
            </td>
        </tr>
        @endforeach
        @endforeach
    </tbody>
</table>

@foreach($categories as $item)
@include('admin.categories.edit_modal', ['category' => $item])
@foreach($item->subcategories as $sub)
@include('admin.categories.edit_modal', ['category' => $sub])
@endforeach
@endforeach

<div class="modal fade" id="categoryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" id="categoryForm">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <div class="modal-content">
                <div class="modal-header border-b border-border">
                    <h5 class="modal-title text-sm font-semibold text-ink" id="modalTitle">Add Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="block text-xs font-medium text-ink-secondary mb-1">Name</label>
                        <input type="text" name="name" id="cat_name" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" required>
                    </div>
                    <div class="mb-3">
                        <label class="block text-xs font-medium text-ink-secondary mb-1">Parent Category (Optional)</label>
                        <select name="category_id" id="cat_parent" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                            <option value="">None (Main)</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="block text-xs font-medium text-ink-secondary mb-1">Image</label>
                        <input type="file" name="image" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                    </div>
                    <div class="flex items-center gap-2">
                        <input class="h-4 w-4 rounded border-border text-brand focus:ring-brand" type="checkbox" name="status" value="1" id="cat_status" checked>
                        <label class="text-sm text-ink" for="cat_status">Is Active</label>
                    </div>
                </div>
                <div class="modal-footer border-t border-border">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection