@extends('admin.layouts.app')
@section('title', 'Sizes')

@section('content')
    <div class="mb-3 flex justify-between items-center">
        <h4 class="mb-0">Sizes</h4>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addSizeModal">
            <i data-lucide="plus" class="icon-xs"></i> Add Size
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-ink border-collapse">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Slug</th>
                    <th scope="col">Sort Order</th>
                    <th scope="col">Last Update</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sizes as $size)
                    <tr>
                        <td>{{ $size->id }}</td>
                        <td><strong>{{ $size->name }}</strong></td>
                        <td><code>{{ $size->slug }}</code></td>
                        <td>{{ $size->sort_order }}</td>
                        <td>{{ $size->updated_at->format('d-m-y h:i A') }}</td>
                        <td class="flex gap-2">
                            <button type="button" class="btn btn-light btn-sm"
                                data-bs-toggle="modal" data-bs-target="#editSizeModal-{{ $size->id }}">
                                <i data-lucide="edit" class="icon-xs"></i>
                                <span>Edit</span>
                            </button>
                            <button type="button" class="btn btn-danger btn-sm"
                                data-bs-toggle="modal" data-bs-target="#deleteSizeModal-{{ $size->id }}">
                                <i data-lucide="trash" class="icon-xs"></i>
                                <span>Delete</span>
                            </button>
                        </td>
                    </tr>

                    <div class="modal fade" id="editSizeModal-{{ $size->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <form method="POST" action="{{ route('admin.sizes.update', $size->id) }}">
                                    @csrf
                                    <div class="modal-header bg-white text-ink">
                                        <h5 class="modal-title">Edit Size</h5>
                                        <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="block text-xs font-medium text-ink-secondary mb-1 font-bold">Name</label>
                                            <input type="text" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" name="name" value="{{ $size->name }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="block text-xs font-medium text-ink-secondary mb-1 font-bold">Sort Order</label>
                                            <input type="number" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" name="sort_order" value="{{ $size->sort_order }}" min="0">
                                            <small class="text-ink-tertiary">Lower numbers appear first in listings.</small>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="deleteSizeModal-{{ $size->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Confirm Delete</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="text-center modal-body">
                                    <div class="p-4 rounded-sm bg-amber-50 border border-amber-200 text-feedback-warning text-sm flex items-start gap-3 flex" role="alert">
                                        <i data-lucide="circle-alert" class="me-2 text-feedback-danger" style="font-size: 1.5rem;"></i>
                                        <p class="mt-1 text-ink-secondary mb-0">
                                            Are you sure you want to delete size <strong>{{ $size->name }}</strong>?
                                            Variants using this size will not be affected (size will be set to null).
                                        </p>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                    <form action="{{ route('admin.sizes.delete', $size->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-ink-tertiary py-4">No sizes found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $sizes->links() }}

    <div class="modal fade" id="addSizeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.sizes.store') }}">
                    @csrf
                    <div class="modal-header bg-white text-ink">
                        <h5 class="modal-title">Add Size</h5>
                        <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="block text-xs font-medium text-ink-secondary mb-1 font-bold">Name</label>
                            <input type="text" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" name="name" placeholder="e.g. XL, 42, Large" required>
                        </div>
                        <div class="mb-3">
                            <label class="block text-xs font-medium text-ink-secondary mb-1 font-bold">Sort Order</label>
                            <input type="number" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" name="sort_order" value="50" min="0">
                            <small class="text-ink-tertiary">Lower numbers appear first in listings.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
