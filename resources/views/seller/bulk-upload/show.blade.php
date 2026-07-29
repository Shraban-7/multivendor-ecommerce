@extends('seller.layouts.app')
@section('title', 'Import Details')
@section('content')

<div class="flex justify-between items-center mb-3">
    <h4 class="font-bold mb-0 text-ink">Import Details</h4>
    <a href="{{ route('seller.bulk-upload.index') }}" class="btn btn-light btn-sm">
        <i data-lucide="arrow-left" class="icon-xs me-1"></i> Back to Imports
    </a>
</div>

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
                    @if($bulkUpload->status === 'pending')
                        <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-xs badge-soft-secondary">Pending</span>
                    @elseif($bulkUpload->status === 'processing')
                        <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-xs badge-soft-warning">
                            <i data-lucide="loader" class="icon-xs"></i> Processing...
                        </span>
                    @elseif($bulkUpload->status === 'completed')
                        <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-xs badge-soft-success">Completed</span>
                    @elseif($bulkUpload->status === 'failed')
                        <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-xs badge-soft-danger">Failed</span>
                    @endif
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
            <table class="w-full text-left text-sm text-ink border-collapse table-bordered table-hover align-middle bg-white">
                <thead class="bg-surface-muted">
                    <tr>
                        <th class="text-sm font-semibold text-ink-tertiary">Row</th>
                        <th class="text-sm font-semibold text-ink-tertiary">SKU</th>
                        <th class="text-sm font-semibold text-ink-tertiary">Errors</th>
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