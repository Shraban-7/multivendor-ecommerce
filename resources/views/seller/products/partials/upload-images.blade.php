<style>
    .border-dashed { border-style: dashed !important; }
    .upload-zone:hover, .upload-zone.dragover { background-color: var(--bs-light-primary) !important; border-color: var(--bs-primary) !important; }
    .preview-item { position: relative; animation: fadeIn 0.3s ease; }
    .preview-remove { position: absolute; top: 5px; right: 5px; background: rgba(255,255,255,0.9); border: none; border-radius: 50%; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; cursor: pointer; z-index: 10; }
    .preview-remove:hover { background: var(--bs-danger); color: white; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
</style>

<div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 mb-4" style="border-radius: 12px;">
    <div class="px-5 py-4 border-b border-border bg-white flex items-center justify-between py-3">
        <div>
            <h5 class="mb-0 font-bold text-ink">Product Gallery</h5>
            <small class="text-ink-tertiary">Manage your product visuals</small>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('seller.products.media.index', $product) }}" class="btn btn-outline-primary">
                <i data-lucide="grid" style="width:14px;height:14px;"></i> Full Gallery
            </a>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#uploadModal">
                <i data-lucide="upload-cloud" style="width:14px;height:14px;"></i> Upload
            </button>
        </div>
    </div>
    <div class="p-5">
        @if ($product->images->count())
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3" id="current-gallery">
            @foreach ($product->images()->ordered()->get() as $image)
            <div>
                <div class="relative border rounded-xs overflow-hidden shadow-sm">
                    <div class="aspect-square">
                        <img src="{{ storage_url($image->image) }}" alt="Product Image" class="w-full h-full object-cover" loading="lazy">
                    </div>
                    @if ($image->is_primary)
                    <span class="absolute top-0 left-0 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-feedback-success m-1" style="font-size:.6rem;z-index:2;">Primary</span>
                    @endif
                    <div class="absolute top-0 left-0 w-full h-full flex items-center justify-center bg-ink bg-opacity-50 opacity-0 transition-all hover-opacity-100" style="z-index:1;">
                        <form action="{{ route('seller.products.image.delete', $image->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-light btn-sm btn-round" title="Delete Image" onclick="return confirm('Permanently delete this image?')">
                                <i data-lucide="trash-2" style="width:14px;height:14px;color:#dc3545;"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-5 bg-surface-muted rounded-md border border-dashed">
            <i data-lucide="image" style="width:48px;height:48px;color:#adb5bd;"></i>
            <p class="text-ink-tertiary mb-0 mt-2">No images added yet.</p>
            <button class="inline-flex items-center text-sm text-brand hover:text-brand-deep transition-colors no-underline font-semibold" data-bs-toggle="modal" data-bs-target="#uploadModal">
                Upload your first image
            </button>
        </div>
        @endif
    </div>
</div>

<div class="modal fade" id="uploadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-b-0 pb-0">
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
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2" id="previewContainer"></div>
                </div>
                <div class="modal-footer border-t-0 pt-0">
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
            if (dataTransfer.items.length + newFiles.length > MAX_FILES) { showErrorToast?.(`Max ${MAX_FILES} files`) || alert(`Max ${MAX_FILES} files`); return; }
            newFiles.forEach(file => {
                if (!file.type.startsWith('image/')) { showErrorToast?.('Only image files allowed.') || alert('Only image files allowed.'); return; }
                if (file.size > MAX_SIZE_BYTES) { showErrorToast?.(`File exceeds ${MAX_SIZE_MB}MB`) || alert(`File exceeds ${MAX_SIZE_MB}MB`); return; }
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
                col.className = 'col-6 col-md-4 preview-item';
                col.dataset.previewId = id;
                col.innerHTML = `<div class="border rounded overflow-hidden position-relative shadow-sm bg-white">
                    <div class="ratio ratio-1x1"><img src="${reader.result}" class="object-fit-cover w-100 h-100" alt="Preview"></div>
                    <button type="button" class="preview-remove shadow-sm" data-preview-id="${id}"><i data-lucide="x" style="width:14px;height:14px;"></i></button>
                    <div class="p-1 bg-white border-top text-truncate small text-center text-muted">${escapeHtml(file.name)}</div>
                </div>`;
                previewContainer.appendChild(col);
                window.renderIcons && window.renderIcons();
            }
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
            const el = previewContainer.querySelector(`[data-preview-id="${id}"]`);
            if (el) el.remove();
            updateButtonState();
        });

        function updateButtonState() {
            const count = dataTransfer.files.length;
            if (count > 0) {
                uploadBtn.removeAttribute('disabled');
                uploadBtn.innerHTML = `<i data-lucide="upload" style="width:16px;height:16px;"></i> Upload ${count} Image${count > 1 ? 's' : ''}`;
            } else {
                uploadBtn.setAttribute('disabled', 'true');
                uploadBtn.innerHTML = `<i data-lucide="upload" style="width:16px;height:16px;"></i> Upload Selected`;
            }
            window.renderIcons && window.renderIcons();
        }
    });
</script>
