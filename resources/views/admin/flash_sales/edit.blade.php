@extends('admin.layouts.app')
@section('title', 'flash sale')

@section('content')

    <div class="container mt-4">
        <h4>Edit Flash Sale</h4>

        <form action="{{ route('admin.flash-sales.update', $sale->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="card mt-3">
                <div class="card-body">

                    <div class="mb-3">
                        <label class="form-label">Flash Sale Title</label>
                        <input type="text" name="title" value="{{ $sale->title }}" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Current Banner</label><br>
                        @if ($sale->image)
                            <img src="{{ storage_url($sale->image) }}" width="120">
                        @endif
                    </div>

                    <div class="mb-3">
                        <label class="form-label">New Banner (optional)</label>
                        <input type="file" name="image" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <div id="description" style="height: 300px;">
                            {!! old('description', $sale->description ?? '') !!}
                        </div>

                        <input type="hidden" name="description" id="content">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Start Time</label>
                            <input type="datetime-local" name="start_time"
                                value="{{ date('Y-m-d\TH:i', strtotime($sale->start_time)) }}" class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">End Time</label>
                            <input type="datetime-local" name="end_time"
                                value="{{ date('Y-m-d\TH:i', strtotime($sale->end_time)) }}" class="form-control">
                        </div>
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" name="is_active" type="checkbox" value="1"
                            {{ $sale->is_active ? 'checked' : '' }}>
                        <label class="form-check-label">Is Active?</label>
                    </div>

                    <button class="btn btn-primary">Update Flash Sale</button>

                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var quill = new Quill('#description', {
                    theme: 'snow'
                });

                document.getElementById('content').value = quill.root.innerHTML;

                quill.on('text-change', function() {
                    document.getElementById('content').value = quill.root.innerHTML;
                });
            });
        </script>
    @endpush
@endsection
