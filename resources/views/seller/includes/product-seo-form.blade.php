<div class="card mb-3">
    <div class="card-header bg-white">
        <h5 class="mb-0">SEO & Social Share Settings</h5>
    </div>

    <div class="card-body">
        <form id="productSeoForm" enctype="multipart/form-data">
            <h5 class="mb-3">Meta Information (Search Engines)</h5>

            <div class="mb-3">
                <label class="form-label">Meta Title
                    <small class="text-muted">(max 70 characters)</small>
                </label>
                <input type="text" name="meta_title" maxlength="70" class="form-control"
                    placeholder="e.g. Red Cotton T-Shirt – Buy Online">
            </div>

            <div class="mb-3">
                <label class="form-label">Meta Description
                    <small class="text-muted">(recommended up to 160 characters)</small>
                </label>
                <textarea name="meta_description" maxlength="160" rows="3" class="form-control"
                    placeholder="Short, keyword-rich description shown in Google results."></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Meta Keywords
                    <small class="text-muted">(comma separated)</small>
                </label>
                <input type="text" name="meta_keywords" maxlength="255" class="form-control"
                    placeholder="e.g. t-shirt, red cotton shirt, mens fashion">
                <small class="text-muted d-block mt-1">
                    *Keywords are optional; modern search engines rely more on content.
                </small>
            </div>

            <hr class="my-4">

            <!-- Open Graph Section -->
            <h5 class="mb-3">Open Graph (Social Media Preview)</h5>
            <p class="small text-muted">
                These fields control how the product appears when shared on Facebook, WhatsApp,
                LinkedIn, etc. If left blank, the Meta Title/Description will be used.
            </p>

            <div class="mb-3">
                <label class="form-label">OG Title
                    <small class="text-muted">(max 70 characters)</small>
                </label>
                <input type="text" name="og_title" maxlength="70" class="form-control"
                    placeholder="Catchy title for social sharing">
            </div>

            <div class="mb-3">
                <label class="form-label">OG Description
                    <small class="text-muted">(recommended up to 160 characters)</small>
                </label>
                <textarea name="og_description" maxlength="160" rows="3" class="form-control"
                    placeholder="Appears below the title when shared on social media."></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">OG Image</label>
                <input type="file" name="og_image" class="form-control">
                <small class="text-muted d-block mt-1">
                    Recommended size: <strong>1200 × 630 px</strong>, JPG/PNG/WebP, max 2 MB.
                    This image will be shown as the preview when the link is shared.
                </small>
            </div>

            <div class="text-end">
                <button type="button" id="seoUpdateBtn" class="btn btn-primary">
                    Save SEO Settings
                </button>
            </div>

        </form>
    </div>
</div>