@extends('admin.layouts.app')
@section('title', 'Add Flash Sale')

@section('content')
    <div class="flex justify-between items-start mb-4">
        <div>
            <h1 class="text-xl font-semibold text-ink">Add Flash Sale</h1>
            <p class="text-sm text-ink-secondary mt-1">Create a new promotional flash sale</p>
        </div>
    </div>

    <div class="max-w-2xl">
        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden p-5">
            <form action="{{ route('admin.flash-sales.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="space-y-5">
                    <div>
                        <label class="block text-xs font-medium text-ink-secondary mb-1">Flash Sale Title</label>
                        <input type="text" name="title" class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" placeholder="Ex: Winter Mega Flash Sale">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-ink-secondary mb-1">Banner Image</label>
                        <input type="file" name="image" class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-ink-secondary mb-1">Description (optional)</label>
                        <div id="description" style="height: 300px;">
                            {!! old('description', $sale->description ?? '') !!}
                        </div>
                        <input type="hidden" name="description" id="content">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Start Time</label>
                            <input type="datetime-local" name="start_time" class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-ink-secondary mb-1">End Time</label>
                            <input type="datetime-local" name="end_time" class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <input class="h-4 w-4 rounded border-border text-brand focus:ring-brand" name="is_active" value="1" type="checkbox" checked>
                        <label class="text-sm text-ink">Is Active?</label>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-border">
                    <button class="btn btn-primary">Save Flash Sale</button>
                </div>
            </form>
        </div>
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