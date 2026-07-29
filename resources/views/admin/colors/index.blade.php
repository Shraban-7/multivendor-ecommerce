@extends('admin.layouts.app')
@section('title', 'Colors')

@section('content')
    <div class="mb-3 flex justify-between items-center">
        <h4 class="mb-0">Colors</h4>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addColorModal">
            <i data-feather="plus" class="icon-xs"></i> Add Color
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-ink border-collapse mb-3 bg-white table-bordered">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Slug</th>
                    <th scope="col">Hex Code</th>
                    <th scope="col">Swatch</th>
                    <th scope="col">Image</th>
                    <th scope="col">Last Update</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($colors as $color)
                    <tr>
                        <td>{{ $color->id }}</td>
                        <td>{{ $color->name }}</td>
                        <td><code>{{ $color->slug }}</code></td>
                        <td><code>{{ $color->hex_code }}</code></td>
                        <td>
                            <span style="display:inline-block;width:28px;height:28px;border-radius:50%;background:{{ $color->hex_code }};border:1px solid #ddd;vertical-align:middle;"></span>
                        </td>
                        <td>
                            @if($color->image)
                                <img src="{{ storage_url($color->image) }}" alt="{{ $color->name }}" style="width:40px;height:40px;object-fit:cover;border-radius:4px;">
                            @else
                                <span class="text-ink-tertiary">—</span>
                            @endif
                        </td>
                        <td>{{ $color->updated_at->format('d-m-y h:i A') }}</td>
                        <td class="flex gap-2">
                            <button type="button" class="btn btn-light btn-sm"
                                data-bs-toggle="modal" data-bs-target="#editColorModal-{{ $color->id }}">
                                <i data-feather="edit" class="icon-xs"></i>
                                <span>Edit</span>
                            </button>
                            <button type="button" class="btn btn-danger btn-sm"
                                data-bs-toggle="modal" data-bs-target="#deleteColorModal-{{ $color->id }}">
                                <i data-feather="trash" class="icon-xs"></i>
                                <span>Delete</span>
                            </button>
                        </td>
                    </tr>

                    <div class="modal fade" id="editColorModal-{{ $color->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <form method="POST" action="{{ route('admin.colors.update', $color->id) }}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-header bg-white text-ink">
                                        <h5 class="modal-title">Edit Color</h5>
                                        <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="block text-xs font-medium text-ink-secondary mb-1 font-bold">Name</label>
                                            <input type="text" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" name="name" value="{{ $color->name }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="block text-xs font-medium text-ink-secondary mb-1 font-bold">Hex Code</label>
                                            <div class="flex gap-2 items-center">
                                                <input type="color" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors w-auto" name="hex_code" value="{{ $color->hex_code }}" style="width:50px;height:38px;">
                                                <input type="text" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" name="hex_code_text" value="{{ $color->hex_code }}" placeholder="#FFFFFF" maxlength="7">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="block text-xs font-medium text-ink-secondary mb-1 font-bold">Image</label>
                                            <input type="file" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" name="image" accept="image/*">
                                            @if($color->image)
                                                <div class="mt-2">
                                                    <img src="{{ storage_url($color->image) }}" alt="" style="width:60px;height:60px;object-fit:cover;border-radius:4px;border:1px solid #ddd;">
                                                </div>
                                            @endif
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

                    <div class="modal fade" id="deleteColorModal-{{ $color->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Confirm Delete</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="text-center modal-body">
                                    <div class="p-4 rounded-sm bg-amber-50 border border-amber-200 text-feedback-warning text-sm flex items-start gap-3 flex" role="alert">
                                        <i class="bi bi-exclamation-circle-fill me-2 text-feedback-danger" style="font-size: 1.5rem;"></i>
                                        <p class="mt-1 text-ink-secondary mb-0">
                                            Are you sure you want to delete <strong>{{ $color->name }}</strong>?
                                            Variants using this color will not be affected (color will be set to null).
                                        </p>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                    <form action="{{ route('admin.colors.delete', $color->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-ink-tertiary py-4">No colors found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $colors->links() }}

    <div class="modal fade" id="addColorModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.colors.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header bg-white text-ink">
                        <h5 class="modal-title">Add Color</h5>
                        <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="block text-xs font-medium text-ink-secondary mb-1 font-bold">Name</label>
                            <input type="text" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" name="name" placeholder="e.g. Midnight Blue" required>
                        </div>
                        <div class="mb-3">
                            <label class="block text-xs font-medium text-ink-secondary mb-1 font-bold">Hex Code</label>
                            <div class="flex gap-2 items-center">
                                <input type="color" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors w-auto" name="hex_code" value="#000000" style="width:50px;height:38px;">
                                <input type="text" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" name="hex_code_text" value="#000000" placeholder="#FFFFFF" maxlength="7">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="block text-xs font-medium text-ink-secondary mb-1 font-bold">Image (optional)</label>
                            <input type="file" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" name="image" accept="image/*">
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

@push('scripts')
<script>
    document.querySelectorAll('input[name="hex_code"]').forEach(picker => {
        const textInput = picker.closest('.flex').querySelector('input[name="hex_code_text"]');
        if (textInput) {
            picker.addEventListener('input', () => { textInput.value = picker.value; });
            textInput.addEventListener('input', () => { picker.value = textInput.value; });
        }
    });
</script>
@endpush
