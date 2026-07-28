<?php

namespace App\Domain\BulkUpload\Jobs;

use App\Domain\BulkUpload\Models\BulkUpload;
use App\Domain\BulkUpload\Services\ImportParserService;
use App\Domain\BulkUpload\Services\ImportProcessorService;
use App\Domain\BulkUpload\Services\ImportValidatorService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ProcessBulkUploadJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 3600;

    public int $tries = 1;

    public function __construct(
        private readonly int $bulkUploadId,
    ) {}

    public function handle(
        ImportParserService $parser,
        ImportValidatorService $validator,
        ImportProcessorService $processor,
    ): void {
        $upload = BulkUpload::findOrFail($this->bulkUploadId);

        $upload->update(['status' => BulkUpload::STATUS_PROCESSING]);

        try {
            $parsed = $parser->parse(
                Storage::disk('public')->path($upload->file_path),
                $upload->file_type
            );

            $rows = $parsed['rows'];
            $upload->update(['total_rows' => count($rows)]);

            $seller = $upload->seller;
            $username = $seller->username;

            $chunkSize = 100;
            $chunks = array_chunk($rows, $chunkSize);

            $currentRowNumber = 1;
            foreach ($chunks as $chunk) {
                foreach ($chunk as $row) {
                    $processor->processRow(
                        $row,
                        $upload->seller_id,
                        $username,
                        $upload->id,
                        $currentRowNumber
                    );
                    $currentRowNumber++;
                }

                $upload->refresh();
            }

            $validator->reset();
            $processor->completeUpload($upload);
        } catch (\Throwable $e) {
            $processor->markFailed($upload, $e->getMessage());
            throw $e;
        }
    }
}
