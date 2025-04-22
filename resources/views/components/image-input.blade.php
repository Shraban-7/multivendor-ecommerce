<div style="width: 250px;">
    <div class="form-group">
        <div class="image-preview border bg-light d-flex justify-content-center text-center align-items-center position-relative"
            style="width: 200px; height: 200px; cursor: pointer; overflow: hidden">
            @isset($image)
                <img src="{{ $image }}" class="w-100 h-100 position-absolute top-0 start-0 object-fit-cover" style="z-index: 1;">
            @else
                <span class="text-muted" style="z-index: 2;">Click to Upload</span>
            @endisset
        </div>
        <input type="file" {{ $attributes }} name="{{ $name ?? 'image' }}" class="d-none file-input" accept="image/*">
        <button type="button" class="btn btn-danger btn-sm mt-2 remove-image d-none">Remove Image</button>
    </div>
</div>
