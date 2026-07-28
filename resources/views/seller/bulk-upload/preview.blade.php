@extends('seller.layouts.app')
@section('title', 'Preview Import')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold mb-0 text-dark">Preview Import</h4>
    <a href="{{ route('seller.bulk-upload.index') }}" class="btn btn-light btn-sm border">
        <i data-feather="arrow-left" class="icon-xs me-1"></i> Back
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
            <div class="col-md-3">
                <div class="p-3 bg-light rounded text-center">
                    <p class="small text-muted mb-1">Total Rows</p>
                    <p class="fw-semibold mb-0">{{ $bulkUpload->total_rows }}</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 bg-light rounded text-center">
                    <p class="small text-muted mb-1">File Type</p>
                    <p class="fw-semibold mb-0 text-uppercase">{{ $bulkUpload->file_type }}</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 bg-light rounded text-center">
                    <p class="small text-muted mb-1">Status</p>
                    <span class="badge badge-soft-secondary">Pending</span>
                </div>
            </div>
        </div>

        <h6 class="fw-semibold mb-2">Columns Detected</h6>
        <div class="mb-3">
            @foreach($headers as $header)
                <span class="badge bg-light text-dark border me-1 mb-1">{{ $header }}</span>
            @endforeach
        </div>

        <h6 class="fw-semibold mb-2">Preview (first {{ count($previewRows) }} rows)</h6>
        <div class="table-responsive" style="max-height:400px;overflow-y:auto;">
            <table class="table table-bordered table-sm align-middle bg-white">
                <thead class="table-light sticky-top">
                    <tr>
                        <th class="small fw-semibold">#</th>
                        @foreach($headers as $header)
                            <th class="small fw-semibold text-muted text-nowrap">{{ $header }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($previewRows as $index => $row)
                    <tr>
                        <td class="small text-muted">{{ $index + 1 }}</td>
                        @foreach($headers as $header)
                            <td class="small text-nowrap" style="max-width:200px;overflow:hidden;text-overflow:ellipsis;">
                                {{ \Illuminate\Support\Str::limit($row[$header] ?? '', 80) }}
                            </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($bulkUpload->total_rows > count($previewRows))
            <p class="text-muted small mt-2">
                Showing {{ count($previewRows) }} of {{ $bulkUpload->total_rows }} rows
            </p>
        @endif

        <div class="mt-4 d-flex gap-2">
            <form action="{{ route('seller.bulk-upload.confirm', $bulkUpload) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary px-4">
                    <i data-feather="play" class="icon-xs me-1"></i> Start Import
                </button>
            </form>
            <a href="{{ route('seller.bulk-upload.index') }}" class="btn btn-light border px-4">
                Cancel
            </a>
        </div>
    </div>
</div>

@endsection
