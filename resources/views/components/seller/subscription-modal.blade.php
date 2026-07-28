<div class="modal fade" id="upgradeModal" tabindex="-1" aria-labelledby="upgradeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold d-flex align-items-center" id="upgradeModalLabel">
                    <i class="bi bi-stars me-2 fs-3 text-warning"></i> Upgrade to Pro
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center px-4">
                <div class="p-3 mb-3 rounded-4" style="background: linear-gradient(135deg, #FFF1EA, #fff);">
                    <i class="bi bi-gem fs-1 text-primary"></i>
                </div>
                <p class="text-muted mb-4">
                    You're currently using a limited plan. Unlock premium seller features,
                    boost visibility, and grow faster by upgrading your subscription.
                </p>
                <div class="text-start mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-graph-up-arrow me-2 fs-5 text-success"></i> Advanced Analytics
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-lightning-charge-fill me-2 fs-5 text-warning"></i> Priority Product Listing
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-infinity me-2 fs-5 text-primary"></i> Unlimited Feature Access
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-headset me-2 fs-5 text-info"></i> Faster Support
                    </div>
                </div>
                <div class="d-grid gap-2">
                    <a href="{{ route('seller.plans.index') }}" class="btn btn-primary btn-lg rounded-pill">
                        View Plans
                    </a>
                    <button class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">
                        Maybe Later
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
