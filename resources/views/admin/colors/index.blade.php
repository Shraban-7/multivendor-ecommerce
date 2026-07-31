@extends('admin.layouts.app')
@section('title', 'Colors')

@section('content')
    <section class="bg-white rounded-sm shadow-sm overflow-hidden mb-4 relative">
        <div class="absolute top-0 left-0 right-0 h-1" style="background: linear-gradient(90deg, #F85606, #fb923c, #fbbf24);"></div>
        <div class="p-5 lg:p-6 pt-6">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div class="min-w-0">
                    <nav class="flex items-center gap-1 mb-2 text-xs text-ink-tertiary">
                        <i data-lucide="palette" class="text-brand-deep" style="width:12px;height:12px;"></i>
                        <span>Catalog</span>
                        <i data-lucide="chevron-right" style="width:12px;height:12px;"></i>
                        <span class="text-ink-soft font-semibold">Colors</span>
                    </nav>
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        <h1 class="text-xl font-bold text-ink-emphasis mb-0">Colors</h1>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-brand-tint text-brand-deep">
                            <i data-lucide="palette" style="width:11px;height:11px;" class="me-1"></i> {{ $colors->total() }} Total
                        </span>
                    </div>
                    <p class="text-sm text-ink-secondary mb-0">Manage product color swatches used across the catalog.</p>
                </div>
                <div class="flex flex-wrap gap-2 shrink-0">
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addColorModal">
                        <i data-lucide="plus" style="width:14px;height:14px;"></i> Add Color
                    </button>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-white rounded-sm shadow-sm overflow-hidden">
        <div class="px-4 py-3 border-b border-border bg-surface-muted flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="shrink-0 w-7 h-7 rounded-sm bg-brand-tint text-brand flex items-center justify-center">
                    <i data-lucide="palette" style="width:14px;height:14px;"></i>
                </span>
                <h5 class="mb-0 font-bold text-ink">Color List</h5>
            </div>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider bg-brand-tint text-brand-deep">{{ $colors->total() }} colors</span>
        </div>
        <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-ink border-collapse">
            <thead>
                <tr class="bg-surface-muted/50">
                    <th scope="col" class="px-4 py-2.5 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">#</th>
                    <th scope="col" class="px-4 py-2.5 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Name</th>
                    <th scope="col" class="px-4 py-2.5 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Slug</th>
                    <th scope="col" class="px-4 py-2.5 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Hex Code</th>
                    <th scope="col" class="px-4 py-2.5 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Swatch</th>
                    <th scope="col" class="px-4 py-2.5 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Image</th>
                    <th scope="col" class="px-4 py-2.5 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Last Update</th>
                    <th scope="col" class="px-4 py-2.5 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Action</th>
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
                                <i data-lucide="edit" class="icon-xs"></i>
                                <span>Edit</span>
                            </button>
                            <button type="button" class="btn btn-danger btn-sm"
                                data-bs-toggle="modal" data-bs-target="#deleteColorModal-{{ $color->id }}">
                                <i data-lucide="trash" class="icon-xs"></i>
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
                                            <input type="text" class="w-full px-3 py-2 text-sm text-ink bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" name="name" value="{{ $color->name }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="block text-xs font-medium text-ink-secondary mb-1 font-bold">Hex Code</label>
                                            <div class="flex gap-2 items-center">
                                                <input type="color" class="w-full px-3 py-2 text-sm text-ink bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors w-auto" name="hex_code" value="{{ $color->hex_code }}" style="width:50px;height:38px;">
                                                <input type="text" class="w-full px-3 py-2 text-sm text-ink bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" name="hex_code_text" value="{{ $color->hex_code }}" placeholder="#FFFFFF" maxlength="7">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="block text-xs font-medium text-ink-secondary mb-1 font-bold">Image</label>
                                            <input type="file" class="w-full px-3 py-2 text-sm text-ink bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" name="image" accept="image/*">
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
                                        <i data-lucide="circle-alert" class="me-2 text-feedback-danger" style="font-size: 1.5rem;"></i>
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
        <div class="px-4 py-3 border-t border-border bg-surface-muted/40">
            {{ $colors->links() }}
        </div>
    </section>

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
                            <input type="text" class="w-full px-3 py-2 text-sm text-ink bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" name="name" placeholder="e.g. Midnight Blue" required>
                        </div>
                        <div class="mb-3">
                            <label class="block text-xs font-medium text-ink-secondary mb-1 font-bold">Hex Code</label>
                            <div class="flex gap-2 items-center">
                                <input type="color" class="w-full px-3 py-2 text-sm text-ink bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors w-auto" name="hex_code" value="#000000" style="width:50px;height:38px;">
                                <input type="text" class="w-full px-3 py-2 text-sm text-ink bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" name="hex_code_text" value="#000000" placeholder="#FFFFFF" maxlength="7">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="block text-xs font-medium text-ink-secondary mb-1 font-bold">Image (optional)</label>
                            <input type="file" class="w-full px-3 py-2 text-sm text-ink bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" name="image" accept="image/*">
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
