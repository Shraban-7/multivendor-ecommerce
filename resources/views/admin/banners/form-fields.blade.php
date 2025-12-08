<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Title</label>
        <input type="text" name="title" value="{{ old('title', $banner->title ?? '') }}" class="form-control">
    </div>

    <div class="col-md-6">
        <label class="form-label">Subtitle</label>
        <input type="text" name="subtitle" value="{{ old('subtitle', $banner->subtitle ?? '') }}" class="form-control">
    </div>

    <div class="col-md-12">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="2">{{ old('description', $banner->description ?? '') }}</textarea>
    </div>

    <div class="col-12">
        <label class="form-label">Image</label>
        <input type="file" name="image" class="form-control">
        @if($banner && $banner->image)
        <img src="{{ asset('storage/'.$banner->image) }}" alt="banner" class="mt-2 rounded" width="100">
        @endif
    </div>

    <div class="col-md-6">
        <label class="form-label">Button Text</label>
        <input type="text" name="button_text" value="{{ old('button_text', $banner->button_text ?? '') }}" class="form-control">
    </div>

    <div class="col-md-6">
        <label class="form-label">Button Link</label>
        <input type="text" name="button_link" value="{{ old('button_link', $banner->button_link ?? '') }}" class="form-control">
    </div>

    <div class="col-md-6">
        <label class="form-label">Section</label>
        <select name="section" class="form-select" required>
            @foreach(\App\Models\Banner::sections() as $section)
            <option value="{{ $section }}" {{ old('section', $banner->section ?? '') == $section ? 'selected' : '' }}>
                {{ ucfirst(str_replace('_', ' ', $section)) }}
            </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label">Sort Order</label>
        <input type="number" name="sort_order" value="{{ old('sort_order', $banner->sort_order ?? 0) }}" class="form-control">
    </div>

    <div class="col-md-6">
        <label class="form-label">Active</label>
        <div class="form-check form-switch mt-2">
            <input class="form-check-input" type="checkbox" name="is_active" value="1"
                {{ old('is_active', $banner->is_active ?? true) ? 'checked' : '' }}>
        </div>
    </div>
</div>