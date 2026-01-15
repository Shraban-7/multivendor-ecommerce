@extends('admin.layouts.app')
@section('title', 'Categories')
@section('content')

<div class="d-flex justify-content-between align-items-end mb-3">
    <h3 class="mb-0">Categories</h3>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#categoryModal">+ Add Category</button>
</div>

<table class="table table-bordered align-middle bg-white">
    <thead>
        <tr>
            <th>Image</th>
            <th>Name</th>
            <th>Icon</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($categories as $category)
        <tr>
            <td><img src="{{ storage_url($category->image) }}" width="50"></td>
            <td><strong>{{ $category->name }}</strong></td>
            <td>{{ $category->icon }}</td>
            <td>@if($category->status)<span class="badge bg-primary">Active</span> @else <span class="badge bg-secondary">Inactive</span> @endif</td>
            <td>
                <button class="btn btn-sm btn-light border" data-bs-toggle="modal" data-bs-target="#editModal{{ $category->id }}">
                    Edit
                </button>
            </td>
        </tr>
        @foreach($category->subcategories as $sub)
        <tr>
            <td></td>
            <td><span class="text-muted ms-4">— {{ $sub->name }}</span></td>
            <td>@if($sub->status)<span class="badge bg-primary">Active</span> @else <span class="badge bg-secondary">Inactive</span> @endif</td>
            <td>
                <button class="btn btn-sm btn-light border" data-bs-toggle="modal" data-bs-target="#editModal{{ $sub->id }}">
                    Edit
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
    <div class="modal-dialog">
        <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" id="categoryForm">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add Category</h5>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Name</label>
                        <input type="text" name="name" id="cat_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Parent Category (Optional)</label>
                        <select name="category_id" id="cat_parent" class="form-control">
                            <option value="">None (Main)</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Image</label>
                        <input type="file" name="image" class="form-control">
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="status" value="1" id="cat_status" checked>
                        <label class="form-check-label">Is Active</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection