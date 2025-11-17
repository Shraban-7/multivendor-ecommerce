@extends('seller.layouts.app')
@section('title', 'Payment Checker')

@push('styles')
<style>
    #connectDeviceModal .modal-content {
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        padding: 1.25rem;
    }

    #connectDeviceModal .modal-header {
        border-bottom: none;
        padding: 0 0 0.5rem 0;
        text-align: center;
        display: block;
    }

    #connectDeviceModal .modal-title {
        font-weight: 700;
        font-size: 1.35rem;
        color: #212529;
    }

    #connectDeviceModal .modal-body {
        padding: 0;
        text-align: center;
    }

    .instruction-text {
        color: #6c757d;
        font-size: 0.9rem;
        margin-bottom: 1.5rem;
    }

    #qrCodeContainer {
        padding: 8px;
        background: white;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        margin-bottom: 0.75rem;
        display: inline-block;
    }

    #qrCodeContainer canvas {
        width: 120px !important;
        height: 120px !important;
        border-radius: 4px;
        display: block;
    }

    .or-separator {
        margin: 1.25rem 0;
        display: flex;
        align-items: center;
        color: #adb5bd;
        font-size: 0.8rem;
        text-transform: uppercase;
        font-weight: 600;
    }

    .or-separator::before,
    .or-separator::after {
        content: '';
        flex-grow: 1;
        height: 1px;
        background: #e9ecef;
        margin: 0 1rem;
    }

    #connectDeviceModal .code-input-group {
        width: 100%;
        display: flex;
        align-items: center;
        padding: 0;
        border: 1px solid #9bbdff;
        background: #e9f2ff;
        border-radius: 6px;
        margin-bottom: 0.75rem;
    }

    #deviceCode {
        flex-grow: 1;
        text-align: center;
        word-break: break-all;
        overflow-wrap: break-word;
        font-family: 'Space Mono', monospace;
        font-weight: 700;
        font-size: 1.5rem;
        letter-spacing: 1px;
        color: #0d6efd;
        padding: 0.5rem 0.75rem;
        user-select: all;
        background: transparent;
        border: none;
    }

    #copyCodeButton {
        height: 100%;
        padding: 0.7rem 0.75rem;
        border-radius: 0 6px 6px 0;
        background-color: #0d6efd;
        border-color: #0d6efd;
        flex-shrink: 0;
    }

    #copyCodeButton:hover {
        background-color: #0b5ed7;
        border-color: #0b5ed7;
    }

    @media (max-width: 350px) {
        #deviceCode {
            font-size: 1.2rem;
            letter-spacing: 0;
            padding: 0.4rem 0.6rem;
        }
    }

    #copyFeedback {
        font-size: 0.8rem;
        color: #198754;
        font-weight: 500;
        margin-top: 6px;
        min-height: 1rem;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    #copyFeedback.show {
        opacity: 1;
    }

    #connectDeviceModal .modal-footer {
        border-top: none;
        justify-content: center;
        padding: 0.5rem 0 0;
    }
</style>
@endpush

@section('content')

@php
$deviceCount = $devices->count();
@endphp

<div class="d-flex justify-content-between align-items-end mb-3">
    <h4 class="mb-0">Automatic Payment Checker</h4>
    @if($deviceCount > 0)
    <button class="btn btn-primary" id="generateCodeTrigger">
        <i class="bi bi-qr-code-scan"></i> Generate Device Code
    </button>
    @endif
</div>

@if($deviceCount == 0)
<div class="row">
    <div class="col-md-6 mb-3">
        <div class="card card-shadow">
            <div class="card-body">
                <h5 class="card-title">🔗 Add New Device</h5>
                <p class="small text-muted">Click **'Generate Device Code'**. Enter this code in the **Payment Listener App** on your device to connect.</p>
                <button class="btn btn-primary btn-lg" id="generateCodeTrigger">
                    <i class="bi bi-qr-code-scan"></i> Generate Device Code
                </button>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="card p-3">
            <h6>Instructions</h6>
            <div class="alert bg-light border mb-1" role="alert">
                <ol class="mb-0">
                    <li>
                        Click <strong>“Generate Device Code”</strong>.
                    </li>
                    <li>Enter the generated code in the Payment Listener App.</li>
                    <li>Allow SMS & background permissions on the device.</li>
                    <li>
                        Device status will change to <strong>Active</strong> once
                        connected.
                    </li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endif

@if($payments->count())
<div class="card p-3 mb-3" style="max-height: 600px; overflow-y:scroll;">
    <h6 class="mb-3">Recent Payments</h6>
    <ul class="list-group list-group-flush">
        @foreach ($payments as $payment)
        <li class="list-group-item">
            <div class="d-flex justify-content-between align-items-center">
                <div class="me-3">
                    <div><span class="fw-bold">{{ $payment->sender }}</span> <span class="text-muted">({{ $payment->device->device_name }})</span></div>
                    <div class="small">{{ $payment->full_sms }}</div>
                </div>
                <div class="small text-muted text-nowrap">{{ $payment->received_at?->format('Y-m-d h:i A') }}</div>
            </div>
        </li>
        @endforeach
    </ul>
</div>
@endif

@if($deviceCount)
<h4 class="mb-2">Linked Devices</h4>
<div class="row g-3">
    @foreach ($devices as $device)
    <div class="col-md-4 col-sm-6">
        <div class="device-card p-3 rounded-4 border bg-white h-100 d-flex flex-column justify-content-between shadow-sm">
            <div>
                <div class="d-flex justify-content-between">
                    <strong class="fs-5">{{ $device->device_name ?? 'Pending Device' }}</strong>
                    <div>
                        <span class="badge 
                            @if($device->status === 1) bg-success
                            @elseif($device->status === 0) bg-warning text-dark
                            @else bg-secondary
                            @endif">
                            {{ $device->statusName }}
                        </span>
                    </div>
                </div>

                <div class="mt-2 small text-muted">
                    <span class="text-dark fw-semibold">Code:</span>
                    <span class="device-code">{{ $device->device_code }}</span>
                </div>

                <small class="text-muted d-block mt-2">
                    Last sync: {{ $device->last_sync_at?->format('Y-m-d h:i A') }}
                </small>
            </div>

            <div class="d-flex mt-3 gap-2">
                <form action="{{ route('seller.paymentListener.devices.checkPayments', $device->id) }}"
                    method="POST" class="flex-fill">
                    @csrf
                    <button class="btn btn-sm btn-primary w-100 d-flex align-items-center justify-content-center">
                        <i class="bi bi-arrow-clockwise me-1"></i> Check
                    </button>

                </form>

                <form action="{{ route('seller.paymentListener.devices.delete', $device->id) }}"
                    method="POST" class="flex-fill">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger w-100"
                        onclick="return confirm('Are you sure?')">
                        Delete
                    </button>
                </form>
            </div>

        </div>
    </div>
    @endforeach

</div>
@endif


<!-- @if($deviceCount)
<div class="card p-3">
    <h6 class="mb-3">Linked Devices</h6>
    <div class="list-group" id="deviceList">
        @foreach ($devices as $device)
        <div
            class="list-group-item d-flex flex-wrap justify-content-between align-items-center">
            <div>
                <strong>{{ $device->device_name ?? 'Pending Device' }}</strong>
                <div>
                    Code:
                    <span class="device-code">{{ $device->device_code }}</span>
                    <span class="ms-2">{{ $device->status }}</span>
                </div>
                <small class="text-muted">Last sync: {{ $device->last_sync_at?->format('Y-m-d h:i A') }}</small>
            </div>
            <div class="mt-2 mt-md-0">
                <div class="d-flex">
                    <form action="{{ route('seller.paymentListener.devices.checkPayments', $device->id) }}" method="POST" class="me-1 mb-1">
                        @csrf
                        <button class="btn btn-sm btn-primary" type="submit">Check Payments</button>
                    </form>

                    <form action="{{ route('seller.paymentListener.devices.delete', $device->id) }}" method="POST" class="mb-1">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" type="submit" onclick="return confirm('Are you sure?')">
                            Delete
                        </button>
                    </form>
                </div>

            </div>
        </div>
        @endforeach
    </div>
</div>
@endif -->

<div class="modal fade" id="connectDeviceModal" tabindex="-1" data-bs-backdrop="static" aria-labelledby="connectDeviceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 360px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="connectDeviceModalLabel">🔑 Connect New Device </h5>
            </div>
            <div class="modal-body">
                <p class="instruction-text">
                    Use one of the two methods below to link your new device.
                </p>
                <div class="d-flex flex-column align-items-center mb-4">
                    <div id="qrCodeContainer"></div>
                    <p class="text-muted small mt-2 mb-0">Scan to auto-pair</p>
                </div>
                <div class="or-separator">OR</div>
                <div class="device-code-wrapper">
                    <p class="text-muted small mb-2">Enter the code manually:</p>
                    <div class="code-input-group">
                        <div id="deviceCode"></div>
                        <button class="btn btn-primary me-2" id="copyCodeButton" title="Copy code">
                            <i class="bi bi-clipboard"></i>
                        </button>
                    </div>
                    <div id="copyFeedback" class="mb-3">Code copied successfully!</div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="{{ route('seller.paymentListener.index') }}" class="btn btn-light w-100">Done</a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="errorModalLabel">⚠️ Connection Error</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <p id="errorMessageContent" class="mb-0 text-danger fw-bold">An unexpected error occurred while generating the code.</p>
                <small class="text-muted">Please try again. If the issue persists, contact support.</small>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
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
        generateButton.innerHTML = `
            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
            Generating...
        `;

        try {
            const response = await fetch(ROUTE_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({})
            });

            const data = await response.json();

            if (response.ok) {
                if (data.code) {
                    deviceCodeElement.textContent = data.code;
                    generateQR(data.code, document.getElementById('qrCodeContainer'));
                    successModal.show();
                } else {
                    throw new Error('Response successful, but device code is missing.');
                }

            } else {
                const errorMsg = data.message || `Server returned status ${response.status}.`;
                errorMessageContent.textContent = errorMsg;
                errorModal.show();
            }

        } catch (error) {
            console.error('Fetch error:', error);
            errorMessageContent.textContent = `A network error occurred: ${error.message}`;
            errorModal.show();

        } finally {
            generateButton.disabled = false;
            generateButton.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-qr-code-scan me-2" viewBox="0 0 16 16">
                    <path d="M0 2a2 2 0 0 1 2-2h2v2H2v2H0V2Zm4 12v-2H2v2h2ZM2 6h2v2H0V6Zm0 4h2v2H0v-2Zm4-4h2v2H4V6Zm0 4h2v2H4v-2Zm0-6h2v2H4V4Zm0 10h2v2H4v-2Zm4 2v-2h2v2H8Zm4 0v-2h2v2h-2ZM8 6h2v2H8V6Zm0 4h2v2H8v-2Zm-4-4h2v2H4V6Zm0 4h2v2H4v-2Zm8-6h2v2h-2V4Zm0 4h2v2h-2V8Zm2-8a2 2 0 0 1 2 2v2h-2V2h-2V0h2ZM16 4v2h-2V4h-2V2h2a2 2 0 0 1 2 2Zm-4 6v2h-2v-2h2Z"/>
                </svg>
                Generate Device Code
            `;
        }
    });

    function generateQR(codeValue, qrContainer) {
        qrContainer.innerHTML = '';
        new QRCode(qrContainer, {
            text: codeValue,
            width: 120,
            height: 120,
            colorDark: "#212529",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });
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

                const originalIcon = '<i class="bi bi-clipboard"></i>';
                copyCodeBtn.classList.remove('btn-primary');
                copyCodeBtn.classList.add('btn-success');
                copyCodeBtn.innerHTML = '<i class="bi bi-check-lg"></i>';

                setTimeout(() => {
                    feedback.classList.remove("show");
                    copyCodeBtn.classList.remove('btn-success');
                    copyCodeBtn.classList.add('btn-primary');
                    copyCodeBtn.innerHTML = originalIcon;
                }, 2000);
            } else {
                throw new Error("Copy failed via execCommand.");
            }
        } catch (err) {
            console.error("Copy failed:", err);
            feedback.textContent = "Copy failed. Please select and copy manually.";
            feedback.classList.add("show");
            setTimeout(() => {
                feedback.classList.remove("show");
                feedback.textContent = "Code copied successfully!";
            }, 3000);
        }
    });
</script>
@endpush