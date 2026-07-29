<style>
    .border-dashed { border-style: dashed !important; }
    .upload-zone:hover, .upload-zone.dragover { background-color: #FFF1EA !important; border-color: #F85606 !important; }
    .preview-item { position: relative; animation: fadeIn 0.3s ease; }
    .preview-remove { position: absolute; top: 5px; right: 5px; background: rgba(255,255,255,0.9); border: none; border-radius: 50%; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; cursor: pointer; z-index: 10; }
    .preview-remove:hover { background: #D93025; color: white; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
</style>

<div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
    <div class="bg-surface-muted px-4 py-2.5 border-b border-border flex items-center justify-between">
        <div>
            <h5 class="mb-0 font-bold text-sm">Product Gallery</h5>
            <small class="text-ink-tertiary">Manage your product visuals</small>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('seller.products.media.index', $product) }}" class="btn btn-outline-primary btn-sm">
                <i data-lucide="grid" style="width:14px;height:14px;"></i> Full Gallery
            </a>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#uploadModal">
                <i data-lucide="upload-cloud" style="width:14px;height:14px;"></i> Upload
            </button>
        </div>
    </div>
    <div class="p-5">
        @if ($product->images->count())
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3" id="current-gallery">
                @foreach ($product->images()->ordered()->get() as $image)
                    <div class="group relative bg-surface-muted border border-border rounded-sm overflow-hidden aspect-square">
                        <img src="{{ storage_url($image->image) }}" alt="Product Image" class="w-full h-full object-cover" loading="lazy">
                        @if ($image->is_primary)
                            <span class="absolute top-2 left-2 inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full bg-emerald-500 text-white shadow-sm">
                                <i data-lucide="star" class="icon-xs me-1" style="width:12px;height:12px;"></i> Primary
                            </span>
                        @endif
                        <div class="absolute inset-0 bg-ink bg-opacity-60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                            <form action="{{ route('seller.products.image.delete', $image->id) }}" method="POST" class="inline" onsubmit="return confirm('Permanently delete this image?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-light btn-sm btn-round" title="Delete Image">
                                    <i data-lucide="trash-2" style="width:14px;height:14px; color: #dc3545;"></i>
                                </button>
                            </form>
                        </div>
                        <div class="absolute bottom-0 left-0 right-0 px-2 py-1 bg-ink bg-opacity-70 text-white text-xs truncate opacity-0 group-hover:opacity-100 transition-opacity">
                            Position #{{ $image->position }}
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 bg-surface-muted rounded-md border border-dashed">
                <i data-lucide="image" style="width:48px;height:48px;color:#adb5bd;"></i>
                <p class="text-ink-tertiary mb-1 mt-2">No images added yet.</p>
                <p class="text-ink-tertiary text-sm mb-3">JPG, PNG, WEBP &bull; Max 4MB per file &bull; Auto-converted to WebP</p>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#uploadModal">
                    <i data-lucide="upload-cloud" style="width:14px;height:14px;"></i> Upload your first image
                </button>
            </div>
        @endif
    </div>
</div>

<div class="modal fade" id="uploadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-b border-border">
                <h5 class="modal-title font-bold">Upload Gallery Images</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('seller.products.uploadImages') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <div class="modal-body">
                    <div class="upload-zone text-center p-5 border-2 border-dashed rounded-md bg-surface-muted cursor-pointer relative mb-3" id="dropZone">
                        <input type="file" name="images[]" id="imageInput" class="absolute top-0 left-0 w-full h-full opacity-0 cursor-pointer" multiple accept="image/png, image/jpeg, image/jpg, image/webp">
                        <div class="pointer-events-none">
                            <i data-lucide="upload-cloud" class="mb-3" style="width:48px;height:48px;color:#0d6efd;"></i>
                            <h6 class="font-bold mb-1">Click or Drag images here</h6>
                            <p class="text-ink-tertiary text-sm mb-0">Max 4MB per file &bull; JPG, PNG, WEBP &bull; Auto-converted to WebP</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2" id="previewContainer"></div>
                </div>
                <div class="modal-footer border-t border-border">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="uploadBtn" disabled>
                        <i data-lucide="upload" style="width:16px;height:16px;"></i> Upload Selected
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
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
                    showErrorToast?.('Only image files allowed.') || alert('Only image files allowed.');
                    return;
                }
                if (file.size > MAX_SIZE_BYTES) {
                    showErrorToast?.('File exceeds ' + MAX_SIZE_MB + 'MB') || alert('File exceeds ' + MAX_SIZE_MB + 'MB');
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
            reader.onloadend = function() {
                const id = ++previewIdCounter;
                const col = document.createElement('div');
                col.className = 'relative aspect-square border border-border rounded-sm overflow-hidden bg-surface-muted';
                col.dataset.previewId = id;
                col.innerHTML = '<img src="' + reader.result + '" class="w-full h-full object-cover" alt="Preview">'
                    + '<button type="button" class="preview-remove" data-preview-id="' + id + '"><i data-lucide="x" style="width:14px;height:14px;"></i></button>'
                    + '<div class="absolute bottom-0 left-0 right-0 px-2 py-1 bg-white border-top text-xs text-center text-ink-tertiary truncate">' + escapeHtml(file.name) + '</div>';
                previewContainer.appendChild(col);
                window.renderIcons && window.renderIcons();
            };
        }

        function escapeHtml(str) {
            const div = document.createElement('div');
            div.appendChild(document.createTextNode(str));
            return div.innerHTML;
        }

        previewContainer.addEventListener('click', function(e) {
            const btn = e.target.closest('.preview-remove');
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
    });
</script>
