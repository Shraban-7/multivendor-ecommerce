@extends('seller.layouts.app')
@section('title', 'Import Details')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold mb-0 text-dark">Import Details</h4>
    <a href="{{ route('seller.bulk-upload.index') }}" class="btn btn-light btn-sm border">
        <i data-feather="arrow-left" class="icon-xs me-1"></i> Back to Imports
    </a>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <div class="row g-3 mb-3">
            <div class="col-md-3">
                <div class="p-3 bg-light rounded text-center">
                    <p class="small text-muted mb-1">File</p>
                    <p class="fw-semibold mb-0 small">{{ $bulkUpload->original_filename }}</p>
                </div>
            </div>
            <div class="col-md-2">
                <div class="p-3 bg-light rounded text-center">
                    <p class="small text-muted mb-1">Total</p>
                    <p class="fw-semibold mb-0">{{ $bulkUpload->total_rows }}</p>
                </div>
            </div>
            <div class="col-md-2">
                <div class="p-3 bg-success-subtle rounded text-center">
                    <p class="small text-muted mb-1">Success</p>
                    <p class="fw-semibold mb-0 text-success">{{ $bulkUpload->success_count }}</p>
                </div>
            </div>
            <div class="col-md-2">
                <div class="p-3 bg-danger-subtle rounded text-center">
                    <p class="small text-muted mb-1">Failed</p>
                    <p class="fw-semibold mb-0 text-danger">{{ $bulkUpload->fail_count }}</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 bg-light rounded text-center">
                    <p class="small text-muted mb-1">Status</p>
                    @if($bulkUpload->status === 'pending')
                        <span class="badge badge-soft-secondary">Pending</span>
                    @elseif($bulkUpload->status === 'processing')
                        <span class="badge badge-soft-warning">
                            <i data-feather="loader" class="icon-xs"></i> Processing...
                        </span>
                    @elseif($bulkUpload->status === 'completed')
                        <span class="badge badge-soft-success">Completed</span>
                    @elseif($bulkUpload->status === 'failed')
                        <span class="badge badge-soft-danger">Failed</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="progress mb-4" style="height:8px;">
            @php
                $total = $bulkUpload->total_rows > 0 ? $bulkUpload->total_rows : 1;
                $progress = (($bulkUpload->success_count + $bulkUpload->fail_count) / $total) * 100;
            @endphp
            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $progress }}%"></div>
        </div>

        @if($bulkUpload->status === 'processing')
        <div class="alert alert-info">
            <i data-feather="loader" class="icon-xs me-1"></i>
            Import is currently processing. Refresh the page to see updated results.
        </div>

        <script>
            setTimeout(function() {
                location.reload();
            }, 10000);
        </script>
        @endif

        @if($bulkUpload->status === 'failed' && isset($bulkUpload->summary['error']))
        <div class="alert alert-danger">
            <i data-feather="alert-circle" class="icon-xs me-1"></i>
            Import failed: {{ $bulkUpload->summary['error'] }}
        </div>
        @endif

        @if($bulkUpload->fail_count > 0)
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-semibold mb-0">Failed Rows ({{ $bulkUpload->fail_count }})</h6>
            <a href="{{ route('seller.bulk-upload.downloadErrors', $bulkUpload) }}" class="btn btn-light btn-sm border">
                <i data-feather="download" class="icon-xs me-1"></i> Download Error Report
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle bg-white">
                <thead class="table-light">
                    <tr>
                        <th class="small fw-semibold text-muted">Row</th>
                        <th class="small fw-semibold text-muted">SKU</th>
                        <th class="small fw-semibold text-muted">Errors</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($failedRows as $row)
                    <tr>
                        <td class="small">{{ $row->row_number }}</td>
                        <td class="small">{{ $row->sku ?? 'N/A' }}</td>
                        <td>
                            <ul class="mb-0 small text-danger ps-3">
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
                <i data-feather="eye" class="icon-xs me-1"></i> View Products
            </a>
        </div>
        @endif
    </div>
</div>

@endsection
