<style>
    .border-dashed { border-style: dashed !important; }
    .upload-zone:hover, .upload-zone.dragover { background-color: var(--bs-light-primary) !important; border-color: var(--bs-primary) !important; }
    .preview-item { position: relative; animation: fadeIn 0.3s ease; }
    .preview-remove { position: absolute; top: 5px; right: 5px; background: rgba(255,255,255,0.9); border: none; border-radius: 50%; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; cursor: pointer; z-index: 10; }
    .preview-remove:hover { background: var(--bs-danger); color: white; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
</style>

<div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0 fw-bold text-dark">Product Gallery</h5>
            <small class="text-muted">Manage your product visuals</small>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('seller.products.media.index', $product) }}" class="btn btn-outline-primary btn-sm d-inline-flex align-items-center gap-1">
                <i data-feather="grid" style="width:14px;height:14px;"></i> Full Gallery
            </a>
            <button type="button" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#uploadModal">
                <i data-feather="upload-cloud" style="width:14px;height:14px;"></i> Upload
            </button>
        </div>
    </div>
    <div class="card-body">
        @if ($product->images->count())
        <div class="row g-3" id="current-gallery">
            @foreach ($product->images()->ordered()->get() as $image)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="position-relative border rounded overflow-hidden shadow-sm">
                    <div class="ratio ratio-1x1">
                        <img src="{{ storage_url($image->image) }}" alt="Product Image" class="w-100 h-100 object-fit-cover" loading="lazy">
                    </div>
                    @if ($image->is_primary)
                    <span class="position-absolute top-0 start-0 badge bg-success m-1" style="font-size:.6rem;z-index:2;">Primary</span>
                    @endif
                    <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-dark bg-opacity-50 opacity-0 transition-all hover-opacity-100" style="z-index:1;">
                        <form action="{{ route('seller.products.image.delete', $image->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-light btn-sm rounded-circle shadow-sm" title="Delete Image" onclick="return confirm('Permanently delete this image?')">
                                <i data-feather="trash-2" style="width:14px;height:14px;color:#dc3545;"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-5 bg-light rounded-3 border border-dashed">
            <i data-feather="image" style="width:48px;height:48px;color:#adb5bd;"></i>
            <p class="text-muted mb-0 mt-2">No images added yet.</p>
            <button class="btn btn-link text-decoration-none fw-semibold text-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
                Upload your first image
            </button>
        </div>
        @endif
    </div>
</div>

<div class="modal fade" id="uploadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold">Upload Gallery Images</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('seller.products.uploadImages') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <div class="modal-body">
                    <div class="upload-zone text-center p-5 border-2 border-dashed rounded-3 bg-light cursor-pointer position-relative mb-3" id="dropZone">
                        <input type="file" name="images[]" id="imageInput" class="position-absolute top-0 start-0 w-100 h-100 opacity-0 cursor-pointer" multiple accept="image/png, image/jpeg, image/jpg, image/webp">
                        <div class="pointer-events-none">
                            <i data-feather="upload-cloud" class="mb-3" style="width:48px;height:48px;color:#0d6efd;"></i>
                            <h6 class="fw-bold mb-1">Click or Drag images here</h6>
                            <p class="text-muted small mb-0">Max 4MB per file &bull; JPG, PNG, WEBP &bull; Auto-converted to WebP</p>
                        </div>
                    </div>
                    <div class="row g-2" id="previewContainer"></div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4 d-inline-flex align-items-center gap-1" id="uploadBtn" disabled>
                        <i data-feather="upload" style="width:16px;height:16px;"></i> Upload Selected
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
                    <button type="button" class="preview-remove shadow-sm" data-preview-id="${id}"><i data-feather="x" style="width:14px;height:14px;"></i></button>
                    <div class="p-1 bg-white border-top text-truncate small text-center text-muted">${escapeHtml(file.name)}</div>
                </div>`;
                previewContainer.appendChild(col);
                feather.replace();
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
                uploadBtn.innerHTML = `<i data-feather="upload" style="width:16px;height:16px;"></i> Upload ${count} Image${count > 1 ? 's' : ''}`;
            } else {
                uploadBtn.setAttribute('disabled', 'true');
                uploadBtn.innerHTML = `<i data-feather="upload" style="width:16px;height:16px;"></i> Upload Selected`;
            }
            feather.replace();
        }
    });
</script>
