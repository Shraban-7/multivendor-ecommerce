@if (session('error') || session('success') || session('warning'))
<div id="alert-border" class="mt-4 mb-3 flex items-start gap-3 p-4 rounded-lg border-l-4 
                        @if (session('error')) bg-red-50 border-red-500 text-red-800
                        @elseif (session('success')) bg-green-50 border-green-500 text-green-800
                        @elseif (session('warning')) bg-amber-50 border-amber-500 text-amber-800 @endif">
    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
    </svg>
    <span class="text-sm flex-1">{{ session('error') ?? (session('success') ?? session('warning')) }}</span>
    <button type="button"
        class="text-current hover:opacity-70 transition-opacity ml-2"
        data-dismiss-target="#alert-border">
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
            <path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" />
        </svg>
    </button>
</div>
@endif