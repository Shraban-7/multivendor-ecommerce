@extends('seller.layouts.app')
@section('title', 'Preview Import')
@section('content')

<div class="flex justify-between items-center mb-3">
    <h4 class="font-bold mb-0 text-ink">Preview Import</h4>
    <a href="{{ route('seller.bulk-upload.index') }}" class="inline-flex items-center justify-center px-3 py-1.5 bg-surface-muted text-ink text-sm font-medium border border-border rounded-xs hover:bg-border/30 focus:outline-none transition-colors gap-1">
        <i data-feather="arrow-left" class="icon-xs me-1"></i> Back
    </a>
</div>

<div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden mb-4">
    <div class="p-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-3">
            <div class="md:col-span-1">
                <div class="p-3 bg-surface-muted rounded-xs text-center">
                    <p class="text-sm text-ink-tertiary mb-1">File</p>
                    <p class="font-semibold mb-0 text-sm">{{ $bulkUpload->original_filename }}</p>
                </div>
            </div>
            <div class="md:col-span-1">
                <div class="p-3 bg-surface-muted rounded-xs text-center">
                    <p class="text-sm text-ink-tertiary mb-1">Total Rows</p>
                    <p class="font-semibold mb-0">{{ $bulkUpload->total_rows }}</p>
                </div>
            </div>
            <div class="md:col-span-1">
                <div class="p-3 bg-surface-muted rounded-xs text-center">
                    <p class="text-sm text-ink-tertiary mb-1">File Type</p>
                    <p class="font-semibold mb-0 uppercase">{{ $bulkUpload->file_type }}</p>
                </div>
            </div>
            <div class="md:col-span-1">
                <div class="p-3 bg-surface-muted rounded-xs text-center">
                    <p class="text-sm text-ink-tertiary mb-1">Status</p>
                    <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-xs badge-soft-secondary">Pending</span>
                </div>
            </div>
        </div>

        <h6 class="font-semibold mb-2">Columns Detected</h6>
        <div class="mb-3">
            @foreach($headers as $header)
                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-xs bg-surface-muted text-ink border border-border me-1 mb-1">{{ $header }}</span>
            @endforeach
        </div>

        <h6 class="font-semibold mb-2">Preview (first {{ count($previewRows) }} rows)</h6>
        <div class="overflow-x-auto" style="max-height:400px;overflow-y:auto;">
            <table class="w-full text-left text-sm text-ink border-collapse table-bordered align-middle bg-white">
                <thead class="bg-surface-muted sticky top-0">
                    <tr>
                        <th class="text-sm font-semibold">#</th>
                        @foreach($headers as $header)
                            <th class="text-sm font-semibold text-ink-tertiary whitespace-nowrap">{{ $header }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($previewRows as $index => $row)
                    <tr>
                        <td class="text-sm text-ink-tertiary">{{ $index + 1 }}</td>
                        @foreach($headers as $header)
                            <td class="text-sm whitespace-nowrap" style="max-width:200px;overflow:hidden;text-overflow:ellipsis;">
                                {{ \Illuminate\Support\Str::limit($row[$header] ?? '', 80) }}
                            </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($bulkUpload->total_rows > count($previewRows))
            <p class="text-ink-tertiary text-sm mt-2">
                Showing {{ count($previewRows) }} of {{ $bulkUpload->total_rows }} rows
            </p>
        @endif

        <div class="mt-4 flex gap-2">
            <form action="{{ route('seller.bulk-upload.confirm', $bulkUpload) }}" method="POST">
                @csrf
                <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-brand-deep text-white text-sm font-medium rounded-xs hover:bg-brand focus:outline-none focus:ring-2 focus:ring-brand-tint disabled:opacity-50 transition-colors">
                    <i data-feather="play" class="icon-xs me-1"></i> Start Import
                </button>
            </form>
            <a href="{{ route('seller.bulk-upload.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-surface-muted text-ink text-sm font-medium border border-border rounded-xs hover:bg-border/30 focus:outline-none transition-colors">
                Cancel
            </a>
        </div>
    </div>
</div>

@endsection