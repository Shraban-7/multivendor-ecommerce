@extends('seller.layouts.app')
@section('title', 'Preview Import')
@section('content')

<section class="bg-white rounded-sm shadow-sm overflow-hidden mb-4 relative">
    <div class="absolute top-0 left-0 right-0 h-1" style="background: linear-gradient(90deg, #16a34a, #22c55e, #86efac);"></div>
    <div class="p-5 lg:p-6 pt-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="min-w-0">
                <nav class="flex items-center gap-1 mb-2 text-xs text-ink-tertiary">
                    <i data-lucide="upload-cloud" class="text-feedback-success" style="width:12px;height:12px;"></i>
                    <a href="{{ route('seller.bulk-upload.index') }}" class="hover:text-ink transition-colors">Bulk Upload</a>
                    <i data-lucide="chevron-right" style="width:12px;height:12px;"></i>
                    <span class="text-ink-soft font-semibold">Preview Import</span>
                </nav>
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <h1 class="text-xl font-bold text-ink-emphasis mb-0">Preview Import</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-feedback-success/15 text-feedback-success">
                        <i data-lucide="eye" style="width:11px;height:11px;" class="me-1"></i> Pre-import
                    </span>
                </div>
                <p class="text-sm text-ink-secondary mb-0">Confirm the columns and rows before importing.</p>
            </div>
            <div class="flex flex-wrap gap-2 shrink-0">
                <a href="{{ route('seller.bulk-upload.index') }}" class="btn btn-light btn-sm">
                    <i data-lucide="arrow-left" style="width:14px;height:14px;"></i> Back
                </a>
            </div>
        </div>
    </div>
</section>

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
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider bg-amber-50 text-amber-700"><span class="w-1.5 h-1.5 rounded-full bg-current opacity-70 me-1.5"></span>Pending</span>
                </div>
            </div>
        </div>

        <h6 class="font-semibold mb-2">Columns Detected</h6>
        <div class="mb-3">
            @foreach($headers as $header)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider bg-surface-muted text-ink-secondary me-1 mb-1">{{ $header }}</span>
            @endforeach
        </div>

        <h6 class="font-semibold mb-2">Preview (first {{ count($previewRows) }} rows)</h6>
        <div class="overflow-x-auto" style="max-height:400px;overflow-y:auto;">
            <table class="w-full text-left text-sm text-ink border-collapse">
                <thead class="bg-surface-muted sticky top-0">
                    <tr>
                        <th class="px-4 py-2.5 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">#</th>
                        @foreach($headers as $header)
                            <th class="px-4 py-2.5 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary whitespace-nowrap">{{ $header }}</th>
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
                <button type="submit" class="btn btn-primary">
                    <i data-lucide="play" class="icon-xs me-1"></i> Start Import
                </button>
            </form>
            <a href="{{ route('seller.bulk-upload.index') }}" class="btn btn-light">
                Cancel
            </a>
        </div>
    </div>
</div>

@endsection