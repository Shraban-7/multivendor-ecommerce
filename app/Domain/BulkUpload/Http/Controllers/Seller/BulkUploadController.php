<?php

namespace App\Domain\BulkUpload\Http\Controllers\Seller;

use App\Domain\BulkUpload\Jobs\ProcessBulkUploadJob;
use App\Domain\BulkUpload\Models\BulkUpload;
use App\Domain\BulkUpload\Models\BulkUploadRow;
use App\Domain\BulkUpload\Services\ImportParserService;
use App\Domain\BulkUpload\Services\ImportValidatorService;
use App\Domain\Vendor\Repositories\SellerRepositoryInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BulkUploadController extends Controller
{
    public function __construct(
        private readonly ImportParserService $parser,
        private readonly ImportValidatorService $validator,
        private readonly SellerRepositoryInterface $sellerRepo,
    ) {}

    public function index()
    {
        $sellerId = get_seller_id();
        $imports = BulkUpload::forSeller($sellerId)->latest()->paginate(15);
        $hasPending = BulkUpload::where('seller_id', $sellerId)
            ->whereIn('status', [BulkUpload::STATUS_PENDING, BulkUpload::STATUS_PROCESSING])
            ->exists();

        return view('seller.bulk-upload.index', compact('imports', 'hasPending'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx|max:10240',
        ]);

        $sellerId = get_seller_id();

        $pending = BulkUpload::where('seller_id', $sellerId)
            ->whereIn('status', [BulkUpload::STATUS_PENDING, BulkUpload::STATUS_PROCESSING])
            ->exists();

        if ($pending) {
            return redirect()->route('seller.bulk-upload.index')
                ->with('error', 'You already have a pending import. Please wait for it to complete.');
        }

        $file = $request->file('file');
        $extension = $file->getClientOriginalExtension();
        $fileType = $extension === 'xlsx' ? 'xlsx' : 'csv';

        $filePath = $file->store('imports/' . $sellerId, 'public');

        $parsed = $this->parser->parse(
            Storage::disk('public')->path($filePath),
            $fileType
        );

        $missingHeaders = $this->parser->getMissingHeaders($parsed['headers']);
        if (! empty($missingHeaders)) {
            Storage::disk('public')->delete($filePath);
            return redirect()->route('seller.bulk-upload.index')
                ->with('error', 'Missing required columns: ' . implode(', ', $missingHeaders));
        }

        $upload = BulkUpload::create([
            'seller_id' => $sellerId,
            'status' => BulkUpload::STATUS_PENDING,
            'file_path' => $filePath,
            'file_type' => $fileType,
            'original_filename' => $file->getClientOriginalName(),
            'total_rows' => count($parsed['rows']),
        ]);

        return redirect()->route('seller.bulk-upload.preview', $upload);
    }

    public function preview(BulkUpload $bulkUpload)
    {
        abort_unless($bulkUpload->seller_id === get_seller_id(), 403);

        $parsed = $this->parser->parse(
            Storage::disk('public')->path($bulkUpload->file_path),
            $bulkUpload->file_type
        );

        $headers = $parsed['headers'];
        $previewRows = array_slice($parsed['rows'], 0, 10);

        return view('seller.bulk-upload.preview', compact('bulkUpload', 'headers', 'previewRows'));
    }

    public function confirm(Request $request, BulkUpload $bulkUpload)
    {
        abort_unless($bulkUpload->seller_id === get_seller_id(), 403);

        if ($bulkUpload->status !== BulkUpload::STATUS_PENDING) {
            return redirect()->route('seller.bulk-upload.index')
                ->with('error', 'This import has already been processed.');
        }

        $bulkUpload->update(['status' => BulkUpload::STATUS_PROCESSING]);

        ProcessBulkUploadJob::dispatch($bulkUpload->id);

        return redirect()->route('seller.bulk-upload.show', $bulkUpload)
            ->with('success', 'Import started. Processing ' . $bulkUpload->total_rows . ' rows...');
    }

    public function show(BulkUpload $bulkUpload)
    {
        abort_unless($bulkUpload->seller_id === get_seller_id(), 403);

        $bulkUpload->loadCount(['successfulRows', 'failedRows']);

        $failedRows = $bulkUpload->failedRows()->paginate(20);

        return view('seller.bulk-upload.show', compact('bulkUpload', 'failedRows'));
    }

    public function downloadErrors(BulkUpload $bulkUpload)
    {
        abort_unless($bulkUpload->seller_id === get_seller_id(), 403);

        $failedRows = $bulkUpload->failedRows()->get();

        $headers = ['Row Number', 'SKU', 'Errors'];
        $rows = $failedRows->map(fn ($r) => [
            $r->row_number,
            $r->sku ?? 'N/A',
            is_array($r->errors) ? implode('; ', $r->errors) : $r->errors,
        ])->toArray();

        $csv = implode(',', $headers) . "\n";
        foreach ($rows as $row) {
            $escaped = array_map(fn ($v) => '"' . str_replace('"', '""', $v) . '"', $row);
            $csv .= implode(',', $escaped) . "\n";
        }

        $filename = 'import-errors-' . $bulkUpload->id . '-' . now()->format('YmdHis') . '.csv';

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
