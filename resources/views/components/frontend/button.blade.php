<button {{ $attributes }}
    class="w-full py-3 px-4 bg-{{$color}}-500 hover:bg-{{$color}}-400
    text-white font-medium rounded-lg text-sm transition-colors duration-200
    focus:outline-none focus:ring-2 focus:ring-{{$color}}-400 focus:ring-offset-2 {{ $class ?? '' }}">
    {{ $slot }}
</button>