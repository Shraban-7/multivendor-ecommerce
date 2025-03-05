<div style="width: 250px;">
    <div class="form-group">
        <div
            class="image-preview border bg-light d-flex justify-content-center text-center align-items-center"
            style="width: 200px; height: 200px; line-height: 200px; cursor: pointer; overflow: hidden">            
            @isset($image)
            <img src="{{$image}}" class="img-fluid" style="max-width: 100%; max-height: 100%;">                
            @endisset
            <span class="text-muted">Click to Upload</span>
        </div>
        <input type="file" {{ $attributes }} name="{{ $name ?? 'image' }}" class="d-none file-input" accept="image/*">
        <button type="button" class="btn btn-danger btn-sm mt-2 remove-image d-none">Remove Image</button>
    </div>
    
</div>