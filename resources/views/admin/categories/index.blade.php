@extends('admin.layouts.app')
@section('title', 'Categories')

@section('content')
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Categories</h4>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
            <i data-feather="plus" class="icon-xs"></i> Add Category
        </a>
    </div>

    <div class="table-responsive ">
        <table class="table mb-3 bg-white table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Cover</th>
                    <th>Sections</th>
                    <th>Subcategories</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categories as $category)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="{{ storage_url($category->image) }}" class="border rounded-circle" alt="Image"
                                    style="height:80px; width:80px">
                                <div class="mt-2 ms-3">
                                    <div>{{ $category->name }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="{{ storage_url($category->cover_image) }}" class="border rounded-circle"
                                    alt="Cover Image" style="height:80px; width:80px">
                                <div class="mt-2 ms-3">
                                    <div>{{ $category->cover_title }}</div>
                                    <div class="mt-1 d-flex align-items-center">
                                        <span class="me-1">BG Color:</span>
                                        <div
                                            style="width: 20px; height: 20px; background-color: {{ $category->cover_bg_color }}; border: 1px solid #ccc; border-radius: 3px;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <ul>
                                @if ($category->is_nav)
                                    <li>Show Top Navbar</li>
                                @endif
                                @if ($category->is_special)
                                    <li class="text-success">Special Category</li>
                                @endif
                                @if ($category->is_slider)
                                    <li>Show on slider</li>
                                @endif
                            </ul>
                        </td>
                        <td>
                            <ul>
                                @foreach ($category->subcategories as $subcategory)
                                    <li>{{ $subcategory->name }}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td class="d-flex align-items-center gap-2">
                            <button type="button" class="btn btn-sm {{ $category->status ? 'btn-danger' : 'btn-success' }}"
                                data-bs-toggle="modal" data-bs-target="#toggleStatusModal{{ $category->id }}">
                                {{ $category->status ? 'Inactive' : 'Active' }}
                            </button>

                            <!-- Confirmation Modal -->
                            <div class="modal fade" id="toggleStatusModal{{ $category->id }}" tabindex="-1"
                                aria-labelledby="toggleStatusModalLabel{{ $category->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="toggleStatusModalLabel{{ $category->id }}">Confirm
                                                Action</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to {{ $category->status ? 'deactivate' : 'activate' }}
                                            this category?
                                        </div>
                                        <div class="modal-footer">
                                            <form action="{{ route('admin.categories.toggleStatus', $category->id) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-primary">Yes, Confirm</button>
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cancel</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <a href="{{ route('admin.categories.edit', $category->id) }}"
                                class="btn btn-light border btn-sm">
                                <i data-feather="edit" class="icon-xs"></i> Edit
                            </a>
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-end">
        {{ $categories->links() }}
    </div>
@endsection
