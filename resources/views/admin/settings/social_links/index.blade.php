@extends('admin.layouts.app')
@section('title', 'Social Links')

@section('content')
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Social Links</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
            <i data-feather="plus" class="icon-xs"></i> Add Social Link
        </button>
    </div>

    <div class="table-responsive ">
        <table class="table mb-3 bg-white table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Icon</th>
                    <th>Link</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($socialLinks as $index => $socialLink)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $socialLink->name }}</td>
                        <td><i class="fab {{ $socialLink->icon_name }}"></i> <code>{{ $socialLink->icon_name }}</code></td>
                        <td><a href="{{ $socialLink->link }}" target="_blank">{{ $socialLink->link }}</a></td>
                        <td>
                            @if ($socialLink->status)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-light border btn-sm" data-bs-toggle="modal"
                                data-bs-target="#editModal-{{ $socialLink->id }}">
                                <i data-feather="edit" class="icon-xs"></i> Edit
                            </button>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editModal-{{ $socialLink->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Social Link</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('admin.settings.socialLinks.update', $socialLink->id) }}"
                                    method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Name</label>
                                            <input type="text" name="name" class="form-control"
                                                value="{{ $socialLink->name }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Icon Name (FontAwesome)</label>
                                            <input name="icon_name" type="text" class="form-control" value="{{ $socialLink->icon_name }}"
                                                placeholder="e.g. fa-facebook-f" required>
                                            <small class="text-muted">
                                                Browse icons at
                                                <a href="https://fontawesome.com/search" target="_blank"
                                                    rel="noopener noreferrer">
                                                    Font Awesome Icons
                                                </a>
                                            </small>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Link</label>
                                            <input type="url" name="link" class="form-control"
                                                value="{{ $socialLink->link }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Status</label>
                                            <select name="status" class="form-select">
                                                <option value="1" {{ $socialLink->status ? 'selected' : '' }}>Active
                                                </option>
                                                <option value="0" {{ !$socialLink->status ? 'selected' : '' }}>
                                                    Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('admin.settings.socialLinks.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Social Link</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input name="name" type="text" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Icon Name (FontAwesome)</label>
                        <input name="icon_name" type="text" class="form-control" placeholder="e.g. fa-facebook-f"
                            required>
                        <small class="text-muted">
                            Browse icons at
                            <a href="https://fontawesome.com/search" target="_blank" rel="noopener noreferrer">
                                Font Awesome Icons
                            </a>
                        </small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Link</label>
                        <input name="link" type="url" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-theme">Save</button>
                </div>
            </form>
        </div>
    </div>

@endsection
