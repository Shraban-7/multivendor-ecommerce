@extends('seller.layouts.app')
@section('title', 'Import Details')
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
                    <span class="text-ink-soft font-semibold">Import Details</span>
                </nav>
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <h1 class="text-xl font-bold text-ink-emphasis mb-0">Import Details</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-feedback-success/15 text-feedback-success">
                        <i data-lucide="upload-cloud" style="width:11px;height:11px;" class="me-1"></i> {{ $bulkUpload->original_filename }}
                    </span>
                </div>
                <p class="text-sm text-ink-secondary mb-0">Review the results of your product import.</p>
            </div>
            <div class="flex flex-wrap gap-2 shrink-0">
                <a href="{{ route('seller.bulk-upload.index') }}" class="btn btn-light btn-sm">
                    <i data-lucide="arrow-left" style="width:14px;height:14px;"></i> Back to Imports
                </a>
            </div>
        </div>
    </div>
</section>

<div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden mb-4">
    <div class="p-4">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-3 mb-3">
            <div class="md:col-span-3">
                <div class="p-3 bg-surface-muted rounded-xs text-center">
                    <p class="text-sm text-ink-tertiary mb-1">File</p>
                    <p class="font-semibold mb-0 text-sm">{{ $bulkUpload->original_filename }}</p>
                </div>
            </div>
            <div class="md:col-span-2">
                <div class="p-3 bg-surface-muted rounded-xs text-center">
                    <p class="text-sm text-ink-tertiary mb-1">Total</p>
                    <p class="font-semibold mb-0">{{ $bulkUpload->total_rows }}</p>
                </div>
            </div>
            <div class="md:col-span-2">
                <div class="p-3 bg-feedback-success/10 rounded-xs text-center">
                    <p class="text-sm text-ink-tertiary mb-1">Success</p>
                    <p class="font-semibold mb-0 text-feedback-success">{{ $bulkUpload->success_count }}</p>
                </div>
            </div>
            <div class="md:col-span-2">
                <div class="p-3 bg-feedback-danger/10 rounded-xs text-center">
                    <p class="text-sm text-ink-tertiary mb-1">Failed</p>
                    <p class="font-semibold mb-0 text-feedback-danger">{{ $bulkUpload->fail_count }}</p>
                </div>
            </div>
            <div class="md:col-span-3">
                <div class="p-3 bg-surface-muted rounded-xs text-center">
                    <p class="text-sm text-ink-tertiary mb-1">Status</p>
                    @php
                        $statusPill = match ($bulkUpload->status) {
                            'pending'    => ['bg-amber-50 text-amber-700', 'bg-amber-400', 'Pending'],
                            'processing' => ['bg-sky-50 text-sky-700', 'bg-sky-400', 'Processing...'],
                            'completed'  => ['bg-emerald-50 text-emerald-700', 'bg-emerald-400', 'Completed'],
                            'failed'     => ['bg-rose-50 text-rose-700', 'bg-rose-400', 'Failed'],
                            default      => ['bg-neutral-100 text-neutral-600', 'bg-neutral-400', ucfirst((string) $bulkUpload->status)],
                        };
                    @endphp
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider {{ $statusPill[0] }}">
                        @if ($bulkUpload->status === 'processing')
                            <i data-lucide="loader" class="icon-xs me-1.5"></i>
                        @else
                            <span class="w-1.5 h-1.5 rounded-full bg-current opacity-70 me-1.5" style="background: {{ $statusPill[1] }};"></span>
                        @endif
                        {{ $statusPill[2] }}
                    </span>
                </div>
            </div>
        </div>

        <div class="w-full h-2 bg-surface-muted rounded-full overflow-hidden mb-4" style="height:8px;">
            @php
                $total = $bulkUpload->total_rows > 0 ? $bulkUpload->total_rows : 1;
                $progress = (($bulkUpload->success_count + $bulkUpload->fail_count) / $total) * 100;
            @endphp
            <div class="h-full bg-feedback-success rounded-full transition-all" role="progressbar" style="width: {{ $progress }}%"></div>
        </div>

        @if($bulkUpload->status === 'processing')
        <div class="flex items-center gap-2 p-4 rounded-xs bg-feedback-info/10 border border-feedback-info text-feedback-info text-sm">
            <i data-lucide="loader" class="icon-xs me-1"></i>
            Import is currently processing. Refresh the page to see updated results.
        </div>

        <script>
            setTimeout(function() {
                location.reload();
            }, 10000);
        </script>
        @endif

        @if($bulkUpload->status === 'failed' && isset($bulkUpload->summary['error']))
        <div class="flex items-center gap-2 p-4 rounded-xs bg-feedback-danger/10 border border-feedback-danger text-feedback-danger text-sm">
            <i data-lucide="alert-circle" class="icon-xs me-1"></i>
            Import failed: {{ $bulkUpload->summary['error'] }}
        </div>
        @endif

        @if($bulkUpload->fail_count > 0)
        <div class="flex justify-between items-center mb-3">
            <h6 class="font-semibold mb-0">Failed Rows ({{ $bulkUpload->fail_count }})</h6>
            <a href="{{ route('seller.bulk-upload.downloadErrors', $bulkUpload) }}" class="btn btn-light btn-sm">
                <i data-lucide="download" class="icon-xs me-1"></i> Download Error Report
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-ink border-collapse">
                <thead>
                    <tr class="bg-surface-muted/50">
                        <th class="px-4 py-2.5 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Row</th>
                        <th class="px-4 py-2.5 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">SKU</th>
                        <th class="px-4 py-2.5 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Errors</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($failedRows as $row)
                    <tr>
                        <td class="text-sm">{{ $row->row_number }}</td>
                        <td class="text-sm">{{ $row->sku ?? 'N/A' }}</td>
                        <td>
                            <ul class="mb-0 text-sm text-feedback-danger ps-3">
                                @foreach($row->errors ?? [] as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $failedRows->links() }}
        @endif

        @if($bulkUpload->success_count > 0)
        <div class="mt-3">
            <a href="{{ route('seller.products.index') }}" class="btn btn-primary btn-sm">
                <i data-lucide="eye" class="icon-xs me-1"></i> View Products
            </a>
        </div>
        @endif
    </div>
</div>

@endsection