<div class="grid grid-cols-1 gap-3">
    <div class="md:col-span-1">
        <label class="block text-xs font-medium text-ink-secondary mb-1">Title</label>
        <input type="text" name="title" value="{{ old('title', $banner->title ?? '') }}" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
    </div>

    <div class="md:col-span-1">
        <label class="block text-xs font-medium text-ink-secondary mb-1">Subtitle</label>
        <input type="text" name="subtitle" value="{{ old('subtitle', $banner->subtitle ?? '') }}" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
    </div>

    <div class="md:col-span-full">
        <label class="block text-xs font-medium text-ink-secondary mb-1">Description</label>
        <x-textarea-input name="description" :value="old('description', $banner->description ?? '')" />
    </div>

    <div class="col-span-full">
        <label class="block text-xs font-medium text-ink-secondary mb-1">Image</label>
        <input type="file" name="image" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
        @if($banner && $banner->image)
        <img src="{{ storage_url($banner->image) }}" alt="banner" class="mt-2 rounded" width="100">
        @endif
    </div>

    <div class="md:col-span-1">
        <label class="block text-xs font-medium text-ink-secondary mb-1">Button Text</label>
        <input type="text" name="button_text" value="{{ old('button_text', $banner->button_text ?? '') }}" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
    </div>

    <div class="md:col-span-1">
        <label class="block text-xs font-medium text-ink-secondary mb-1">Button Link</label>
        <input type="text" name="button_link" value="{{ old('button_link', $banner->button_link ?? '') }}" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
    </div>

    <div class="md:col-span-1">
        <label class="block text-xs font-medium text-ink-secondary mb-1">Section</label>
        <select name="section" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors" required>
            @foreach(\App\Domain\Product\Models\Banner::sections() as $section)
            <option value="{{ $section }}" {{ old('section', $banner->section ?? '') == $section ? 'selected' : '' }}>
                {{ ucfirst(str_replace('_', ' ', $section)) }}
            </option>
            @endforeach
        </select>
    </div>

    <div class="md:col-span-1">
        <label class="block text-xs font-medium text-ink-secondary mb-1">Sort Order</label>
        <input type="number" name="sort_order" value="{{ old('sort_order', $banner->sort_order ?? 0) }}" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
    </div>

    <div class="md:col-span-1">
        <label class="block text-xs font-medium text-ink-secondary mb-1">Active</label>
        <div class="flex items-center gap-2 form-switch mt-2">
            <input class="h-4 w-4 rounded border-border text-brand focus:ring-brand" type="checkbox" name="is_active" value="1"
                {{ old('is_active', $banner->is_active ?? true) ? 'checked' : '' }}>
        </div>
    </div>
</div>