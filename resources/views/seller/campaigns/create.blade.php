@extends('seller.layouts.app')
@section('title', 'Create Campaign')
@section('content')
    <h4 class="mb-3">Campaign Create</h4>
    <div class="col-md-8">
        <form action="{{ route('seller.campaigns.store') }}" method="POST" enctype="multipart/form-data"
            class="bg-white p-4 rounded shadow-sm">
            @csrf
            <div class="row">
                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                <div class="mb-3 col-md-6">
                    <label class="form-label">Start Date</label>
                    <input type="datetime-local" name="start_date" class="form-control" required>
                </div>

                <div class="mb-3 col-md-6">
                    <label class="form-label">End Date</label>
                    <input type="datetime-local" name="end_date" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Image</label>
                    <input type="file" name="image" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="4" class="form-control"></textarea>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" name="is_active" value="1">
                    <label class="form-check-label">Active</label>
                </div>
            </div>


            <button type="submit" class="btn btn-theme">Create Campaign</button>
        </form>
    </div>

@endsection
