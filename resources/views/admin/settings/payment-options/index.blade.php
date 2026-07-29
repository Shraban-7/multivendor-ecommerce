@extends('admin.layouts.app')
@section('title', 'Payment Options')

@section('content')
    <div class="mb-3 flex justify-between items-center">
        <h4 class="mb-0">Payment Options</h4>

        @if (hasPermission('admin.settings.paymentOptions.store'))
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                <i data-lucide="plus" class="icon-xs"></i> Add Option
            </button>
        @endif
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-ink border-collapse">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Image</th>
                    <th>Link</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($paymentOptions as $option)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $option->name }}</td>
                        <td>
                            @if ($option->image)
                                <img src="{{ storage_url($option->image) }}" alt="{{ $option->name }}"
                                    style="max-height: 40px;">
                            @else
                                <em class="text-ink-tertiary">No Image</em>
                            @endif
                        </td>
                        <td>
                            @if ($option->link)
                                <a href="{{ $option->link }}" target="_blank">{{ $option->link }}</a>
                            @else
                            @endif
                        </td>
                        <td>
                            @if ($option->status)
                                <span class="badge bg-feedback-success">Active</span>
                            @else
                                <span class="badge bg-feedback-danger">Inactive</span>
                            @endif
                        </td>
                        <td>
                            @if (hasPermission('admin.settings.paymentOptions.update'))
                                <button class="btn btn-light btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#editModal-{{ $option->id }}">
                                    <i data-lucide="edit" class="icon-xs"></i> Edit
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('admin.settings.paymentOptions.store') }}" method="POST" enctype="multipart/form-data"
                class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Payment Option</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="block text-xs font-medium text-ink-secondary mb-1">Name</label>
                        <input name="name" type="text" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" required>
                    </div>
                    <div class="mb-3">
                        <label class="block text-xs font-medium text-ink-secondary mb-1">Link</label>
                        <input name="link" type="url" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                    </div>
                    <div class="mb-3">
                        <label class="block text-xs font-medium text-ink-secondary mb-1">Upload Image</label>
                        <input name="image" type="file" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                    </div>
                    <div class="mb-3">
                        <label class="block text-xs font-medium text-ink-secondary mb-1">Status</label>
                        <select name="status" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors">
                            <option value="1" selected>Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>


    @foreach ($paymentOptions as $Option)
        <!-- Edit Modal -->
        <div class="modal fade" id="editModal-{{ $option->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <form action="{{ route('admin.settings.paymentOptions.update', $option->id) }}" method="POST"
                    enctype="multipart/form-data" class="modal-content">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Payment Option</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Name</label>
                            <input type="text" name="name" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ $option->name }}"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Link</label>
                            <input type="url" name="link" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ $option->link }}">
                        </div>
                        <div class="mb-3">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Image (optional)</label>
                            <input type="file" name="image" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                            @if ($option->image)
                                <div class="mt-2">
                                    <label class="block text-xs font-medium text-ink-secondary mb-1">Current Image:</label><br>
                                    <img src="{{ storage_url($option->image) }}" class="img-fluid rounded"
                                        style="max-height: 60px;">
                                </div>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Status</label>
                            <select name="status" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors">
                                <option value="1" {{ $option->status ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ !$option->status ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
@endsection
