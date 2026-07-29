<div class="modal fade" id="editModal{{ $category->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header border-b border-border">
                    <h5 class="modal-title text-sm font-semibold text-ink">Edit Category: {{ $category->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="block text-xs font-medium text-ink-secondary mb-1">Name</label>
                        <input type="text" name="name" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ $category->name }}" required>
                    </div>

                    @if($category->category_id)
                    <div class="mb-3">
                        <label class="block text-xs font-medium text-ink-secondary mb-1">Parent Category (Optional)</label>
                        <select name="category_id" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                            <option value="">None (Root)</option>
                            @foreach($categories as $parentOption)
                            @if($parentOption->id != $category->id)
                            <option value="{{ $parentOption->id }}" {{ $category->category_id == $parentOption->id ? 'selected' : '' }}>
                                {{ $parentOption->name }}
                            </option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                    @endif

                    @if(!$category->category_id)
                    <div class="mb-3">
                        <label class="block text-xs font-medium text-ink-secondary mb-1">Current Image</label>
                        <div class="mb-2">
                            @if($category->image)
                            <img src="{{ storage_url($category->image) }}" width="80" class="border rounded-xs">
                            @else
                            <span class="text-ink-tertiary text-xs">No image uploaded</span>
                            @endif
                        </div>
                        <input type="file" name="image" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                    </div>
                    @endif

                    <div class="flex items-center gap-2">
                        <input class="h-4 w-4 rounded border-border text-brand focus:ring-brand" type="checkbox" name="status" value="1"
                            id="active{{ $category->id }}" {{ $category->status ? 'checked' : '' }}>
                        <label class="text-sm text-ink" for="active{{ $category->id }}">Is Active</label>
                    </div>
                </div>
                <div class="modal-footer border-t border-border">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Category</button>
                </div>
            </div>
        </form>
    </div>
</div>