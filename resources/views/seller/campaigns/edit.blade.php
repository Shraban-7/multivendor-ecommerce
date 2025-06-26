@extends('seller.layouts.app')
@section('title', 'Edit Campaign')
@section('content')

<form action="{{ route('seller.campaigns.update', $campaign->id) }}" method="POST" enctype="multipart/form-data" class="bg-white p-4 rounded shadow-sm">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label class="form-label">Start Date</label>
        <input type="datetime-local" name="start_date" value="{{ old('start_date', $campaign->start_date) }}" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">End Date</label>
        <input type="datetime-local" name="end_date" value="{{ old('end_date', $campaign->end_date) }}" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Image</label><br>
        <img src="{{ storage_url($campaign->image) }}" style="width: 100px;" class="mb-2">
        <input type="file" name="image" class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" rows="4" class="form-control">{{ old('description', $campaign->description) }}</textarea>
    </div>

    <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" name="is_active" value="1" {{ $campaign->is_active ? 'checked' : '' }}>
        <label class="form-check-label">Active</label>
    </div>

    <button type="submit" class="btn btn-primary">Update Campaign</button>
</form>

@endsection
