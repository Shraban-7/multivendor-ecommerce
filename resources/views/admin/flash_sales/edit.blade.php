@extends('admin.layouts.app')
@section('title', 'flash sale')

@section('content')
    <h4>Edit Flash Sale</h4>

    <form action="{{ route('admin.flash-sales.update', $sale->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden mt-3">
            <div class="p-5">

                <div class="mb-3">
                    <label class="block text-xs font-medium text-ink-secondary mb-1">Flash Sale Title</label>
                    <input type="text" name="title" value="{{ $sale->title }}" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                </div>

                <div class="mb-3">
                    <label class="block text-xs font-medium text-ink-secondary mb-1">Current Banner</label><br>
                    @if ($sale->image)
                        <img src="{{ storage_url($sale->image) }}" width="120">
                    @endif
                </div>

                <div class="mb-3">
                    <label class="block text-xs font-medium text-ink-secondary mb-1">New Banner (optional)</label>
                    <input type="file" name="image" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                </div>

                <div class="mb-3">
                    <label class="block text-xs font-medium text-ink-secondary mb-1">Description</label>
                    <div id="description" style="height: 300px;">
                        {!! old('description', $sale->description ?? '') !!}
                    </div>

                    <input type="hidden" name="description" id="content">
                </div>

                <div class="grid grid-cols-1">
                    <div class="md:col-span-1 mb-3">
                        <label class="block text-xs font-medium text-ink-secondary mb-1">Start Time</label>
                        <input type="datetime-local" name="start_time"
                            value="{{ date('Y-m-d\TH:i', strtotime($sale->start_time)) }}" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                    </div>

                    <div class="md:col-span-1 mb-3">
                        <label class="block text-xs font-medium text-ink-secondary mb-1">End Time</label>
                        <input type="datetime-local" name="end_time"
                            value="{{ date('Y-m-d\TH:i', strtotime($sale->end_time)) }}" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                    </div>
                </div>

                <div class="flex items-center gap-2 form-switch mb-3">
                    <input class="h-4 w-4 rounded border-border text-brand focus:ring-brand" name="is_active" type="checkbox" value="1"
                        {{ $sale->is_active ? 'checked' : '' }}>
                    <label class="text-sm text-ink">Is Active?</label>
                </div>

                <button class="btn btn-primary">Update Flash Sale</button>

            </div>
        </div>
    </form>


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
