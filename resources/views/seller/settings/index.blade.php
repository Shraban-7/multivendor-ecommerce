@extends('seller.layouts.app')

@section('title', 'Business Settings')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0 text-dark">Business Settings</h4>
    </div>

    <div class="row">
        <div class="col-md-8 col-12">
            <div class="card border-0 shadow-sm card-body" style="border-radius: 12px;">
                <form id="businessSettingsForm" action="{{ route('seller.settings.update') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="business_name" class="form-label">Business Name</label>
                            <input type="text" class="form-control" id="business_name" name="business_name"
                                value="{{ old('business_name', $seller->business_name) }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="business_email" class="form-label">Business Email</label>
                            <input type="email" class="form-control" id="business_email" name="business_email"
                                value="{{ old('business_email', $seller->business_email) }}" required>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="business_address" class="form-label">Business Address</label>
                            <textarea name="business_address" id="business_address" class="form-control" rows="2">{{ old('business_address', $seller->business_address) }}</textarea>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="shipping_cost" class="form-label">Shipping Cost</label>
                            <input type="number" class="form-control" id="shipping_cost" name="shipping_cost"
                                value="{{ old('shipping_cost', $seller->shipping_cost) }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Business Logo</label>
                            <x-image-input name="business_logo" :image="storage_url($seller->business_logo)" />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Cover Photo</label>
                            <x-image-input name="cover_image" :image="storage_url($seller->cover_image)" />
                        </div>

                    </div>

                    <button type="submit" class="btn btn-primary mt-3 d-inline-flex align-items-center gap-1">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            const $form = $("#businessSettingsForm");
            const $submitBtn = $form.find("button[type='submit']");
            const $fileInput = $("#files");
            const $previewContainer = $("#banner-images");


            $fileInput.on("change", function() {
                const file = this.files[0];

                if (!file) return;

                $previewContainer.empty();

                const reader = new FileReader();

                reader.onload = function(e) {
                    const html = `
                        <div class="col-md-3 mb-3 preview-item">
                            <div class="position-relative">
                                <img src="${e.target.result}" class="img-fluid rounded" alt="Preview">
                                <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2 remove-preview">
                                    <i data-feather="trash"></i>
                                </button>
                            </div>
                        </div>
                    `;

                    $previewContainer.append(html);

                    feather.replace();
                };

                reader.readAsDataURL(file);
            });

            $(document).on("click", ".remove-preview", function() {
                const index = $(this).closest(".preview-item").data("index");
                const dt = new DataTransfer();
                const files = Array.from($fileInput[0].files);
                files.forEach((f, i) => {
                    if (i !== index) dt.items.add(f);
                });
                $fileInput[0].files = dt.files;
                $(this).closest(".preview-item").remove();
            });

            $(document).on("click", ".delete-banner", function() {
                const id = $(this).data("id");
                const $banner = $("#banner-" + id);
                if (!confirm("Are you sure you want to delete this banner image?")) return;

                $.ajax({
                    url: `/seller/banner-image/${id}`,
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    beforeSend: function() {
                        $banner.css("opacity", "0.5");
                    },
                    success: function(res) {
                        showSuccessToast(res.message || "Image deleted successfully!");
                        $banner.fadeOut(300, function() {
                            $(this).remove();
                        });
                    },
                    error: function() {
                        showErrorToast("Something went wrong while deleting image.");
                        $banner.css("opacity", "1");
                    }
                });
            });

            $form.on("submit", function(e) {
                e.preventDefault();
                $submitBtn.prop("disabled", true).text("Saving...");
                const fd = new FormData(this);

                $.ajax({
                    url: $form.attr("action"),
                    type: "POST",
                    data: fd,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        showSuccessToast(res.message || "Settings updated successfully!");
                        $submitBtn.prop("disabled", false).text("Save Changes");
                    },
                    error: function(xhr) {
                        $submitBtn.prop("disabled", false).text("Save Changes");
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors || {};
                            let msg = [];
                            $.each(errors, function(field, arr) {
                                const name = field.replace(/_/g, ' ').replace(/\b\w/g,
                                    c => c.toUpperCase());
                                msg.push(`<strong>${name}:</strong> ${arr.join(', ')}`);
                            });
                            showErrorToast(msg.join('<br>'))
                        } else {
                            showErrorToast("Something went wrong!");
                        }
                    }
                });
            });
        });
    </script>
@endpush
