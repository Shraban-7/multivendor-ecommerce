<label class="flex items-center gap-2 cursor-pointer">
    <input {{ $attributes }} class="w-4 h-4 text-yellow-500 border-gray-300 rounded focus:ring-2 focus:ring-yellow-400 cursor-pointer" />
    <span class="text-gray-700 select-none">{{ $slot }}</span>
</label>