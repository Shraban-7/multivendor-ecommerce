<link href="{{ asset('assets/libs/cropperjs/cropper.min.css') }}" rel="stylesheet">

<style>
    #cropperModal .modal-dialog {
        max-width: 600px;
        max-height: 85vh;
    }

    #cropperModal .modal-body {
        display: flex;
        justify-content: center;
        align-items: center;
        max-height: 60vh;
        overflow: hidden;
        padding: 0.5rem;
    }

    #cropperModal .img-container {
        width: 100%;
        height: 100%;
        max-height: 60vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    #cropperModal .img-container img {
        max-width: 100%;
        max-height: 60vh;
        object-fit: contain;
    }
</style>

<div class="modal fade" id="cropperModal" tabindex="-1" aria-labelledby="cropperModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cropperModalLabel">Crop Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="img-container">
                    <img id="image" src="" alt="Image to crop" />
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="downloadCropped"><i class="bi bi-download me-2"></i>Download</button>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('assets/libs/cropperjs/cropper.min.js') }}"></script>

<script>
    const inputImage = document.getElementById("inputImage");
    const image = document.getElementById("image");
    const modal = new bootstrap.Modal(document.getElementById("cropperModal"));
    const downloadBtn = document.getElementById("downloadCropped");
    let cropper;

    inputImage.addEventListener("change", (e) => {
        const file = e.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = () => {
            image.src = reader.result;
            modal.show();
        };
        reader.readAsDataURL(file);
    });

    document.getElementById("cropperModal").addEventListener("shown.bs.modal", () => {
        cropper = new Cropper(image, {
            aspectRatio: 1.1,
            viewMode: 1,
            dragMode: "move",
            background: false,
            autoCropArea: 1,
            responsive: true,
        });
    });

    document.getElementById("cropperModal").addEventListener("hidden.bs.modal", () => {
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
        inputImage.value = "";
    });

    downloadBtn.addEventListener("click", () => {
        if (!cropper) return;

        const canvas = cropper.getCroppedCanvas({
            width: 300,
            height: 300 / 1.1,
        });
        const link = document.createElement("a");
        link.download = `cropped-${Date.now()}.png`;
        link.href = canvas.toDataURL("image/png");
        link.click();
        modal.hide();
    });
</script>