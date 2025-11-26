@php
$defaultDuration = 3000;
$containerId = 'toast-container';
@endphp

<div aria-live="polite" aria-atomic="true" class="position-fixed top-0 end-0 p-3" style="z-index: 1100" id="{{ $containerId }}">
</div>

<style>
    .toast {
        background-color: #ffffff;
        border: none;
        border-radius: 0.5rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        padding: 0;
        margin-bottom: 10px;
        max-width: 300px;
        opacity: 0;
        transform: translateX(100%);
        transition: opacity 0.3s ease-out, transform 0.3s ease-out;
    }

    .toast.showing,
    .toast.show {
        opacity: 1;
        transform: translateX(0);
    }

    .toast.hide {
        opacity: 0;
        transform: translateX(100%);
    }

    .toast-header {
        border-bottom: 1px solid rgba(0, 0, 0, 0.03);
        background-color: #f8f9fa;
        padding: 0.6rem 0.9rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-top-left-radius: calc(0.5rem - 1px);
        border-top-right-radius: calc(0.5rem - 1px);
        color: #343a40;
    }

    .toast-header .me-auto {
        font-weight: 600;
        font-size: 0.9rem;
    }

    .toast-header i {
        font-size: 0.85rem;
    }

    .toast-body {
        padding: 0.9rem;
        color: #495057;
        font-size: 0.85rem;
    }

    .toast .btn-close {
        font-size: 0.75rem;
        color: #6c757d;
        opacity: 0.8;
        transition: opacity 0.2s ease;
    }

    .toast .btn-close:hover {
        opacity: 1;
        color: #000;
    }

    .toast.toast-success .toast-header {
        background-color: #e0f2f1;
        color: #1a5e55;
    }

    .toast.toast-success .toast-body {
        border-left: 5px solid #28a745;
        color: #1a5e55;
    }

    .toast.toast-error .toast-header {
        background-color: #fde0e1;
        color: #8c2a35;
    }

    .toast.toast-error .toast-body {
        border-left: 5px solid #dc3545;
        color: #8c2a35;
    }

    .toast.toast-warning .toast-header {
        background-color: #fff8e1;
        color: #8c6e2b;
    }

    .toast.toast-warning .toast-body {
        border-left: 5px solid #ffc107;
        color: #8c6e2b;
    }

    .toast.toast-info .toast-header {
        background-color: #e3f2fd;
        color: #2a698c;
    }

    .toast.toast-info .toast-body {
        border-left: 5px solid #17a2b8;
        color: #2a698c;
    }

    .toast.toast-default .toast-body {
        border-left: 5px solid #6c757d;
    }
</style>

<script>
    class CustomToaster {
        constructor(containerId) {
            this.toastContainer = document.getElementById(containerId);
            if (!this.toastContainer) {
                console.error(`Toaster container with ID "${containerId}" not found. Make sure you have the HTML structure in place.`);
                return;
            }
        }

        showToast(type, title, message, duration = {{ $defaultDuration }}, dismissible = true) {
            const toastElement = document.createElement("div");
            toastElement.classList.add("toast", `toast-${type}`);
            toastElement.setAttribute("role", "alert");
            toastElement.setAttribute("aria-live", "assertive");
            toastElement.setAttribute("aria-atomic", "true");

            let iconSvg = "";
            let iconClass = "me-2 toaster-icon";
            switch (type) {
                case "success":
                    iconSvg = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="currentColor" class="${iconClass} text-success" width="16" height="16"><path d="M256 0C114.6 0 0 114.6 0 256s114.6 256 256 256s256-114.6 256-256S397.4 0 256 0zM369 209L225 353c-9.4 9.4-24.6 9.4-33.9 0L143 283c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l35.1 35.1L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"/></svg>`;
                    break;
                case "error":
                    iconSvg = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="currentColor" class="${iconClass} text-danger" width="16" height="16"><path d="M256 0C114.6 0 0 114.6 0 256s114.6 256 256 256s256-114.6 256-256S397.4 0 256 0zM256 320c-17.7 0-32-14.3-32-32V160c0-17.7 14.3-32 32-32s32 14.3 32 32V288c0 17.7-14.3 32-32 32zm0 80c-22.1 0-40-17.9-40-40s17.9-40 40-40s40 17.9 40 40s-17.9 40-40 40z"/></svg>`;
                    break;
                case "warning":
                    iconSvg = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" fill="currentColor" class="${iconClass} text-warning" width="16" height="16"><path d="M569.5 440.3c9.4 16.1 4.1 35.4-11.4 46.9S476.9 512 457.1 512H118.9c-19.8 0-36.6-11.4-42.9-24.8S-4.1 456.4 5.4 440.3L278.6 40c9.4-16.1 26.5-26.2 44.9-26.2s35.5 10.1 44.9 26.2L569.5 440.3zM288 192c-17.7 0-32 14.3-32 32V352c0 17.7 14.3 32 32 32s32-14.3 32-32V224c0-17.7-14.3-32-32-32zm0 80c-22.1 0-40-17.9-40-40s17.9-40 40-40s40 17.9 40 40s-17.9 40-40 40z"/></svg>`;
                    break;
                case "info":
                    iconSvg = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="currentColor" class="${iconClass} text-info" width="16" height="16"><path d="M256 0C114.6 0 0 114.6 0 256s114.6 256 256 256s256-114.6 256-256S397.4 0 256 0zM256 128c-17.7 0-32 14.3-32 32V288c0 17.7 14.3 32 32 32s32-14.3 32-32V160c0-17.7-14.3-32-32-32zm0 80c-22.1 0-40-17.9-40-40s17.9-40 40-40s40 17.9 40 40s-17.9 40-40 40z"/></svg>`;
                    break;
                default:
                    iconSvg = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" fill="currentColor" class="${iconClass} text-secondary" width="16" height="16"><path d="M224 0c-17.7 0-32 14.3-32 32V68.1C107.5 86.8 48 152.2 48 224c0 40.4 17.3 77.4 46.1 103.8c-4.9 19.3-15 37.5-31.5 53.8c-10.4 10.5-16.5 24.1-16.5 38.6c0 30.9 25.1 56 56 56h288c30.9 0 56-25.1 56-56c0-14.5-6.1-28.1-16.5-38.6c-16.5-16.4-26.6-34.6-31.5-53.8C382.7 301.4 400 264.4 400 224c0-71.8-59.5-137.2-144-155.9V32c0-17.7-14.3-32-32-32zm144 416H80c-8.8 0-16-7.2-16-16s7.2-16 16-16h288c8.8 0 16 7.2 16 16s-7.2 16-16 16z"/></svg>`;
            }

            toastElement.innerHTML = `
                <div class="toast-header">
                    ${iconSvg}<strong class="me-auto">${title}</strong>${dismissible? `<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>`: ""}
                </div>
                <div class="toast-body">${message}</div>
            `;

            this.toastContainer.appendChild(toastElement);

            const bootstrapToast = new bootstrap.Toast(toastElement, {
                autohide: duration > 0,
                delay: duration,
            });

            bootstrapToast.show();

            toastElement.addEventListener("hidden.bs.toast", () => {
                toastElement.remove();
            });
        }

        success(title, message, duration = {{ $defaultDuration }}, dismissible = true) {
            this.showToast("success", title, message, duration, dismissible);
        }

        error(title, message, duration = {{ $defaultDuration }}, dismissible = true) {
            this.showToast("error", title, message, duration, dismissible);
        }

        warning(title, message, duration = {{ $defaultDuration }}, dismissible = true) {
            this.showToast("warning", title, message, duration, dismissible);
        }

        info(title, message, duration = {{ $defaultDuration }}, dismissible = true) {
            this.showToast("info", title, message, duration, dismissible);
        }
    }

    document.addEventListener("DOMContentLoaded", () => {
        window.CustomToaster = new CustomToaster('{{ $containerId }}');
        @if(session()->has('success'))
        window.CustomToaster.success('Success!', '{{ session('success') }}');
        @endif
        @if(session()->has('error'))
        window.CustomToaster.error('Error!', '{{ session('error') }}');
        @endif
        @if(session()->has('warning'))
        window.CustomToaster.warning('Warning!', '{{ session('warning') }}');
        @endif
        @if(session()->has('info'))
        window.CustomToaster.info('Info!', '{{ session('info') }}');
        @endif
        @if(session()->has('message'))
        window.CustomToaster.info('Message:', '{{ session('message') }}');
        @endif

        window.showSuccess = function(message, title = 'Success!', duration = {{ $defaultDuration }}, dismissible = true) {
            window.CustomToaster.success(title, message, duration, dismissible);
        };

        window.showError = function(message, title = 'Error!', duration = {{ $defaultDuration }}, dismissible = true) {
            window.CustomToaster.error(title, message, duration, dismissible);
        };

        window.showWarning = function(message, title = 'Warning!', duration = {{ $defaultDuration }}, dismissible = true) {
            window.CustomToaster.warning(title, message, duration, dismissible);
        };

        window.showInfo = function(message, title = 'Info!', duration = {{ $defaultDuration }}, dismissible = true) {
            window.CustomToaster.info(title, message, duration, dismissible);
        };

        window.showToast = function(type, message, title = 'Notification', duration = {{ $defaultDuration }}, dismissible = true) {
            window.CustomToaster.showToast(type, title, message, duration, dismissible);
        };
    });
</script>