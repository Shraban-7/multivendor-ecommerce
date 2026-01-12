@php
    $defaultDuration = 3000;
    $containerId = 'toast-container';
@endphp

<!-- TOAST CONTAINER -->
<div
    id="{{ $containerId }}"
    aria-live="polite"
    aria-atomic="true"
    class="fixed top-4 right-4 z-[1100] w-full max-w-sm space-y-3"
></div>

<script>
/* ===============================
   TOASTER CLASS
================================ */
class CustomToaster {
    constructor(containerId) {
        this.container = document.getElementById(containerId);
        if (!this.container) {
            console.error(`Toast container "${containerId}" not found.`);
        }
    }

    getConfig(type) {
        const base = {
            wrapper: 'bg-white rounded-lg shadow-lg overflow-hidden transition-all duration-300 ease-out',
            header: 'flex items-center justify-between px-3 pt-3',
            body: 'px-3 pb-3 text-sm',
        };

        const types = {
            success: {
                border: 'border-l-4 border-green-500',
                text: 'text-green-700',
                icon: 'text-green-500',
                svg: `<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 512 512"><path d="M256 0C114.6 0 0 114.6 0 256s114.6 256 256 256 256-114.6 256-256S397.4 0 256 0zM369 209L225 353c-9.4 9.4-24.6 9.4-33.9 0L143 283c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l35.1 35.1L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"/></svg>`
            },
            error: {
                border: 'border-l-4 border-red-500',
                text: 'text-red-700',
                icon: 'text-red-500',
                svg: `<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 512 512"><path d="M256 0C114.6 0 0 114.6 0 256s114.6 256 256 256 256-114.6 256-256S397.4 0 256 0zM256 320a32 32 0 1 0 0-64 32 32 0 0 0 0 64zm-32-160v96a32 32 0 0 0 64 0v-96a32 32 0 0 0-64 0z"/></svg>`
            },
            warning: {
                border: 'border-l-4 border-yellow-400',
                text: 'text-yellow-700',
                icon: 'text-yellow-500',
                svg: `<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 576 512"><path d="M569.5 440.3 278.6 40c-18.8-32.6-65.5-32.6-84.3 0L6.5 440.3C-12.4 472.8 10.4 512 48.9 512h478.2c38.5 0 61.3-39.2 42.4-71.7zM288 192a32 32 0 0 1 32 32v96a32 32 0 0 1-64 0v-96a32 32 0 0 1 32-32zm0 224a40 40 0 1 1 0-80 40 40 0 0 1 0 80z"/></svg>`
            },
            info: {
                border: 'border-l-4 border-sky-500',
                text: 'text-sky-700',
                icon: 'text-sky-500',
                svg: `<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 512 512"><path d="M256 0C114.6 0 0 114.6 0 256s114.6 256 256 256 256-114.6 256-256S397.4 0 256 0zM256 128a32 32 0 1 0 0 64 32 32 0 0 0 0-64zm32 256h-64a16 16 0 0 1 0-32h16v-96h-16a16 16 0 0 1 0-32h48a16 16 0 0 1 16 16v112h16a16 16 0 0 1 0 32z"/></svg>`
            }
        };

        return { ...base, ...types[type] };
    }

    show(type, title, message, duration = {{ $defaultDuration }}, dismissible = true) {
        const cfg = this.getConfig(type);

        const toast = document.createElement('div');
        toast.className = `${cfg.wrapper} ${cfg.border} translate-x-full opacity-0`;

        toast.innerHTML = `
            <div class="${cfg.header} ${cfg.text}">
                <div class="flex items-center gap-2">
                    <span class="${cfg.icon}">${cfg.svg}</span>
                    <strong class="text-sm font-semibold">${title}</strong>
                </div>
                ${dismissible ? `<button class="text-gray-400 hover:text-gray-700 text-xs">&times;</button>` : ''}
            </div>
            <div class="${cfg.body} ${cfg.text}">
                ${message}
            </div>
        `;

        this.container.appendChild(toast);

        requestAnimationFrame(() => {
            toast.classList.remove('translate-x-full', 'opacity-0');
            toast.classList.add('translate-x-0', 'opacity-100');
        });

        const close = () => {
            toast.classList.add('translate-x-full', 'opacity-0');
            setTimeout(() => toast.remove(), 300);
        };

        if (dismissible) {
            toast.querySelector('button')?.addEventListener('click', close);
        }

        if (duration > 0) {
            setTimeout(close, duration);
        }
    }

    success(t, m, d, c) { this.show('success', t, m, d, c); }
    error(t, m, d, c)   { this.show('error', t, m, d, c); }
    warning(t, m, d, c) { this.show('warning', t, m, d, c); }
    info(t, m, d, c)    { this.show('info', t, m, d, c); }
}

/* ===============================
   INIT + GLOBAL HELPERS
================================ */
document.addEventListener('DOMContentLoaded', () => {
    window.toaster = new CustomToaster('{{ $containerId }}');

    @if(session('success'))
        toaster.success('Success!', '{{ session('success') }}');
    @endif

    @if(session('error'))
        toaster.error('Error!', '{{ session('error') }}');
    @endif

    @if(session('warning'))
        toaster.warning('Warning!', '{{ session('warning') }}');
    @endif

    @if(session('info'))
        toaster.info('Info!', '{{ session('info') }}');
    @endif

    @if($errors->any())
        toaster.error('Validation Errors', `{!! implode('<br>', $errors->all()) !!}`);
    @endif
});

/* ===============================
   AJAX / GLOBAL USAGE
================================ */
window.showSuccessToast = (msg, title = 'Success!', duration = {{ $defaultDuration }}) =>
    window.toaster.success(title, msg, duration);

window.showErrorToast = (msg, title = 'Error!', duration = {{ $defaultDuration }}) =>
    window.toaster.error(title, msg, duration);

window.showWarningToast = (msg, title = 'Warning!', duration = {{ $defaultDuration }}) =>
    window.toaster.warning(title, msg, duration);

window.showInfoToast = (msg, title = 'Info!', duration = {{ $defaultDuration }}) =>
    window.toaster.info(title, msg, duration);
</script>
