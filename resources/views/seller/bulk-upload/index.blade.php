@extends('seller.layouts.app')
@section('title', 'Bulk Product Upload')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold mb-0 text-dark">Bulk Product Upload</h4>
</div>

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <h5 class="card-title mb-3">Upload Product File</h5>
        <p class="text-muted small mb-3">
            Upload a CSV or XLSX file containing your products. 
            <a href="#sample" data-bs-toggle="collapse">View sample format</a>
        </p>

        @if($hasPending)
            <div class="alert alert-warning">
                <i data-feather="alert-triangle" class="icon-xs me-1"></i>
                You have a pending import. Please wait for it to complete before uploading another file.
            </div>
        @else
        <form action="{{ route('seller.bulk-upload.upload') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <div class="upload-zone border border-2 border-dashed rounded-3 p-5 text-center" id="uploadZone" style="cursor:pointer; background: #f8f9fa;">
                    <i data-feather="upload" style="width:48px;height:48px;color:var(--bs-primary)"></i>
                    <p class="mt-2 mb-1 fw-semibold">Click to upload or drag & drop</p>
                    <p class="text-muted small mb-0">CSV or XLSX files up to 10MB</p>
                    <input type="file" name="file" id="fileInput" class="d-none" accept=".csv,.xlsx,.txt">
                </div>
                <div id="fileSelected" class="d-none mt-2 p-2 bg-light rounded">
                    <i data-feather="file-text" class="icon-xs me-1"></i>
                    <span id="fileName"></span>
                </div>
                @error('file')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="btn btn-primary px-4">
                <i data-feather="upload" class="icon-xs me-1"></i> Upload & Preview
            </button>
        </form>
        @endif

        <div class="collapse mt-4" id="sample">
            <div class="bg-light p-3 rounded small">
                <p class="fw-semibold mb-2">Required columns: <code>name</code>, <code>category</code>, <code>price</code>, <code>cost_price</code></p>
                <p class="mb-2">Optional columns: <code>sku</code>, <code>barcode</code>, <code>subcategory</code>, <code>brand</code>, <code>short_description</code>, <code>description</code>, <code>compare_price</code>, <code>stock</code>, <code>weight</code>, <code>height</code>, <code>width</code>, <code>length</code>, <code>unit</code>, <code>status</code>, <code>tags</code>, <code>thumbnail_url</code>, <code>gallery_urls</code>, <code>country_of_origin</code>, <code>manufacturer_name</code>, <code>manufacturer_details</code>, <code>specifications</code></p>
                <pre class="mb-0">name,category,subcategory,brand,price,cost_price,compare_price,stock,sku,status,tags,thumbnail_url
Example Product,Electronics,Mobile Phones,Samsung,25000,20000,30000,100,SP-001,active,smartphone,https://example.com/img.jpg</pre>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <h5 class="card-title mb-3">Import History</h5>

        @if($imports->count() === 0)
            <p class="text-muted mb-0">No imports yet.</p>
        @else
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle bg-white">
                <thead class="table-light">
                    <tr>
                        <th class="small fw-semibold text-muted">File</th>
                        <th class="small fw-semibold text-muted">Rows</th>
                        <th class="small fw-semibold text-muted">Success</th>
                        <th class="small fw-semibold text-muted">Failed</th>
                        <th class="small fw-semibold text-muted">Status</th>
                        <th class="small fw-semibold text-muted">Date</th>
                        <th class="small fw-semibold text-muted">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($imports as $import)
                    <tr>
                        <td class="small">{{ $import->original_filename }}</td>
                        <td class="text-center small">{{ $import->total_rows }}</td>
                        <td class="text-center small text-success fw-semibold">{{ $import->success_count }}</td>
                        <td class="text-center small text-danger fw-semibold">{{ $import->fail_count }}</td>
                        <td>
                            @if($import->status === 'pending')
                                <span class="badge badge-soft-secondary">Pending</span>
                            @elseif($import->status === 'processing')
                                <span class="badge badge-soft-warning">
                                    <i data-feather="loader" class="icon-xs"></i> Processing
                                </span>
                            @elseif($import->status === 'completed')
                                <span class="badge badge-soft-success">Completed</span>
                            @elseif($import->status === 'failed')
                                <span class="badge badge-soft-danger">Failed</span>
                            @elseif($import->status === 'cancelled')
                                <span class="badge badge-soft-secondary">Cancelled</span>
                            @endif
                        </td>
                        <td class="small">{{ $import->created_at->format('d/m/Y h:ia') }}</td>
                        <td>
                            <a href="{{ route('seller.bulk-upload.show', $import) }}" class="btn btn-light btn-sm border">
                                <i data-feather="eye" class="icon-xs"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $imports->links() }}
        @endif
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function () {
        const zone = document.getElementById('uploadZone');
        const input = document.getElementById('fileInput');

        zone.addEventListener('click', () => input.click());

        zone.addEventListener('dragover', (e) => {
            e.preventDefault();
            zone.classList.add('border-primary');
            zone.style.background = '#eef7ff';
        });

        zone.addEventListener('dragleave', () => {
            zone.classList.remove('border-primary');
            zone.style.background = '#f8f9fa';
        });

        zone.addEventListener('drop', (e) => {
            e.preventDefault();
            zone.classList.remove('border-primary');
            zone.style.background = '#f8f9fa';
            if (e.dataTransfer.files.length) {
                input.files = e.dataTransfer.files;
                showFileName(input.files[0].name);
            }
        });

        input.addEventListener('change', () => {
            if (input.files.length) {
                showFileName(input.files[0].name);
            }
        });

        function showFileName(name) {
            zone.classList.add('d-none');
            const div = document.getElementById('fileSelected');
            div.classList.remove('d-none');
            document.getElementById('fileName').textContent = name;
        }
    });
</script>
@endpush
@endsection
