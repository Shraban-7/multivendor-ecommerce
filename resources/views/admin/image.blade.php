@extends('admin.layouts.app')
@section('title', 'Images')

@push('styles')
<link href="https://unpkg.com/cropperjs@1.5.13/dist/cropper.min.css" rel="stylesheet">
@endpush

@section('content')
<h4 class="mb-3">Images</h4>

<div id="alertBox"></div>

<div class="grid grid-cols-1 mb-3">
    <div class="col-span-1">
        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden p-5 mb-3">
            <form id="form" action="{{ route('admin.images.store') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <div class="grid grid-cols-1">
                    <div class="mb-3 col-span-full">
                        <label class="block text-xs font-medium text-ink-secondary mb-1">Upload Your Watermark</label>
                        <x-image-input name="watermark" />
                    </div>
                    <div class="mb-3 col-span-full">
                        <label class="block text-xs font-medium text-ink-secondary mb-1">Upload Your Images</label>
                        <input class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" name="images[]" type="file" multiple>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
            </form>
        </div>

        @if(count($watermarkedImages))
        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden p-5">
            <div class="flex justify-between items-center mb-3">
                <h5 class="mb-0">Watermarked Images</h5>
                @if(count($watermarkedImages))
                <form action="{{ route('admin.images.delete-all') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete all images?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm">Delete All</button>
                </form>
                @endif
            </div>

            <div style="max-height: 500px; overflow-y: auto;">
                <div class="grid grid-cols-1">
                    @foreach ($watermarkedImages as $image)
                    <div class="md:col-span-1 mb-4">
                        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
                            <img src="{{ asset('storage/' . $image) }}"
                                class="card-img-top"
                                alt="Watermarked Image"
                                style="height: 200px; object-fit: cover; width: 100%;">

                            <div class="px-5 py-3 border-t border-border bg-surface-muted text-center">
                                <a href="{{ asset('storage/' . $image) }}" download class="btn btn-light btn-sm btn-block">
                                    <i data-lucide="download" class="nav-icon icon-xs me-2"></i> Download</a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

    </div>

    <div class="col-span-1">

        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden p-5 mb-3">
            <form id="cropImageForm" enctype="multipart/form-data" method="POST">
                @csrf
                <div class="grid grid-cols-1">
                    <div class="mb-3 col-span-full">
                        <label class="block text-xs font-medium text-ink-secondary mb-1">Crop Your Image</label>
                        <x-image-input name="image" />
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
            </form>
        </div>

        @if(count($croppedImages))
        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden p-5">
            <div class="flex justify-between items-center mb-3">
                <h5 class="mb-0">Cropped Images</h5>
                @if(count($croppedImages))
                <form action="{{ route('admin.images.delete-cropped-image') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete all images?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm">Delete All</button>
                </form>
                @endif
            </div>

            <div style="max-height: 500px; overflow-y: auto;">
                <div class="grid grid-cols-1">
                    @foreach ($croppedImages as $image)
                    <div class="md:col-span-1 mb-4">
                        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
                            <img src="{{ asset('storage/' . $image) }}"
                                class="card-img-top"
                                alt="Cropped Image"
                                style="height: 200px; object-fit: cover; width: 100%;">

                            <div class="px-5 py-3 border-t border-border bg-surface-muted text-center">
                                <a href="{{ asset('storage/' . $image) }}" download class="btn btn-light btn-sm btn-block">
                                    <i data-lucide="download" class="nav-icon icon-xs me-2"></i> Download</a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

    </div>

</div>

<!-- Image Cropper Modal -->
<div class="modal fade" id="imageCropperModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Crop Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeImageCropperModalBtn"></button>
            </div>
            <div class="modal-body text-center">
                <input type="file" id="imageUploadInput" accept="image/*" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors mb-3">
                <img id="cropperImage" src="#" class="d-none img-fluid" style="max-height: 400px;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="cropImageBtn">Crop & Insert</button>
            </div>
        </div>
    </div>
</div>


@push('scripts')

<script src="https://unpkg.com/cropperjs@1.5.13/dist/cropper.min.js"></script>
<script>
    let cropper;
    let croppedBlob = null;

    const imageInputComponent = document.querySelector('input[name="image"]');
    const imagePreviewDiv = imageInputComponent.closest('.form-group').querySelector('.image-preview');
    const removeImageBtn = imageInputComponent.closest('.form-group').querySelector('.remove-image');

    const modal = new bootstrap.Modal(document.getElementById('imageCropperModal'));

    imagePreviewDiv.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        modal.show();
    });

    const imageInput = document.getElementById('imageUploadInput');
    const cropperImage = document.getElementById('cropperImage');
    const cropButton = document.getElementById('cropImageBtn');

    imageInput.addEventListener('change', function() {
        const file = this.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function(e) {
            cropperImage.src = e.target.result;
            cropperImage.classList.remove('d-none');

            if (cropper) cropper.destroy();
            cropper = new Cropper(cropperImage, {
                aspectRatio: 1,
                viewMode: 2,
                autoCropArea: 1,
                responsive: true
            });
        };
        reader.readAsDataURL(file);
    });

    cropButton.addEventListener('click', function() {
        if (cropper) {
            cropper.getCroppedCanvas().toBlob(function(blob) {
                croppedBlob = blob;

                const previewURL = URL.createObjectURL(blob);

                imagePreviewDiv.innerHTML = `<img src="${previewURL}" class="w-full h-full absolute top-0 left-0 object-fit-cover" style="z-index: 1;">`;
                removeImageBtn.classList.remove('d-none');

                // Convert blob to File and set it on original input
                const file = new File([blob], "cropped_image.png", {
                    type: 'image/png'
                });

                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                imageInputComponent.files = dataTransfer.files;

                modal.hide();
                imageInput.value = '';
                cropperImage.classList.add('d-none');
                if (cropper) cropper.destroy();
                cropper = null;
            }, 'image/png');
        }
    });

    document.getElementById('closeImageCropperModalBtn').addEventListener('click', () => {
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
        imageInput.value = '';
        cropperImage.classList.add('d-none');
    });

    cropImageForm.addEventListener('submit', function(e) {
        e.preventDefault();

        $('#alertBox').html('');

        if (!imageInputComponent.files.length) {
            alert('Please crop and insert an image first.');
            return;
        }

        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('image', imageInputComponent.files[0]);

        fetch("{{ route('admin.images.cropped-image') }}", {
                method: 'POST',
                body: formData
            })
            .then(res => {
                if (!res.ok) throw new Error('Upload failed');
                return res.text();
            })
            .then(data => {
                $('#alertBox').html(`<div class="p-4 rounded-sm bg-emerald-50 border border-emerald-200 text-feedback-success text-sm flex items-start gap-3 alert-dismissible fade show" role="alert">
                        Cropped image saved successfully!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>`);

                setTimeout(function() {
                    window.location.reload();
                }, 1500);
            })
            .catch(err => {
                console.error(err);
                alert('Error uploading image.');
            });
    });
</script>
@endpush

@endsection