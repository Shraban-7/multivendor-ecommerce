@php
    $counts = $counts ?? ['total' => 0, 'completed' => 0, 'failed' => 0, 'rows' => 0];
@endphp
@extends('seller.layouts.app')
@section('title', 'Bulk Product Upload')

@section('content')

<section class="bg-white rounded-sm shadow-sm overflow-hidden mb-3 relative">
    <div class="absolute top-0 left-0 right-0 h-1" style="background: linear-gradient(90deg, #06b6d4, #38bdf8, #7dd3fc);"></div>
    <div class="p-5 lg:p-6 pt-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="min-w-0">
                <nav class="flex items-center gap-1 mb-2 text-xs text-ink-tertiary">
                    <i data-lucide="upload" class="text-feedback-info" style="width:12px;height:12px;"></i>
                    <span>Workspace</span>
                    <i data-lucide="chevron-right" style="width:12px;height:12px;"></i>
                    <span class="text-ink-soft font-semibold">Bulk Upload</span>
                </nav>
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <h1 class="text-xl font-bold text-ink-emphasis mb-0">Bulk Product Upload</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-[#06b6d4]/15 text-[#06b6d4]">
                        <i data-lucide="file-spreadsheet" style="width:11px;height:11px;" class="me-1"></i> Catalog Importer
                    </span>
                </div>
                <p class="text-sm text-ink-secondary mb-0">Import product lists from CSV or XLSX files in bulk.</p>
            </div>
        </div>
    </div>
</section>

@if(session('error'))
    <div class="flex items-center gap-2 p-4 rounded-sm bg-feedback-danger/10 text-feedback-danger text-sm mb-3">{{ session('error') }}</div>
@endif
@if(session('success'))
    <div class="flex items-center gap-2 p-4 rounded-sm bg-feedback-success/10 text-feedback-success text-sm mb-3">{{ session('success') }}</div>
@endif

{{-- ═══ UPLOAD CARD ═══ --}}
<section class="bg-white rounded-sm shadow-sm overflow-hidden mb-3">
    <div class="px-5 py-3 bg-surface-muted flex items-center gap-2">
        <i data-lucide="upload" style="width:14px;height:14px;" class="text-ink-tertiary"></i>
        <h3 class="text-sm font-bold text-ink-emphasis mb-0">Upload Product File</h3>
    </div>
    <div class="p-5 border-t border-border">
        <p class="text-sm text-ink-soft mb-3">
            Upload a CSV or XLSX file containing your products. <a href="#sample" data-bs-toggle="collapse" class="text-brand-deep hover:underline">View sample format</a>
        </p>

        @if($hasPending)
            <div class="flex items-start gap-2 p-4 rounded-xs bg-feedback-warning/10 text-feedback-warning text-sm">
                <i data-lucide="alert-triangle" style="width:16px;height:16px;" class="shrink-0 mt-0.5"></i>
                <div>You have a pending import. Please wait for it to complete before uploading another file.</div>
            </div>
        @else
            <form action="{{ route('seller.bulk-upload.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="upload-zone rounded-sm p-8 text-center cursor-pointer transition-colors" id="uploadZone" style="background: var(--bs-surface-muted, #FAFAFA); border: 2px dashed #E5E5E5;">
                    <i data-lucide="upload" style="width:48px;height:48px;color:#06b6d4"></i>
                    <p class="mt-3 mb-1 font-semibold text-ink-emphasis">Click to upload or drag &amp; drop</p>
                    <p class="text-ink-tertiary text-sm mb-0">CSV or XLSX files up to 10MB</p>
                    <input type="file" name="file" id="fileInput" class="hidden" accept=".csv,.xlsx,.txt">
                </div>
                <div id="fileSelected" class="d-none mt-2 p-2 bg-surface-muted rounded-xs text-sm flex items-center gap-1.5">
                    <i data-lucide="file-text" style="width:14px;height:14px;" class="text-feedback-info"></i>
                    <span id="fileName" class="font-medium text-ink-emphasis"></span>
                </div>
                @error('file')<div class="text-feedback-danger text-sm mt-1">{{ $message }}</div>@enderror
                <div class="mt-3 flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i data-lucide="upload" style="width:14px;height:14px;"></i> Upload &amp; Preview
                    </button>
                </div>
            </form>
        @endif

        <div class="collapse mt-4" id="sample">
            <div class="bg-surface-muted p-3 rounded-xs text-sm">
                <p class="font-semibold mb-2 text-ink-emphasis">Required columns: <code>name</code>, <code>category</code>, <code>price</code>, <code>cost_price</code></p>
                <p class="mb-2 text-ink-secondary">Optional columns: <code>sku</code>, <code>barcode</code>, <code>subcategory</code>, <code>brand</code>, <code>short_description</code>, <code>description</code>, <code>compare_price</code>, <code>stock</code>, <code>weight</code>, <code>height</code>, <code>width</code>, <code>length</code>, <code>unit</code>, <code>status</code>, <code>tags</code>, <code>thumbnail_url</code>, <code>gallery_urls</code>, <code>country_of_origin</code>, <code>manufacturer_name</code>, <code>manufacturer_details</code>, <code>specifications</code></p>
                <pre class="mb-0 text-ink-secondary">name,category,subcategory,brand,price,cost_price,compare_price,stock,sku,status,tags,thumbnail_url
Example Product,Electronics,Mobile Phones,Samsung,25000,20000,30000,100,SP-001,active,smartphone,https://example.com/img.jpg</pre>
            </div>
        </div>
    </div>
</section>

{{-- ═══ IMPORT HISTORY ═══ --}}
@php
    $tiles = [
        ['key' => 'total',     'label' => 'Total Imports', 'top' => '#06b6d4', 'text' => 'text-[#06b6d4]',         'icon' => 'file-spreadsheet'],
        ['key' => 'completed', 'label' => 'Completed',       'top' => '#10b981', 'text' => 'text-feedback-success',  'icon' => 'check-circle-2'],
        ['key' => 'failed',    'label' => 'Failed',          'top' => '#ef4444', 'text' => 'text-feedback-danger',   'icon' => 'x-circle'],
        ['key' => 'rows',      'label' => 'Rows Imported',   'top' => '#0ea5e9', 'text' => 'text-feedback-info',     'icon' => 'database'],
    ];
@endphp
<section class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-3">
    @foreach ($tiles as $tile)
        <article class="bg-white rounded-sm shadow-sm overflow-hidden relative">
            <div class="absolute top-0 left-0 right-0 h-1" style="background-color: {{ $tile['top'] }};"></div>
            <div class="p-4 pt-5">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider">{{ $tile['label'] }}</span>
                    <i data-lucide="{{ $tile['icon'] }}" class="text-ink-tertiary" style="width:14px;height:14px;"></i>
                </div>
                <h3 class="text-2xl font-bold {{ $tile['text'] }} mb-0">{{ number_format($counts[$tile['key']] ?? 0) }}</h3>
            </div>
        </article>
    @endforeach
</section>

<section class="bg-white rounded-sm shadow-sm overflow-hidden">
    <div class="px-5 py-3 bg-surface-muted flex items-center gap-2">
        <i data-lucide="history" style="width:14px;height:14px;" class="text-ink-tertiary"></i>
        <h3 class="text-sm font-bold text-ink-emphasis mb-0">Import History</h3>
    </div>

    <div class="overflow-x-auto px-4 pb-4">
        <table class="w-full text-left text-sm border-collapse">
            <thead class="bg-surface-muted">
                <tr>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">File</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary text-center">Rows</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary text-center">Success</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary text-center">Failed</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Status</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Date</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($imports as $import)
                    @php
                        $pillBg = match ($import->status) {
                            'pending'    => 'bg-surface-muted text-ink-secondary',
                            'processing' => 'bg-feedback-warning/15 text-feedback-warning',
                            'completed'  => 'bg-feedback-success/15 text-feedback-success',
                            'failed'     => 'bg-feedback-danger/15 text-feedback-danger',
                            'cancelled'  => 'bg-surface-muted text-ink-tertiary',
                            default      => 'bg-surface-muted text-ink-tertiary',
                        };
                        $pillLabel = ucfirst($import->status);
                    @endphp
                    <tr class="border-t border-border hover:bg-surface-muted/40 transition-colors">
                        <td class="px-4 py-3 text-sm text-ink-emphasis">
                            <i data-lucide="file-text" style="width:13px;height:13px;" class="me-2 text-ink-tertiary align-text-bottom"></i>
                            {{ $import->original_filename }}
                        </td>
                        <td class="px-4 py-3 text-center font-semibold text-ink-emphasis">{{ $import->total_rows }}</td>
                        <td class="px-4 py-3 text-center font-semibold text-feedback-success">{{ $import->success_count }}</td>
                        <td class="px-4 py-3 text-center font-semibold text-feedback-danger">{{ $import->fail_count }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider {{ $pillBg }}">
                                @if ($import->status === 'processing')
                                    <i data-lucide="loader-circle" style="width:11px;height:11px;" class="me-1 animate-spin"></i>
                                @endif
                                {{ $pillLabel }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-xs text-ink-secondary">
                            <i data-lucide="calendar" style="width:11px;height:11px;" class="me-1 align-text-bottom text-ink-tertiary"></i>
                            {{ $import->created_at->format('d M Y · H:i') }}
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('seller.bulk-upload.show', $import) }}" class="btn btn-light btn-sm">
                                <i data-lucide="eye" style="width:13px;height:13px;"></i> View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="py-10 text-center">
                                <i data-lucide="file-x" class="text-ink-tertiary mx-auto mb-2" style="width:36px;height:36px;"></i>
                                <p class="text-ink-soft font-semibold mb-1">No imports yet</p>
                                <p class="text-ink-tertiary text-xs">Upload your first product list above to get started.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(method_exists($imports, 'hasPages') && $imports->hasPages())
        <div class="flex justify-end p-4 border-t border-border">
            {{ $imports->links() }}
        </div>
    @endif
</section>

@push('scripts')
<script>
    $(document).ready(function () {
        const zone = document.getElementById('uploadZone');
        const input = document.getElementById('fileInput');

        if (zone && input) {
            zone.addEventListener('click', () => input.click());

            zone.addEventListener('dragover', (e) => {
                e.preventDefault();
                zone.style.borderColor = '#06b6d4';
                zone.style.background = '#eef7ff';
            });

            zone.addEventListener('dragleave', () => {
                zone.style.borderColor = '#E5E5E5';
                zone.style.background = '#FAFAFA';
            });

            zone.addEventListener('drop', (e) => {
                e.preventDefault();
                zone.style.borderColor = '#E5E5E5';
                zone.style.background = '#FAFAFA';
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
        }
    });
</script>
@endpush

@endsection
