@extends('admin.layouts.app')
@section('title','flash sale')

@section('content')

<div class="container mt-4">
    <h4>Add Flash Sale</h4>

    <form action="{{ route('admin.flash-sales.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="card mt-3">
            <div class="card-body">

                <div class="mb-3">
                    <label class="form-label">Flash Sale Title</label>
                    <input type="text" name="title" class="form-control" placeholder="Ex: Winter Mega Flash Sale">
                </div>

                <div class="mb-3">
                    <label class="form-label">Banner Image</label>
                    <input type="file" name="image" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Description (optional)</label>
                    <textarea name="description" class="form-control" rows="3"></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Start Time</label>
                        <input type="datetime-local" name="start_time" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">End Time</label>
                        <input type="datetime-local" name="end_time" class="form-control">
                    </div>
                </div>

                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" name="is_active" value="1" type="checkbox" checked>
                    <label class="form-check-label">Is Active?</label>
                </div>

                <button class="btn btn-primary">Save Flash Sale</button>

            </div>
        </div>
    </form>
</div>

@endsection