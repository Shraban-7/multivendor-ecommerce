<style>
    .border-dashed {
        border-style: dashed !important;
    }
    .cursor-pointer {
        cursor: pointer;
    }
    .object-fit-cover {
        object-fit: cover;
    }
    .hover-opacity-100:hover {
        opacity: 1 !important;
    }
    .transition-all {
        transition: all 0.2s ease-in-out;
    }
    .upload-zone:hover,
    .upload-zone.dragover {
        background-color: #e9ecef !important;
        border-color: #0d6efd !important;
    }
    .preview-item {
        position: relative;
        animation: fadeIn 0.3s ease;
    }

    .preview-remove {
        position: absolute;
        top: 5px;
        right: 5px;
        background: rgba(255, 255, 255, 0.9);
        border: none;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background 0.2s;
        z-index: 10;
    }

    .preview-remove:hover {
        background: #dc3545;
        color: white;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
        <div>
            <h5 class="mb-0 fw-bold text-dark">Product Gallery</h5>
            <small class="text-muted">Manage your product visuals</small>
        </div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
            <i class="bi bi-cloud-upload me-2"></i>Upload Images
        </button>
    </div>

    <div class="card-body">
        @if ($product->images->count())
        <div class="row g-3" id="current-gallery">
            @foreach ($product->images as $image)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="gallery-item position-relative border rounded overflow-hidden shadow-sm group-hover">
                    <div class="ratio ratio-1x1">
                        <img src="{{ storage_url($image->image) }}" alt="Product Image" class="w-100 h-100 object-fit-cover">
                    </div>
                    <div class="gallery-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-dark bg-opacity-50 opacity-0 hover-opacity-100 transition-all">
                        <form action="{{ route('seller.products.image.delete', $image->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="btn btn-light btn-sm rounded-circle shadow-sm"
                                title="Delete Image"
                                onclick="return confirm('Permanently delete this image?')">
                                <i class="bi bi-trash text-danger"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-5 bg-light rounded-3 border border-dashed">
            <i class="bi bi-images display-4 text-muted mb-3"></i>
            <p class="text-muted mb-0">No images added yet.</p>
            <button class="btn btn-link text-decoration-none" data-bs-toggle="modal" data-bs-target="#uploadModal">
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
                    <!-- Upload Zone -->
                    <div class="upload-zone text-center p-5 border-2 border-dashed rounded-3 bg-light cursor-pointer position-relative mb-3" id="dropZone">
                        <input type="file"
                            name="images[]"
                            id="imageInput"
                            class="position-absolute top-0 start-0 w-100 h-100 opacity-0 cursor-pointer"
                            multiple
                            accept="image/png, image/jpeg, image/jpg, image/webp">

                        <div class="pointer-events-none">
                            <i class="bi bi-cloud-arrow-up display-3 text-primary mb-3"></i>
                            <h6 class="fw-bold mb-1">Click or Drag images here</h6>
                            <p class="text-muted small mb-0">
                                Max 5 images • Max 4MB per file • JPG, PNG, WEBP
                            </p>
                        </div>
                    </div>
                    <div class="row g-2" id="previewContainer">
                    </div>
                </div>

                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4" id="uploadBtn" disabled>
                        <i class="bi bi-upload me-2"></i>Upload Selected
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

        //use a DataTransfer object to manipulate the files associated with the input
        let dataTransfer = new DataTransfer();

        // Max constraints
        const MAX_FILES = 5;
        const MAX_SIZE_MB = 4;
        const MAX_SIZE_BYTES = MAX_SIZE_MB * 1024 * 1024;

        // Drag and Drop Visuals
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => dropZone.classList.add('dragover'), false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => dropZone.classList.remove('dragover'), false);
        });

        dropZone.addEventListener('drop', handleDrop, false);
        input.addEventListener('change', handleFiles, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            handleFiles({
                target: {
                    files: files
                }
            });
        }

        function handleFiles(e) {
            const newFiles = Array.from(e.target.files);
            let hasErrors = false;
            let errorMsg = '';

            // Check if adding these files exceeds the limit
            if (dataTransfer.items.length + newFiles.length > MAX_FILES) {
                showErrorToast(`You can only upload a maximum of ${MAX_FILES} images at a time.`);
                return;
            }

            newFiles.forEach(file => {
                // Validate Type
                if (!file.type.startsWith('image/')) {
                    hasErrors = true;
                    errorMsg = 'Only image files are allowed.';
                    return;
                }

                // Validate Size
                if (file.size > MAX_SIZE_BYTES) {
                    hasErrors = true;
                    errorMsg = `File "${file.name}" exceeds the ${MAX_SIZE_MB}MB limit.`;
                    return;
                }

                // Add to DataTransfer object (our "state")
                dataTransfer.items.add(file);

                // Create Preview
                createPreview(file);
            });

            if (hasErrors) {
                showErrorToast(errorMsg);
            }

            // Update the actual input with the valid files
            input.files = dataTransfer.files;
            updateButtonState();
        }

        function createPreview(file) {
            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onloadend = function() {
                const col = document.createElement('div');
                col.className = 'col-6 col-md-4 preview-item';

                // Generate unique ID based on file props to identify for removal
                const fileId = `${file.name}-${file.size}`;
                col.setAttribute('data-id', fileId);

                col.innerHTML = `
                <div class="border rounded overflow-hidden position-relative shadow-sm bg-white">
                    <div class="ratio ratio-1x1">
                        <img src="${reader.result}" class="object-fit-cover w-100 h-100" alt="Preview">
                    </div>
                    <button type="button" class="preview-remove shadow-sm" onclick="removeFile('${file.name}', ${file.size})">
                        <i class="bi bi-x"></i>
                    </button>
                    <div class="p-1 bg-white border-top text-truncate small text-center text-muted">
                        ${file.name}
                    </div>
                </div>
            `;
                previewContainer.appendChild(col);
            }
        }

        // Exposed globally so the HTML onclick can access it
        window.removeFile = function(fileName, fileSize) {
            const newDataTransfer = new DataTransfer();

            // Filter out the file to be deleted
            Array.from(dataTransfer.files).forEach(file => {
                if (file.name !== fileName || file.size !== fileSize) {
                    newDataTransfer.items.add(file);
                }
            });

            // Update state
            dataTransfer = newDataTransfer;
            input.files = dataTransfer.files;
            
            const fileId = `${fileName}-${fileSize}`;
            const el = document.querySelector(`.preview-item[data-id="${fileId}"]`);
            if (el) el.remove();

            updateButtonState();
        }

        function updateButtonState() {
            if (input.files.length > 0) {
                uploadBtn.removeAttribute('disabled');
                uploadBtn.innerHTML = `<i class="bi bi-upload me-2"></i>Upload ${input.files.length} Image${input.files.length > 1 ? 's' : ''}`;
            } else {
                uploadBtn.setAttribute('disabled', 'true');
                uploadBtn.innerHTML = `<i class="bi bi-upload me-2"></i>Upload Selected`;
            }
        }
    });
</script>