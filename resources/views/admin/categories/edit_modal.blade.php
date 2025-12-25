<div class="modal fade" id="editModal{{ $category->id }}" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Category: {{ $category->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" value="{{ $category->name }}" required>
                    </div>

                    @if($category->category_id)
                    <div class="mb-3">
                        <label class="form-label">Parent Category (Optional)</label>
                        <select name="category_id" class="form-control">
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
                        <label class="form-label">Current Image</label>
                        <div class="mb-2">
                            @if($category->image)
                            <img src="{{ storage_url($category->image) }}" width="80" class="img-thumbnail">
                            @else
                            <span class="text-muted">No image uploaded</span>
                            @endif
                        </div>
                        <input type="file" name="image" class="form-control">
                    </div>
                    @endif

                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="status" value="1"
                            id="active{{ $category->id }}" {{ $category->status ? 'checked' : '' }}>
                        <label class="form-check-label" for="active{{ $category->id }}">Is Active</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Category</button>
                </div>
            </div>
        </form>
    </div>
</div>