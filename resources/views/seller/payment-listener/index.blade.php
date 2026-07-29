@extends('seller.layouts.app')
@section('title', 'Payment Checker')

@push('styles')
<style>
    #connectDeviceModal .modal-content { border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); padding: 1.25rem; }
    #connectDeviceModal .modal-header { border-bottom: none; padding: 0 0 0.5rem 0; text-align: center; display: block; }
    #connectDeviceModal .modal-title { font-weight: 700; font-size: 1.35rem; color: #212529; }
    #connectDeviceModal .modal-body { padding: 0; text-align: center; }
    .instruction-text { color: #6c757d; font-size: 0.9rem; margin-bottom: 1.5rem; }
    #qrCodeContainer { padding: 8px; background: white; border: 1px solid #e9ecef; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); margin-bottom: 0.75rem; display: inline-block; }
    #qrCodeContainer canvas { width: 120px !important; height: 120px !important; border-radius: 4px; display: block; }
    .or-separator { margin: 1.25rem 0; display: flex; align-items: center; color: #adb5bd; font-size: 0.8rem; text-transform: uppercase; font-weight: 600; }
    .or-separator::before, .or-separator::after { content: ''; flex-grow: 1; height: 1px; background: #e9ecef; margin: 0 1rem; }
    #connectDeviceModal .code-input-group { width: 100%; display: flex; align-items: center; padding: 0; border: 1px solid #9bbdff; background: #e9f2ff; border-radius: 6px; margin-bottom: 0.75rem; }
    #deviceCode { flex-grow: 1; text-align: center; word-break: break-all; overflow-wrap: break-word; font-family: 'Space Mono', monospace; font-weight: 700; font-size: 1.5rem; letter-spacing: 1px; color: var(--bs-primary); padding: 0.5rem 0.75rem; user-select: all; background: transparent; border: none; }
    #copyCodeButton { height: 100%; padding: 0.7rem 0.75rem; border-radius: 0 6px 6px 0; background-color: var(--bs-primary); border-color: var(--bs-primary); flex-shrink: 0; }
    #copyCodeButton:hover { background-color: var(--bs-dark-primary); border-color: var(--bs-dark-primary); }
    #copyFeedback { font-size: 0.8rem; color: #198754; font-weight: 500; margin-top: 6px; min-height: 1rem; opacity: 0; transition: opacity 0.3s ease; }
    #copyFeedback.show { opacity: 1; }
    #connectDeviceModal .modal-footer { border-top: none; justify-content: center; padding: 0.5rem 0 0; }
    @media (max-width: 350px) { #deviceCode { font-size: 1.2rem; letter-spacing: 0; padding: 0.4rem 0.6rem; } }
</style>
@endpush

@section('content')

<?php
$deviceCount = $devices->count();
$seller = seller();
$hasAccess = $seller->hasFeature('payment_checker');
?>

@if(!$hasAccess)
<x-seller.subscription-modal />
@endif

<div class="flex justify-between items-end mb-3">
    <h4 class="font-bold mb-0 text-ink">Automatic Payment Checker</h4>
    @if($deviceCount > 0)
    <button class="inline-flex items-center justify-center px-4 py-2 bg-brand-deep text-white text-sm font-medium rounded-xs hover:bg-brand focus:outline-none focus:ring-2 focus:ring-brand-tint disabled:opacity-50 transition-colors gap-1" id="generateCodeTrigger">
        <i class="bi bi-qr-code-scan"></i> Generate Device Code
    </button>
    @endif
</div>

@if($deviceCount == 0)
<div class="grid grid-cols-1 md:grid-cols-2 gap-3">
    <div class="md:col-span-1">
        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden" style="border-radius: 12px;">
            <div class="p-5">
                <h5 class="font-semibold">Add New Device</h5>
                <p class="text-sm text-ink-tertiary">Click **'Generate Device Code'**. Enter this code in the **Payment Listener App** on your device to connect.</p>
                <button class="inline-flex items-center justify-center px-5 py-2.5 bg-brand-deep text-white text-base font-medium rounded-xs hover:bg-brand focus:outline-none focus:ring-2 focus:ring-brand-tint disabled:opacity-50 transition-colors gap-1" id="generateCodeTrigger">
                    <i class="bi bi-qr-code-scan"></i> Generate Device Code
                </button>
            </div>
        </div>
    </div>
    <div class="md:col-span-1">
        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden" style="border-radius: 12px;">
            <div class="p-5">
                <h6 class="font-semibold">Instructions</h6>
                <div class="block p-4 rounded-xs bg-surface-muted border border-border text-ink text-sm mb-1" role="alert">
                    <ol class="mb-0">
                        <li>Click <strong>"Generate Device Code"</strong>.</li>
                        <li>Enter the generated code in the Payment Listener App.</li>
                        <li>Allow SMS & background permissions on the device.</li>
                        <li>Device status will change to <strong>Active</strong> once connected.</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@if($payments->count())
<div class="bg-white border border-border rounded-sm shadow-sm p-3 mb-3" style="border-radius: 12px; max-height: 500px; overflow-y:scroll;">
    <h6 class="font-semibold mb-3">Recent Payments</h6>
    <ul class="flex flex-col">
        @foreach ($payments as $payment)
        <li class="flex items-center px-0 py-2 border-b border-border">
            <div class="flex justify-between items-center">
                <div class="me-3">
                    <div><span class="font-bold">{{ $payment->sender }}</span> <span class="text-ink-tertiary">({{ $payment->device->device_name }})</span></div>
                    <div class="text-sm">{{ $payment->full_sms }}</div>
                </div>
                <div class="text-sm text-ink-tertiary whitespace-nowrap">{{ $payment->received_at?->format('Y-m-d h:i A') }}</div>
            </div>
        </li>
        @endforeach
    </ul>
</div>
@endif

@if($deviceCount)
<h4 class="font-bold mb-3 text-ink">Linked Devices</h4>
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
    @foreach ($devices as $device)
    <div class="sm:col-span-1 md:col-span-1">
        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden h-full" style="border-radius: 12px;">
            <div class="p-5 flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-start">
                        <strong class="text-xl">{{ $device->device_name ?? 'Pending Device' }}</strong>
                        <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-xs {{ $device->status === 1 ? 'bg-feedback-success text-white' : ($device->status === 0 ? 'bg-feedback-warning text-ink' : 'bg-ink-tertiary text-white') }}">
                            {{ $device->statusName }}
                        </span>
                    </div>
                    <div class="mt-2 text-sm text-ink-tertiary">
                        <span class="text-ink font-semibold">Code:</span>
                        <span class="device-code">{{ $device->device_code }}</span>
                    </div>
                    <small class="text-ink-tertiary block mt-2">Last sync: {{ $device->last_sync_at?->format('Y-m-d h:i A') }}</small>
                </div>
                <div class="flex mt-3 gap-2">
                    <form action="{{ route('seller.paymentListener.devices.checkPayments', $device->id) }}" method="POST" class="flex-1">
                        @csrf
                        <button class="inline-flex items-center justify-center px-3 py-1.5 bg-brand-deep text-white text-sm font-medium rounded-xs hover:bg-brand focus:outline-none focus:ring-2 focus:ring-brand-tint disabled:opacity-50 transition-colors w-full gap-1">
                            <i class="bi bi-arrow-clockwise me-1"></i> Check
                        </button>
                    </form>
                    <form action="{{ route('seller.paymentListener.devices.delete', $device->id) }}" method="POST" class="flex-1">
                        @csrf @method('DELETE')
                        <button class="inline-flex items-center justify-center px-3 py-1.5 bg-transparent text-feedback-danger text-sm font-medium border border-feedback-danger rounded-xs hover:bg-feedback-danger hover:text-white focus:outline-none transition-colors w-full" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif

<div class="modal fade" id="connectDeviceModal" tabindex="-1" data-bs-backdrop="static" aria-labelledby="connectDeviceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 360px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="connectDeviceModalLabel"> Connect New Device</h5>
            </div>
            <div class="modal-body">
                <p class="instruction-text">Use one of the two methods below to link your new device.</p>
                <div class="flex flex-col items-center mb-4">
                    <div id="qrCodeContainer"></div>
                    <p class="text-ink-tertiary text-sm mt-2 mb-0">Scan to auto-pair</p>
                </div>
                <div class="or-separator">OR</div>
                <div class="device-code-wrapper">
                    <p class="text-ink-tertiary text-sm mb-2">Enter the code manually:</p>
                    <div class="code-input-group">
                        <div id="deviceCode"></div>
                        <button class="inline-flex items-center justify-center px-4 py-2 bg-brand-deep text-white text-sm font-medium rounded-xs hover:bg-brand focus:outline-none focus:ring-2 focus:ring-brand-tint disabled:opacity-50 transition-colors me-2" id="copyCodeButton" title="Copy code">
                            <i class="bi bi-clipboard"></i>
                        </button>
                    </div>
                    <div id="copyFeedback" class="mb-3">Code copied successfully!</div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="{{ route('seller.paymentListener.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-surface-muted text-ink text-sm font-medium border border-border rounded-xs hover:bg-border/30 focus:outline-none transition-colors w-full">Done</a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="errorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header bg-feedback-danger text-white">
                <h5 class="modal-title">Connection Error</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <p id="errorMessageContent" class="mb-0 text-feedback-danger font-bold">An unexpected error occurred while generating the code.</p>
                <small class="text-ink-tertiary">Please try again. If the issue persists, contact support.</small>
            </div>
            <div class="modal-footer justify-center">
                <button type="button" class="inline-flex items-center justify-center px-4 py-2 bg-surface-muted text-ink text-sm font-medium border border-border rounded-xs hover:bg-border/30 focus:outline-none transition-colors" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
@if($hasAccess)
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    const generateButton = document.getElementById('generateCodeTrigger');
    const deviceCodeElement = document.getElementById('deviceCode');
    const errorMessageContent = document.getElementById('errorMessageContent');
    const copyCodeBtn = document.getElementById('copyCodeButton');
    const successModal = new bootstrap.Modal(document.getElementById('connectDeviceModal'));
    const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
    const ROUTE_URL = "{{ route('seller.paymentListener.devices.generateCode') }}";

    generateButton.addEventListener('click', async () => {
        generateButton.disabled = true;
        generateButton.innerHTML = `<span class="inline-block h-4 w-4 animate-spin rounded-full border-2 border-current border-t-transparent me-2" role="status" aria-hidden="true"></span> Generating...`;
        try {
            const response = await fetch(ROUTE_URL, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                body: JSON.stringify({})
            });
            const data = await response.json();
            if (response.ok) {
                if (data.code) { deviceCodeElement.textContent = data.code; generateQR(data.code, document.getElementById('qrCodeContainer')); successModal.show(); }
                else { throw new Error('Response successful, but device code is missing.'); }
            } else {
                errorMessageContent.textContent = data.message || `Server returned status ${response.status}.`;
                errorModal.show();
            }
        } catch (error) {
            console.error('Fetch error:', error);
            errorMessageContent.textContent = `A network error occurred: ${error.message}`;
            errorModal.show();
        } finally {
            generateButton.disabled = false;
            generateButton.innerHTML = `<i class="bi bi-qr-code-scan me-2"></i> Generate Device Code`;
        }
    });

    function generateQR(codeValue, qrContainer) {
        qrContainer.innerHTML = '';
        new QRCode(qrContainer, { text: codeValue, width: 120, height: 120, colorDark: "#212529", colorLight: "#ffffff", correctLevel: QRCode.CorrectLevel.H });
    }

    copyCodeBtn.addEventListener("click", () => {
        const feedback = document.getElementById("copyFeedback");
        const range = document.createRange();
        range.selectNode(deviceCodeElement);
        window.getSelection().removeAllRanges();
        window.getSelection().addRange(range);
        try {
            const success = document.execCommand('copy');
            window.getSelection().removeAllRanges();
            if (success) {
                feedback.classList.add("show");
                copyCodeBtn.classList.remove('btn-primary');
                copyCodeBtn.classList.add('btn-success');
                copyCodeBtn.innerHTML = '<i class="bi bi-check-lg"></i>';
                setTimeout(() => { feedback.classList.remove("show"); copyCodeBtn.classList.remove('btn-success'); copyCodeBtn.classList.add('btn-primary'); copyCodeBtn.innerHTML = '<i class="bi bi-clipboard"></i>'; }, 2000);
            } else { throw new Error("Copy failed via execCommand."); }
        } catch (err) {
            console.error("Copy failed:", err);
            feedback.textContent = "Copy failed. Please select and copy manually.";
            feedback.classList.add("show");
            setTimeout(() => { feedback.classList.remove("show"); feedback.textContent = "Code copied successfully!"; }, 3000);
        }
    });
</script>
@endif
@if(!$hasAccess)
<script>$('#generateCodeTrigger').click(function() { const modal = new bootstrap.Modal(document.getElementById('upgradeModal')); modal.show(); });</script>
@endif
@endpush