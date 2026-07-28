<?php

namespace App\Domain\BulkUpload\Services;

use App\Domain\BulkUpload\Models\BulkUpload;
use App\Domain\BulkUpload\Models\BulkUploadRow;
use App\Domain\Product\Models\Product;
use App\Domain\Product\Models\Tag;
use App\Domain\Product\Services\StockManagerService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImportProcessorService
{
    public function __construct(
        private readonly ImportValidatorService $validatorService,
        private readonly StockManagerService $stockManager,
    ) {}

    public function processRow(array $row, int $sellerId, string $username, int $bulkUploadId, int $rowNumber): BulkUploadRow
    {
        $errors = $this->validatorService->validateRow($row, $sellerId);

        DB::beginTransaction();
        try {
            $record = BulkUploadRow::create([
                'bulk_upload_id' => $bulkUploadId,
                'row_number' => $rowNumber,
                'sku' => trim($row['sku'] ?? ''),
                'status' => BulkUploadRow::STATUS_PENDING,
                'data' => $row,
            ]);

            if (! empty($errors)) {
                $record->update([
                    'status' => BulkUploadRow::STATUS_FAILED,
                    'errors' => $errors,
                ]);
            } else {
                $product = $this->createProduct($row, $sellerId, $username);

                $record->update([
                    'status' => BulkUploadRow::STATUS_SUCCESS,
                    'product_id' => $product->id,
                    'sku' => $product->sku,
                ]);
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error("Bulk upload row {$rowNumber} failed: " . $e->getMessage(), [
                'bulk_upload_id' => $bulkUploadId,
                'row' => $row,
            ]);

            $record = BulkUploadRow::create([
                'bulk_upload_id' => $bulkUploadId,
                'row_number' => $rowNumber,
                'sku' => trim($row['sku'] ?? ''),
                'status' => BulkUploadRow::STATUS_FAILED,
                'errors' => [$e->getMessage()],
                'data' => $row,
            ]);
        }

        return $record;
    }

    private function createProduct(array $row, int $sellerId, string $username): Product
    {
        $imageFolder = "{$username}/products";

        $sku = trim($row['sku'] ?? '');
        if (empty($sku)) {
            $sku = Product::generateSku($sellerId);
        }

        $categoryId = $this->validatorService->getResolvedCategoryId($row['category'] ?? '');
        $brandId = null;
        if (! empty(trim($row['brand'] ?? ''))) {
            $brandId = $this->validatorService->getResolvedBrandId($row['brand'] ?? '');
        }

        $status = Product::STATUS_PENDING_APPROVAL;
        $statusStr = strtolower(trim($row['status'] ?? ''));
        if ($statusStr === 'draft') {
            $status = Product::STATUS_DRAFT;
        } elseif ($statusStr === 'active') {
            $status = Product::STATUS_ACTIVE;
        }

        $stock = (int) ($this->validatorService->parseNumeric($row['stock'] ?? '') ?? 0);

        $specs = [];
        if (! empty(trim($row['specifications'] ?? ''))) {
            $lines = explode("\n", $row['specifications']);
            foreach ($lines as $line) {
                $line = trim($line);
                if (str_contains($line, ':')) {
                    $parts = explode(':', $line, 2);
                    $key = trim($parts[0]);
                    $value = trim($parts[1]);
                    if ($key && $value) {
                        $specs[$key] = $value;
                    }
                }
            }
        }

        $slug = str_slug('products', 'slug', trim($row['name']));

        $product = Product::create([
            'seller_id' => $sellerId,
            'category_id' => $categoryId,
            'subcategory_id' => null,
            'brand_id' => $brandId,
            'name' => trim($row['name']),
            'slug' => $slug,
            'sku' => $sku,
            'barcode' => trim($row['barcode'] ?? '') ?: null,
            'short_description' => trim($row['short_description'] ?? '') ?: null,
            'description' => $row['description'] ?? null,
            'price' => $this->validatorService->parseNumeric($row['price'] ?? ''),
            'compare_price' => $this->validatorService->parseNumeric($row['compare_price'] ?? ''),
            'cost_price' => $this->validatorService->parseNumeric($row['cost_price'] ?? 0),
            'stock_in' => $stock,
            'stock_out' => 0,
            'weight' => $this->validatorService->parseNumeric($row['weight'] ?? ''),
            'height' => $this->validatorService->parseNumeric($row['height'] ?? ''),
            'width' => $this->validatorService->parseNumeric($row['width'] ?? ''),
            'length' => $this->validatorService->parseNumeric($row['length'] ?? ''),
            'country_of_origin' => trim($row['country_of_origin'] ?? '') ?: null,
            'manufacturer_name' => trim($row['manufacturer_name'] ?? '') ?: null,
            'manufacturer_details' => trim($row['manufacturer_details'] ?? '') ?: null,
            'specifications' => $specs,
            'status' => $status,
            'is_visible' => false,
            'low_stock_quantity' => 0,
            'unit_id' => null,
            'unit_value' => null,
            'payment_type' => null,
            'is_featured' => 0,
            'best_selling' => 0,
            'is_trending' => 0,
        ]);

        $thumbnailUrl = trim($row['thumbnail_url'] ?? '');
        if (! empty($thumbnailUrl)) {
            $path = $this->downloadImage($thumbnailUrl, $imageFolder);
            if ($path) {
                $product->update(['thumbnail' => $path]);
            }
        }

        $galleryUrls = trim($row['gallery_urls'] ?? '');
        if (! empty($galleryUrls)) {
            $urls = preg_split('/[|,]/', $galleryUrls);
            $sortOrder = 0;
            foreach ($urls as $url) {
                $url = trim($url);
                if (empty($url)) continue;
                $path = $this->downloadImage($url, $imageFolder);
                if ($path) {
                    $product->images()->create([
                        'image' => $path,
                        'sort_order' => $sortOrder++,
                    ]);
                }
            }
        }

        $tags = trim($row['tags'] ?? '');
        if (! empty($tags)) {
            $tagNames = array_map('trim', explode(',', $tags));
            $tagIds = [];
            foreach ($tagNames as $name) {
                if (empty($name)) continue;
                $tag = Tag::firstOrCreate(
                    ['slug' => Str::slug($name)],
                    ['name' => $name]
                );
                $tagIds[] = $tag->id;
            }
            $product->tags()->sync($tagIds);
        }

        if ($stock > 0) {
            $this->stockManager->incrementStock($product, null, $stock, 'Initial stock from bulk import');
        }

        return $product;
    }

    private function downloadImage(string $url, string $folder): ?string
    {
        try {
            $response = \Illuminate\Support\Facades\Http::timeout(15)->get($url);

            if (! $response->ok()) {
                return null;
            }

            $imageContent = $response->body();
            $extension = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION);
            $extension = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'webp', 'gif']) ? $extension : 'jpg';

            $fileName = Str::uuid() . '.' . $extension;
            $path = "{$folder}/{$fileName}";

            Storage::disk('public')->put($path, $imageContent);

            return $path;
        } catch (\Throwable $e) {
            Log::warning("Failed to download image from URL: {$url}", ['error' => $e->getMessage()]);
            return null;
        }
    }

    public function completeUpload(BulkUpload $upload): void
    {
        $successCount = $upload->successfulRows()->count();
        $failCount = $upload->failedRows()->count();

        $failedRows = $upload->failedRows()->get(['row_number', 'sku', 'errors']);

        $upload->update([
            'status' => BulkUpload::STATUS_COMPLETED,
            'success_count' => $successCount,
            'fail_count' => $failCount,
            'summary' => [
                'failed_rows' => $failedRows->toArray(),
                'total_processed' => $successCount + $failCount,
                'completion_time' => now()->toIso8601String(),
            ],
        ]);
    }

    public function markFailed(BulkUpload $upload, string $error): void
    {
        $upload->update([
            'status' => BulkUpload::STATUS_FAILED,
            'summary' => [
                'error' => $error,
                'failed_at' => now()->toIso8601String(),
            ],
        ]);
    }
}
