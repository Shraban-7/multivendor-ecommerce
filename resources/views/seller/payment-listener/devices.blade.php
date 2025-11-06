@extends('seller.layouts.app')
@section('title', 'Payment Listener Devices')

@section('content')

<style>
    .device-code {
        font-family: monospace;
        font-size: 0.85rem;
        background: #f1f1f1;
        padding: 2px 6px;
        border-radius: 4px;
    }
</style>

<h4 class="mb-3">Automatic Payment Listener</h4>

<!-- <div class="card p-3 mb-3">
    <div class="d-flex justify-content-between align-items-center">
        <label class="form-label mb-0">Enable Auto Payment Confirmation</label>
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="autoConfirmToggle" checked />
        </div>
    </div>
</div> -->

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

@if($devices->count())
<div class="card p-3 mb-3">
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
                <form action="{{ route('seller.paymentListener.devices.delete', $device->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger" type="submit" onclick="return confirm('Are you sure?')">
                        Delete
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

@if($payments->count())
<div class="card p-3">
    <h6 class="mb-3">Recent Payments</h6>
    <div class="table-responsive">
        <table class="table table-sm align-middle">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Sender</th>
                    <th>SMS</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($payments as $payment)
                <tr>
                    <td>{{ $payment->created_at->format('Y-m-d h:i A') }}</td>
                    <td>{{ $payment->sender }}</td>
                    <td>{{ $payment->full_sms }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<div class="modal fade" id="generateCodeModal" tabindex="-1" data-bs-backdrop="static" aria-labelledby="generateCodeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-primary" id="generateCodeModalLabel">
                    Connect New Device
                </h5>
            </div>

            <div class="modal-body text-center pt-2">
                <p class="mt-3 mb-2">
                    Enter this code in your Payment Listener App:
                </p>
                <div class="p-4 bg-light rounded border border-primary mx-4">
                    <h1 id="deviceCodeToCopy" class="mono-code display-4 text-primary fw-bolder mb-0">K9L0M1N2</h1>
                </div>
                <div class="mt-3 d-grid gap-2 mx-4">
                    <button class="btn btn-primary" id="copyCodeButton">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard-check-fill me-2" viewBox="0 0 16 16">
                            <path d="M6.5 2V1a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v1Z" />
                            <path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1A2 2 0 0 1 10.5 4h-5A2 2 0 0 1 4 2.5zm6.854 7.354a.5.5 0 0 0-.708-.708L7.5 10.793 6.354 9.646a.5.5 0 1 0-.708.708l1.5 1.5a.5.5 0 0 0 .708 0z" />
                        </svg>
                        Copy Code
                    </button>
                    <small id="copyFeedback" class="text-success small opacity-0" aria-live="polite">Code copied to clipboard!</small>
                </div>

                <p class="mt-1 small text-muted">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clock me-1" viewBox="0 0 16 16">
                        <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z" />
                        <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z" />
                    </svg>
                    This code is valid for **15 minutes**.
                </p>
            </div>

            <div class="modal-footer justify-content-center border-0 pt-0">
                <a href="{{ route('seller.paymentListener.devices.index') }}" class="btn btn-outline-secondary">Close</a>
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
<script>
    const generateButton = document.getElementById('generateCodeTrigger');
    const successCodeElement = document.getElementById('deviceCodeToCopy');
    const errorMessageContent = document.getElementById('errorMessageContent');

    const successModal = new bootstrap.Modal(document.getElementById('generateCodeModal'));
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
                    successCodeElement.textContent = data.code;
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

    document.getElementById('copyCodeButton').addEventListener('click', function() {
        const codeElement = document.getElementById('deviceCodeToCopy');
        const codeText = codeElement.textContent;
        const feedbackElement = document.getElementById('copyFeedback');

        navigator.clipboard.writeText(codeText).then(() => {
            feedbackElement.classList.remove('opacity-0');
            feedbackElement.classList.add('opacity-100');

            setTimeout(() => {
                feedbackElement.classList.remove('opacity-100');
                feedbackElement.classList.add('opacity-0');
            }, 2000);
        }).catch(err => {
            console.error('Could not copy text: ', err);
            alert("Copy failed. Please copy the code manually: " + codeText);
        });
    });
</script>
@endpush