@php
    use Illuminate\Support\Carbon;

    $deviceCount = $devices->count();
    $seller = seller();
    $hasAccess = $seller->hasFeature('payment_checker');
@endphp
@extends('seller.layouts.app')
@section('title', 'Payment Listener')

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
    #deviceCode { flex-grow: 1; text-align: center; word-break: break-all; overflow-wrap: break-word; font-family: 'Space Mono', monospace; font-weight: 700; font-size: 1.5rem; letter-spacing: 1px; color: #F85606; padding: 0.5rem 0.75rem; user-select: all; background: transparent; border: none; }
    #copyCodeButton { height: 100%; padding: 0.7rem 0.75rem; border-radius: 0 6px 6px 0; background-color: #F85606; border-color: #F85606; flex-shrink: 0; }
    #copyCodeButton:hover { background-color: #C43D00; border-color: #C43D00; }
    #copyFeedback { font-size: 0.8rem; color: #198754; font-weight: 500; margin-top: 6px; min-height: 1rem; opacity: 0; transition: opacity 0.3s ease; }
    #copyFeedback.show { opacity: 1; }
    #connectDeviceModal .modal-footer { border-top: none; justify-content: center; padding: 0.5rem 0 0; }
    @media (max-width: 350px) { #deviceCode { font-size: 1.2rem; letter-spacing: 0; padding: 0.4rem 0.6rem; } }
</style>
@endpush

@section('content')

@if(!$hasAccess)
    <x-seller.subscription-modal />
@endif

<section class="bg-white rounded-sm shadow-sm overflow-hidden mb-3 relative">
    <div class="absolute top-0 left-0 right-0 h-1" style="background: linear-gradient(90deg, #F85606, #fb923c, #fbbf24);"></div>
    <div class="p-5 lg:p-6 pt-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="min-w-0">
                <nav class="flex items-center gap-1 mb-2 text-xs text-ink-tertiary">
                    <i data-lucide="smartphone" class="text-feedback-info" style="width:12px;height:12px;"></i>
                    <span>Workspace</span>
                    <i data-lucide="chevron-right" style="width:12px;height:12px;"></i>
                    <span class="text-ink-soft font-semibold">Payment Listener</span>
                </nav>
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <h1 class="text-xl font-bold text-ink-emphasis mb-0">Automatic Payment Checker</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-feedback-info/15 text-feedback-info">
                        <i data-lucide="bell-ring" style="width:11px;height:11px;" class="me-1"></i> SMS Auto-Ingest
                    </span>
                </div>
                <p class="text-sm text-ink-secondary mb-0">Connect a phone running the Payment Listener app — incoming bank SMS get reconciled automatically.</p>
            </div>
            @if($deviceCount > 0)
                <button class="btn btn-primary" id="generateCodeTrigger">
                    <i data-lucide="qr-code" style="width:15px;height:15px;"></i> Generate Device Code
                </button>
            @endif
        </div>
    </div>
</section>

@php
    $tiles = [
        ['label' => 'Linked Devices', 'value' => $deviceCount, 'top' => '#F85606', 'text' => 'text-brand-deep',          'icon' => 'smartphone'],
        ['label' => 'Recent Payments', 'value' => $payments->count(), 'top' => '#10b981', 'text' => 'text-feedback-success',  'icon' => 'banknote'],
        ['label' => 'Status',           'value' => $hasAccess ? 'Active' : 'Locked', 'top' => '#0ea5e9', 'text' => $hasAccess ? 'text-feedback-info' : 'text-feedback-warning', 'icon' => 'shield-check'],
    ];
@endphp
<section class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-3">
    @foreach ($tiles as $tile)
        <article class="bg-white rounded-sm shadow-sm overflow-hidden relative">
            <div class="absolute top-0 left-0 right-0 h-1" style="background-color: {{ $tile['top'] }};"></div>
            <div class="p-4 pt-5">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider">{{ $tile['label'] }}</span>
                    <i data-lucide="{{ $tile['icon'] }}" class="text-ink-tertiary" style="width:14px;height:14px;"></i>
                </div>
                @if (is_numeric($tile['value']))
                    <h3 class="text-2xl font-bold {{ $tile['text'] }} mb-0">{{ number_format($tile['value']) }}</h3>
                @else
                    <h3 class="text-xl font-bold {{ $tile['text'] }} mb-0">{{ $tile['value'] }}</h3>
                @endif
            </div>
        </article>
    @endforeach
</section>

@if($deviceCount == 0)
    <section class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
        <div class="bg-white rounded-sm shadow-sm overflow-hidden">
            <div class="px-5 py-3 bg-surface-muted flex items-center gap-2">
                <i data-lucide="plus-circle" style="width:14px;height:14px;" class="text-ink-tertiary"></i>
                <h3 class="text-sm font-bold text-ink-emphasis mb-0">Add New Device</h3>
            </div>
            <div class="p-5">
                <p class="text-sm text-ink-soft mb-3">Click <strong>'Generate Device Code'</strong>. Enter this code in the <strong>Payment Listener App</strong> on your device to connect.</p>
                <button class="btn btn-primary btn-lg" id="generateCodeTrigger">
                    <i data-lucide="qr-code" style="width:16px;height:16px;"></i> Generate Device Code
                </button>
            </div>
        </div>
        <div class="bg-white rounded-sm shadow-sm overflow-hidden">
            <div class="px-5 py-3 bg-surface-muted flex items-center gap-2">
                <i data-lucide="info" style="width:14px;height:14px;" class="text-ink-tertiary"></i>
                <h3 class="text-sm font-bold text-ink-emphasis mb-0">Instructions</h3>
            </div>
            <div class="p-5">
                <div class="block p-4 rounded-xs bg-surface-muted text-sm">
                    <ol class="list-decimal ps-4 mb-0 space-y-1 text-ink-soft">
                        <li>Click <strong>"Generate Device Code"</strong>.</li>
                        <li>Enter the generated code in the Payment Listener App.</li>
                        <li>Allow SMS &amp; background permissions on the device.</li>
                        <li>Device status will change to <strong>Active</strong> once connected.</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
@endif

@if($payments->count())
    <section class="bg-white rounded-sm shadow-sm overflow-hidden mb-3">
        <div class="px-5 py-3 bg-surface-muted flex items-center gap-2">
            <i data-lucide="banknote" style="width:14px;height:14px;" class="text-ink-tertiary"></i>
            <h3 class="text-sm font-bold text-ink-emphasis mb-0">Recent Payments</h3>
        </div>
        <div style="max-height: 500px; overflow-y: scroll;" class="p-3">
            <ul class="flex flex-col">
                @foreach ($payments as $payment)
                    <li class="flex items-center px-2 py-2 border-b border-border last:border-0">
                        <div class="flex justify-between items-center w-full">
                            <div class="me-3">
                                <div><span class="font-semibold text-ink-emphasis">{{ $payment->sender }}</span> <span class="text-ink-tertiary">({{ $payment->device->device_name ?? '—' }})</span></div>
                                <div class="text-sm text-ink-soft">{{ $payment->full_sms }}</div>
                            </div>
                            <div class="text-xs text-ink-tertiary whitespace-nowrap">
                                <i data-lucide="clock" style="width:11px;height:11px;" class="me-1 align-text-bottom"></i>
                                {{ $payment->received_at?->format('Y-m-d · h:i A') }}
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>
@endif

@if($deviceCount)
    <section class="bg-white rounded-sm shadow-sm overflow-hidden mb-3">
        <div class="px-5 py-3 bg-surface-muted flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i data-lucide="smartphone" style="width:14px;height:14px;" class="text-ink-tertiary"></i>
                <h3 class="text-sm font-bold text-ink-emphasis mb-0">Linked Devices</h3>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 p-4">
            @foreach ($devices as $device)
                <article class="bg-surface-muted rounded-sm overflow-hidden flex flex-col">
                    <div class="p-5 grow">
                        <div class="flex justify-between items-start mb-2">
                            <strong class="text-lg text-ink-emphasis">{{ $device->device_name ?? 'Pending Device' }}</strong>
                            @php
                                $status = $device->status;
                                $pillBg = match ($status) {
                                    1       => 'bg-feedback-success/15 text-feedback-success',
                                    0       => 'bg-feedback-warning/15 text-feedback-warning',
                                    default => 'bg-surface-muted text-ink-tertiary',
                                };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider {{ $pillBg }}">
                                <span class="w-1.5 h-1.5 rounded-full bg-current opacity-70 me-1.5"></span>
                                {{ $device->statusName }}
                            </span>
                        </div>
                        <div class="mt-2 text-sm">
                            <span class="text-ink-tertiary">Code: <span class="device-code font-mono text-ink-emphasis">{{ $device->device_code }}</span></span>
                        </div>
                        <small class="text-ink-tertiary block mt-2 inline-flex items-center gap-1">
                            <i data-lucide="clock" style="width:11px;height:11px;"></i>
                            Last sync: {{ $device->last_sync_at?->format('Y-m-d · h:i A') ?? '—' }}
                        </small>
                    </div>
                    <div class="px-5 py-3 border-t border-border flex gap-2">
                        <form action="{{ route('seller.paymentListener.devices.checkPayments', $device->id) }}" method="POST" class="flex-1">
                            @csrf
                            <button class="btn btn-primary btn-sm w-full">
                                <i data-lucide="refresh-cw" style="width:13px;height:13px;"></i> Check
                            </button>
                        </form>
                        <form action="{{ route('seller.paymentListener.devices.delete', $device->id) }}" method="POST" class="flex-1"
                              onsubmit="return confirm('Are you sure?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-light btn-sm w-full text-feedback-danger" style="color:#dc2625;">
                                <i data-lucide="trash-2" style="width:13px;height:13px;"></i> Delete
                            </button>
                        </form>
                    </div>
                </article>
            @endforeach
        </div>
    </section>
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
                        <button class="btn btn-primary btn-icon me-2" id="copyCodeButton" title="Copy code">
                            <i data-lucide="clipboard"></i>
                        </button>
                    </div>
                    <div id="copyFeedback" class="mb-3">Code copied successfully!</div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="{{ route('seller.paymentListener.index') }}" class="btn btn-light w-full">Done</a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="errorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-bold text-feedback-danger">Connection Error</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <p id="errorMessageContent" class="mb-0 text-feedback-danger font-bold">An unexpected error occurred while generating the code.</p>
                <small class="text-ink-tertiary">Please try again. If the issue persists, contact support.</small>
            </div>
            <div class="modal-footer justify-center">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

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

    if (generateButton) {
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
                generateButton.innerHTML = `<i data-lucide="qr-code" class="me-2"></i> Generate Device Code`;
            }
        });
    }

    function generateQR(codeValue, qrContainer) {
        qrContainer.innerHTML = '';
        new QRCode(qrContainer, { text: codeValue, width: 120, height: 120, colorDark: "#212529", colorLight: "#ffffff", correctLevel: QRCode.CorrectLevel.H });
    }

    if (copyCodeBtn) {
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
                    copyCodeBtn.innerHTML = '<i data-lucide="check"></i>';
                    setTimeout(() => { feedback.classList.remove("show"); copyCodeBtn.classList.remove('btn-success'); copyCodeBtn.classList.add('btn-primary'); copyCodeBtn.innerHTML = '<i data-lucide="clipboard"></i>'; }, 2000);
                } else { throw new Error("Copy failed via execCommand."); }
            } catch (err) {
                console.error("Copy failed:", err);
                feedback.textContent = "Copy failed. Please select and copy manually.";
                feedback.classList.add("show");
                setTimeout(() => { feedback.classList.remove("show"); feedback.textContent = "Code copied successfully!"; }, 3000);
            }
        });
    }
</script>
@endif
@if(!$hasAccess)
<script>$('#generateCodeTrigger').click(function() { const modal = new bootstrap.Modal(document.getElementById('upgradeModal')); modal.show(); });</script>
@endif
@endpush

@endsection
