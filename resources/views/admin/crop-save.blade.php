<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Professional Image Cropper</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://unpkg.com/cropperjs@1.5.13/dist/cropper.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
        }

        .bg-white border border-border rounded-sm shadow-sm overflow-hidden {
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        #modalImage {
            max-width: 100%;
            max-height: 400px;
            border-radius: 8px;
            border: 1px solid #dee2e6;
        }

        #croppedPreview {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            width: 100%;
            max-width: 500px;
        }

        .modal-body {
            padding: 1rem 2rem;
        }

        .modal-footer {
            padding: 1rem 2rem;
        }

        .modal-content {
            border-radius: 12px;
        }
    </style>
</head>

<?php
    $height = $width = 100;
?>

<body class="h-full">

    <div class="container py-5">
        <div class="grid grid-cols-1 justify-center">
            <div class="md:col-span-2 xl:col-span-1-6">
                <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
                    <div class="p-5 text-center">
                        <h4 class="mb-4">Upload & Crop Product Image</h4>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#cropperModal">
                            Upload & Crop
                        </button>
                        <div class="mt-5">
                            <h5 class="text-ink-tertiary mb-3">Cropped Image Preview</h5>
                            <canvas id="croppedPreview" height="{{ $height }}"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cropper Modal -->
    <div class="modal fade" id="cropperModal" tabindex="-1" aria-labelledby="cropperModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title">Crop Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeModalBtn"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="uploadImage" class="block text-xs font-medium text-ink-secondary mb-1">Select an Image</label>
                        <input type="file" id="uploadImage" accept="image/*" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                    </div>
                    <div class="flex justify-center items-center">
                        <img id="modalImage" src="#" alt="Image Preview" class="d-none">
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button id="cropButton" class="btn btn-success">Crop & Insert</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/cropperjs@1.5.13/dist/cropper.min.js"></script>

    <script>
        let cropper;
        const uploadImage = document.getElementById('uploadImage');
        const modalImage = document.getElementById('modalImage');
        const cropButton = document.getElementById('cropButton');
        const croppedCanvas = document.getElementById('croppedPreview');
        const closeModalBtn = document.getElementById('closeModalBtn');

        const HEIGHT = "{{ $height }}";
        const WIDTH = "{{ $width }}";

        uploadImage.addEventListener('change', function() {
            const file = this.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(e) {
                modalImage.src = e.target.result;
                modalImage.classList.remove('d-none');

                modalImage.onload = function() {
                    if (cropper) cropper.destroy();
                    cropper = new Cropper(modalImage, {
                        aspectRatio: 1,
                        viewMode: 2,
                        autoCropArea: 1,
                        responsive: true
                    });
                };
            };
            reader.readAsDataURL(file);
        });

        cropButton.addEventListener('click', function() {
            if (cropper) {
                const canvas = cropper.getCroppedCanvas({
                    width: HEIGHT,
                    height: WIDTH
                });

                canvas.toBlob(function(blob) {
                    const formData = new FormData();
                    formData.append('croppedImage', blob, 'cropped.png');

                    fetch("{{ route('upload.save') }}", {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            showSuccessToast('Image uploaded');
                            console.log('Saved image:', data);
                        })
                        .catch(error => console.error('Upload failed:', error));


                    const ctx = croppedCanvas.getContext('2d');
                    ctx.clearRect(0, 0, croppedCanvas.width, croppedCanvas.height);
                    croppedCanvas.width = HEIGHT;
                    croppedCanvas.height = WIDTH;
                    const tempImg = new Image();
                    tempImg.onload = () => ctx.drawImage(tempImg, 0, 0, HEIGHT, WIDTH);
                    tempImg.src = URL.createObjectURL(blob);

                    bootstrap.Modal.getInstance(document.getElementById('cropperModal')).hide();
                    uploadImage.value = '';
                    modalImage.classList.add('d-none');
                    cropper.destroy();
                    cropper = null;
                }, 'image/png');
            }
        });

        closeModalBtn.addEventListener('click', function() {
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
            uploadImage.value = '';
            modalImage.src = '';
            modalImage.classList.add('d-none');
        });
    </script>
</body>

</html>