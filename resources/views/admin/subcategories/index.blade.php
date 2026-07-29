@extends('admin.layouts.app')
@section('title', 'Subcategories')

@section('content')
    <div class="mb-3 flex justify-between items-center">
        <h4 class="mb-0">Subcategories</h4>
        <a href="{{ route('admin.subcategories.create') }}" class="btn btn-primary">
            <i data-feather="plus" class="icon-xs"></i> Add Subcategory
        </a>
    </div>

    <div class="overflow-x-auto ">
        <table class="w-full text-left text-sm text-ink border-collapse mb-3 bg-white table-bordered">
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
                            <div class="flex items-center">
                                <img src="{{ storage_url($subcategory->image) }}" class="border rounded-full"
                                    alt="Image" style="height:80px; width:80px">
                                <div class="mt-2 ms-3">
                                    <div>{{ $subcategory->name }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="flex items-center">
                                <img src="{{ storage_url($subcategory->cover_image) }}" class="border rounded-full"
                                    alt="Cover Image" style="height:80px; width:80px">
                                <div class="mt-2 ms-3">
                                    <div>{{ $subcategory->cover_title }}</div>
                                    <div class="mt-1 flex items-center">
                                        <span class="me-1">BG Color:</span>
                                        <div
                                            style="width: 20px; height: 20px; background-color: {{ $subcategory->cover_bg_color }}; border: 1px solid #ccc; border-radius: 3px;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="flex items-center gap-2">

                            @if (hasPermission('admin.subcategories.toggleStatus'))
                                <button type="button"
                                    class="inline-flex items-center justify-center px-3 py-1.5 text-sm font-medium rounded-xs {{ $subcategory->status ? 'btn-danger' : 'btn-success' }}"
                                    data-bs-toggle="modal" data-bs-target="#toggleStatusModal{{ $subcategory->id }}">
                                    {{ $subcategory->status ? 'Inactive' : 'Active' }}
                                </button>
                            @endif

                            <!-- Confirmation Modal -->
                            <div class="modal fade" id="toggleStatusModal{{ $subcategory->id }}" tabindex="-1"
                                aria-labelledby="toggleStatusModalLabel{{ $subcategory->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="toggleStatusModalLabel{{ $subcategory->id }}">
                                                Confirm
                                                Action</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to {{ $subcategory->status ? 'deactivate' : 'activate' }}
                                            this category?
                                        </div>
                                        <div class="modal-footer">
                                            <form
                                                action="{{ route('admin.subcategories.toggleStatus', $subcategory->id) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-primary">Yes, Confirm</button>
                                                <button type="button" class="btn btn-light"
                                                    data-bs-dismiss="modal">Cancel</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if (hasPermission('admin.subcategories.edit'))
                                <a href="{{ route('admin.subcategories.edit', $subcategory->id) }}"
                                    class="btn btn-light btn-sm">
                                    <i data-feather="edit" class="icon-xs"></i> Edit
                                </a>
                            @endif
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="flex justify-end">
        {{ $subcategories->links() }}
    </div>
@endsection
