@extends('seller.layouts.app')
@section('title', 'Bulk Product Upload')
@section('content')

<div class="flex justify-between items-center mb-3">
    <h4 class="font-bold mb-0 text-ink">Bulk Product Upload</h4>
</div>

@if(session('error'))
    <div class="flex items-center gap-2 p-4 rounded-xs bg-feedback-danger/10 border border-feedback-danger text-feedback-danger text-sm mb-3">{{ session('error') }}</div>
@endif

@if(session('success'))
    <div class="flex items-center gap-2 p-4 rounded-xs bg-feedback-success/10 border border-feedback-success text-feedback-success text-sm mb-3">{{ session('success') }}</div>
@endif

<div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden mb-4">
    <div class="p-4">
        <h5 class="text-lg font-semibold mb-3">Upload Product File</h5>
        <p class="text-ink-tertiary text-sm mb-3">
            Upload a CSV or XLSX file containing your products. 
            <a href="#sample" data-bs-toggle="collapse">View sample format</a>
        </p>

        @if($hasPending)
            <div class="flex items-center gap-2 p-4 rounded-xs bg-feedback-warning/10 border border-feedback-warning text-feedback-warning text-sm">
                <i data-feather="alert-triangle" class="icon-xs me-1"></i>
                You have a pending import. Please wait for it to complete before uploading another file.
            </div>
        @else
        <form action="{{ route('seller.bulk-upload.upload') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <div class="upload-zone border border-2 border-dashed rounded-md p-5 text-center" id="uploadZone" style="cursor:pointer; background: #f8f9fa;">
                    <i data-feather="upload" style="width:48px;height:48px;color:var(--bs-primary)"></i>
                    <p class="mt-2 mb-1 font-semibold">Click to upload or drag & drop</p>
                    <p class="text-ink-tertiary text-sm mb-0">CSV or XLSX files up to 10MB</p>
                    <input type="file" name="file" id="fileInput" class="hidden" accept=".csv,.xlsx,.txt">
                </div>
                <div id="fileSelected" class="d-none mt-2 p-2 bg-surface-muted rounded-xs">
                    <i data-feather="file-text" class="icon-xs me-1"></i>
                    <span id="fileName"></span>
                </div>
                @error('file')<div class="text-feedback-danger text-sm mt-1">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-brand-deep text-white text-sm font-medium rounded-xs hover:bg-brand focus:outline-none focus:ring-2 focus:ring-brand-tint disabled:opacity-50 transition-colors">
                <i data-feather="upload" class="icon-xs me-1"></i> Upload & Preview
            </button>
        </form>
        @endif

        <div class="collapse mt-4" id="sample">
            <div class="bg-surface-muted p-3 rounded-xs text-sm">
                <p class="font-semibold mb-2">Required columns: <code>name</code>, <code>category</code>, <code>price</code>, <code>cost_price</code></p>
                <p class="mb-2">Optional columns: <code>sku</code>, <code>barcode</code>, <code>subcategory</code>, <code>brand</code>, <code>short_description</code>, <code>description</code>, <code>compare_price</code>, <code>stock</code>, <code>weight</code>, <code>height</code>, <code>width</code>, <code>length</code>, <code>unit</code>, <code>status</code>, <code>tags</code>, <code>thumbnail_url</code>, <code>gallery_urls</code>, <code>country_of_origin</code>, <code>manufacturer_name</code>, <code>manufacturer_details</code>, <code>specifications</code></p>
                <pre class="mb-0">name,category,subcategory,brand,price,cost_price,compare_price,stock,sku,status,tags,thumbnail_url
Example Product,Electronics,Mobile Phones,Samsung,25000,20000,30000,100,SP-001,active,smartphone,https://example.com/img.jpg</pre>
            </div>
        </div>
    </div>
</div>

<div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
    <div class="p-4">
        <h5 class="text-lg font-semibold mb-3">Import History</h5>

        @if($imports->count() === 0)
            <p class="text-ink-tertiary mb-0">No imports yet.</p>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-ink border-collapse table-bordered table-hover align-middle bg-white">
                <thead class="bg-surface-muted">
                    <tr>
                        <th class="text-sm font-semibold text-ink-tertiary">File</th>
                        <th class="text-sm font-semibold text-ink-tertiary">Rows</th>
                        <th class="text-sm font-semibold text-ink-tertiary">Success</th>
                        <th class="text-sm font-semibold text-ink-tertiary">Failed</th>
                        <th class="text-sm font-semibold text-ink-tertiary">Status</th>
                        <th class="text-sm font-semibold text-ink-tertiary">Date</th>
                        <th class="text-sm font-semibold text-ink-tertiary">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($imports as $import)
                    <tr>
                        <td class="text-sm">{{ $import->original_filename }}</td>
                        <td class="text-center text-sm">{{ $import->total_rows }}</td>
                        <td class="text-center text-sm text-feedback-success font-semibold">{{ $import->success_count }}</td>
                        <td class="text-center text-sm text-feedback-danger font-semibold">{{ $import->fail_count }}</td>
                        <td>
                            @if($import->status === 'pending')
                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-xs badge-soft-secondary">Pending</span>
                            @elseif($import->status === 'processing')
                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-xs badge-soft-warning">
                                    <i data-feather="loader" class="icon-xs"></i> Processing
                                </span>
                            @elseif($import->status === 'completed')
                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-xs badge-soft-success">Completed</span>
                            @elseif($import->status === 'failed')
                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-xs badge-soft-danger">Failed</span>
                            @elseif($import->status === 'cancelled')
                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-xs badge-soft-secondary">Cancelled</span>
                            @endif
                        </td>
                        <td class="text-sm">{{ $import->created_at->format('d/m/Y h:ia') }}</td>
                        <td>
                            <a href="{{ route('seller.bulk-upload.show', $import) }}" class="inline-flex items-center justify-center px-3 py-1.5 bg-surface-muted text-ink text-sm font-medium border border-border rounded-xs hover:bg-border/30 focus:outline-none transition-colors">
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