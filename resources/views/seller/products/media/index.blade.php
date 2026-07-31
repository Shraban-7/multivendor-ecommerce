@extends('seller.layouts.app')
@section('title', 'Product Media')

@push('styles')
<style>
    .media-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 1rem; }
    .media-item { position: relative; border-radius: 10px; overflow: hidden; border: 2px solid #e9ecef; transition: border-color .2s, box-shadow .2s; background: #f8f9fa; }
    .media-item:hover { border-color: #0d6efd; box-shadow: 0 4px 12px rgba(13,110,253,.15); }
    .media-item.primary { border-color: #198754; }
    .media-item .ratio { cursor: grab; }
    .media-item:active .ratio { cursor: grabbing; }
    .media-item .overlay { position: absolute; inset: 0; background: rgba(0,0,0,.4); opacity: 0; transition: opacity .2s; display: flex; align-items: center; justify-content: center; gap: .4rem; z-index: 2; }
    .media-item:hover .overlay { opacity: 1; }
    .media-item .badge-primary { position: absolute; top: 6px; left: 6px; z-index: 3; font-size: .65rem; }
    .upload-zone { border: 2px dashed #dee2e6; border-radius: 12px; background: #f8f9fa; cursor: pointer; transition: all .2s; }
    .upload-zone:hover, .upload-zone.dragover { background: #e8f4fd; border-color: #0d6efd; }
    .preview-item { position: relative; animation: fadeIn .3s ease; }
    .preview-item .remove-btn { position: absolute; top: 4px; right: 4px; width: 24px; height: 24px; border-radius: 50%; background: rgba(255,255,255,.9); border: none; display: flex; align-items: center; justify-content: center; cursor: pointer; z-index: 10; }
    .preview-item .remove-btn:hover { background: #dc3545; color: #fff; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    .sortable-ghost { opacity: .4; }
    .sortable-chosen { border-color: #0d6efd !important; box-shadow: 0 0 0 3px rgba(13,110,253,.25); }
</style>
@endpush

@section('content')
@php
    $imgPlaceholder = '0';
    $setPrimaryTemplate = route('seller.products.media.setPrimary', [$product, $imgPlaceholder]);
    $destroyTemplate    = route('seller.products.media.destroy', [$product, $imgPlaceholder]);
    $replaceTemplate    = route('seller.products.media.replace', [$product, $imgPlaceholder]);
@endphp
<section class="bg-white rounded-sm shadow-sm overflow-hidden mb-3 relative">
    <div class="absolute top-0 left-0 right-0 h-1" style="background: linear-gradient(90deg, #a855f7, #c084fc, #e879f9);"></div>
    <div class="p-5 lg:p-6 pt-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="min-w-0">
                <nav class="flex items-center gap-1 mb-2 text-xs text-ink-tertiary">
                    <i data-lucide="package" class="text-[#a855f7]" style="width:12px;height:12px;"></i>
                    <a href="{{ route('seller.products.edit', $product->slug) }}" class="hover:text-ink-emphasis">{{ $product->name }}</a>
                    <i data-lucide="chevron-right" style="width:12px;height:12px;"></i>
                    <span class="text-ink-soft font-semibold">Media</span>
                </nav>
                <div class="flex flex-wrap items-center gap-2 mb-1">
                    <h1 class="text-xl font-bold text-ink-emphasis mb-0 truncate max-w-[420px]">Product Media</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-[#a855f7]/15 text-[#a855f7]">
                        <i data-lucide="gallery-horizontal" style="width:11px;height:11px;" class="me-1"></i> Gallery
                    </span>
                </div>
                <p class="text-sm text-ink-secondary mb-0 inline-flex flex-wrap gap-2 items-center">
                    <span>{{ $product->name }}</span>
                    <span class="text-ink-tertiary">·</span>
                    <span>SKU: <strong class="text-ink-emphasis">{{ $product->sku }}</strong></span>
                </p>
            </div>
            <a href="{{ route('seller.products.edit', $product->slug) }}" class="btn btn-light shrink-0">
                <i data-lucide="arrow-left" style="width:14px;height:14px;"></i> Back to Product
            </a>
        </div>
    </div>
</section>

<div class="grid grid-cols-1 gap-4">
    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
        <div class="bg-surface-muted px-4 py-2.5 border-b border-border flex items-center justify-between">
            <h5 class="font-bold mb-0 text-sm"><i data-lucide="upload-cloud" class="icon-xs me-1"></i> Upload Images</h5>
        </div>
        <div class="p-5">
            <form id="mediaUploadForm" enctype="multipart/form-data" method="POST"
                action="{{ route('seller.products.media.upload', $product) }}">
                @csrf
                <div class="upload-zone text-center p-5 relative mb-3" id="dropZone">
                    <input type="file" name="images[]" id="imageInput"
                        class="absolute top-0 left-0 w-full h-full opacity-0 cursor-pointer" multiple
                        accept="image/png, image/jpeg, image/jpg, image/webp">
                    <div class="pointer-events-none">
                        <i data-lucide="image" class="mb-2" style="width:48px;height:48px;color:#0d6efd;"></i>
                        <h6 class="font-bold mb-1">Click or Drag images here</h6>
                        <p class="text-ink-tertiary text-sm mb-0">Max 4MB per file &bull; JPG, PNG, WEBP &bull;
                            Auto-converted to WebP</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-2 mb-3" id="previewContainer"></div>
                <div class="flex justify-end">
                    <button type="submit" class="btn btn-primary"
                        id="uploadBtn" disabled>
                        <i data-lucide="upload" style="width:16px;height:16px;"></i> Upload Selected
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
        <div class="bg-surface-muted px-4 py-2.5 border-b border-border flex items-center justify-between">
            <h5 class="font-bold mb-0 text-sm"><i data-lucide="grid" class="icon-xs me-1"></i> Gallery ({{ $product->images->count() }})</h5>
            <span class="text-sm text-ink-tertiary">Drag to reorder</span>
        </div>
        <div class="p-5">
            @if ($product->images->count())
                <div class="media-grid" id="mediaGrid">
                    @foreach ($product->images->sortBy('position') as $image)
                        <div class="media-item {{ $image->is_primary ? 'primary' : '' }}" data-id="{{ $image->id }}"
                            data-position="{{ $image->position }}">
                            @if ($image->is_primary)
                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full bg-emerald-500 text-white badge-primary">Primary</span>
                            @endif
                            <div class="ratio ratio-1x1">
                                <img src="{{ $image->image_url }}" alt="Product Image" class="w-full h-full object-fit-cover"
                                    loading="lazy">
                            </div>
                            <div class="overlay">
                                @if (!$image->is_primary)
                                    <button type="button" class="btn btn-light btn-sm btn-round action-btn"
                                        data-action="primary" data-id="{{ $image->id }}" title="Set as Primary">
                                        <i data-lucide="star" style="width:16px;height:16px;color:#198754;"></i>
                                    </button>
                                @endif
                                <button type="button" class="btn btn-light btn-sm btn-round action-btn"
                                    data-action="replace" data-id="{{ $image->id }}" title="Replace">
                                    <i data-lucide="refresh-cw" style="width:16px;height:16px;color:#0d6efd;"></i>
                                </button>
                                <button type="button" class="btn btn-light btn-sm btn-round action-btn"
                                    data-action="delete" data-id="{{ $image->id }}" title="Delete">
                                    <i data-lucide="trash-2" style="width:16px;height:16px;color:#dc3545;"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <i data-lucide="image" style="width:48px;height:48px;color:#adb5bd;"></i>
                    <p class="text-ink-tertiary mt-2 mb-0">No images in the gallery yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<input type="file" id="replaceFileInput" class="hidden" accept="image/png, image/jpeg, image/jpg, image/webp">

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        const mediaRoutes = {
            setPrimary: @json($setPrimaryTemplate),
            destroy: @json($destroyTemplate),
            replace: @json($replaceTemplate),
            reorder: @json(route('seller.products.media.reorder', $product))
        };
        const ROUTE_PLACEHOLDER = '/0';

        document.addEventListener('DOMContentLoaded', function () {
            const uploadForm = document.getElementById('mediaUploadForm');
            const input = document.getElementById('imageInput');
            const dropZone = document.getElementById('dropZone');
            const previewContainer = document.getElementById('previewContainer');
            const uploadBtn = document.getElementById('uploadBtn');
            let previewIdCounter = 0;
            const MAX_FILES = 20;
            const MAX_SIZE_MB = 4;
            const MAX_SIZE_BYTES = MAX_SIZE_MB * 1024 * 1024;

            let dataTransfer = new DataTransfer();

            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, e => { e.preventDefault(); e.stopPropagation(); });
            });
            ['dragenter', 'dragover'].forEach(eventName => {
                dropZone.addEventListener(eventName, () => dropZone.classList.add('dragover'));
            });
            ['dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, () => dropZone.classList.remove('dragover'));
            });
            dropZone.addEventListener('drop', e => { handleFiles(e.dataTransfer.files); });
            input.addEventListener('change', e => { handleFiles(e.target.files); });

            function handleFiles(files) {
                const newFiles = Array.from(files);
                if (dataTransfer.items.length + newFiles.length > MAX_FILES) {
                    showErrorToast?.('Max ' + MAX_FILES + ' files') || alert('Max ' + MAX_FILES + ' files');
                    return;
                }
                newFiles.forEach(file => {
                    if (!file.type.startsWith('image/')) {
                        showErrorToast?.('"' + file.name + '" is not an image') || alert('"' + file.name + '" is not an image');
                        return;
                    }
                    if (file.size > MAX_SIZE_BYTES) {
                        showErrorToast?.('"' + file.name + '" exceeds ' + MAX_SIZE_MB + 'MB') || alert('"' + file.name + '" exceeds ' + MAX_SIZE_MB + 'MB');
                        return;
                    }
                    dataTransfer.items.add(file);
                    createPreview(file);
                });
                syncInput();
                updateButtonState();
            }

            function syncInput() {
                const newDT = new DataTransfer();
                for (let i = 0; i < dataTransfer.items.length; i++) {
                    newDT.items.add(dataTransfer.items[i].getAsFile());
                }
                dataTransfer = newDT;
                input.files = dataTransfer.files;
            }

            function createPreview(file) {
                const reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onloadend = function () {
                    const id = ++previewIdCounter;
                    const col = document.createElement('div');
                    col.className = 'col-6 col-md-3 col-lg-2 preview-item';
                    col.dataset.previewId = id;
                    col.innerHTML = '<div class="border rounded overflow-hidden position-relative shadow-sm bg-white">'
                        + '<div class="ratio ratio-1x1"><img src="' + reader.result + '" class="object-fit-cover w-100 h-100" alt="Preview"></div>'
                        + '<button type="button" class="remove-btn" data-preview-id="' + id + '"><i data-lucide="x" style="width:14px;height:14px;"></i></button>'
                        + '<div class="p-1 bg-white border-top text-truncate small text-center text-muted">' + escapeHtml(file.name) + '</div>'
                        + '</div>';
                    previewContainer.appendChild(col);
                    window.renderIcons && window.renderIcons();
                };
            }

            previewContainer.addEventListener('click', function (e) {
                const btn = e.target.closest('.remove-btn');
                if (!btn) return;
                const id = parseInt(btn.dataset.previewId);
                const items = dataTransfer.files;
                const newDT = new DataTransfer();
                let idx = 0;
                for (let i = 0; i < items.length; i++) {
                    if (++idx !== id) newDT.items.add(items[i]);
                }
                dataTransfer = newDT;
                syncInput();
                const el = previewContainer.querySelector('[data-preview-id="' + id + '"]');
                if (el) el.remove();
                updateButtonState();
            });

            function escapeHtml(str) {
                const div = document.createElement('div');
                div.appendChild(document.createTextNode(str));
                return div.innerHTML;
            }

            function updateButtonState() {
                const count = dataTransfer.files.length;
                if (count > 0) {
                    uploadBtn.removeAttribute('disabled');
                    uploadBtn.innerHTML = '<i data-lucide="upload" style="width:16px;height:16px;"></i> Upload ' + count + ' Image' + (count > 1 ? 's' : '');
                } else {
                    uploadBtn.setAttribute('disabled', 'true');
                    uploadBtn.innerHTML = '<i data-lucide="upload" style="width:16px;height:16px;"></i> Upload Selected';
                }
                window.renderIcons && window.renderIcons();
            }

            uploadForm.addEventListener('submit', function (e) {
                e.preventDefault();
                if (!dataTransfer.files.length) return;
                const formData = new FormData(this);
                const btn = this.querySelector('button[type="submit"]');
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span> Uploading...';

                fetch(this.action, {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                    body: formData,
                }).then(r => r.json()).then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        showErrorToast?.(data.message || 'Upload failed');
                        btn.disabled = false;
                        updateButtonState();
                    }
                }).catch(() => {
                    showErrorToast?.('Upload failed');
                    btn.disabled = false;
                    updateButtonState();
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            const grid = document.getElementById('mediaGrid');
            if (!grid) return;

            let sortable = new Sortable(grid, {
                animation: 200,
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',
                handle: '.ratio',
                onEnd: function () {
                    const order = Array.from(grid.children).map(el => parseInt(el.dataset.id));
                    fetch(mediaRoutes.reorder, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        },
                        body: JSON.stringify({ order }),
                    }).then(r => r.json()).then(data => {
                        if (!data.success) showErrorToast?.('Reorder failed');
                    });
                }
            });

            const replaceInput = document.getElementById('replaceFileInput');
            let replaceId = null;

            function urlFor(template, id) {
                return template.replace(ROUTE_PLACEHOLDER, '/' + id);
            }

            grid.addEventListener('click', function (e) {
                const btn = e.target.closest('.action-btn');
                if (!btn) return;

                const id = parseInt(btn.dataset.id);
                const action = btn.dataset.action;

                if (action === 'primary') {
                    fetch(urlFor(mediaRoutes.setPrimary, id), {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        },
                    }).then(r => r.json()).then(data => {
                        if (data.success) window.location.reload();
                        else showErrorToast?.(data.message || 'Failed');
                    });
                }

                if (action === 'delete') {
                    if (!confirm('Permanently delete this image?')) return;
                    fetch(urlFor(mediaRoutes.destroy, id), {
                        method: 'DELETE',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        },
                    }).then(r => r.json()).then(data => {
                        if (data.success) window.location.reload();
                        else showErrorToast?.(data.message || 'Delete failed');
                    });
                }

                if (action === 'replace') {
                    replaceId = id;
                    replaceInput.click();
                }
            });

            replaceInput.addEventListener('change', function () {
                if (!this.files.length || !replaceId) return;
                const formData = new FormData();
                formData.append('image', this.files[0]);
                formData.append('_token', document.querySelector('input[name="_token"]').value);

                fetch(urlFor(mediaRoutes.replace, replaceId), {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                    body: formData,
                }).then(r => r.json()).then(data => {
                    if (data.success) window.location.reload();
                    else showErrorToast?.(data.message || 'Replace failed');
                });
            });
        });
    </script>
@endpush