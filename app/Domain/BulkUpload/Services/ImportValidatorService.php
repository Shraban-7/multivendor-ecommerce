<?php

namespace App\Domain\BulkUpload\Services;

use App\Domain\Product\Models\Category;
use App\Domain\Product\Models\Product;
use Illuminate\Support\Facades\DB;

class ImportValidatorService
{
    private array $fileSkus = [];

    private array $categoryCache = [];

    private array $brandCache = [];

    public function validateRow(array $row, int $sellerId): array
    {
        $errors = [];

        $name = trim($row['name'] ?? '');
        if (empty($name)) {
            $errors[] = 'Product name is required';
        }

        if (mb_strlen($name) > 255) {
            $errors[] = 'Product name must not exceed 255 characters';
        }

        $categoryName = trim($row['category'] ?? '');
        if (empty($categoryName)) {
            $errors[] = 'Category is required';
        } else {
            $category = $this->resolveCategory($categoryName);
            if ($category === null) {
                $errors[] = "Category '{$categoryName}' not found";
            }
        }

        $price = $this->parseNumeric($row['price'] ?? '');
        if ($price === null || $price <= 0) {
            $errors[] = 'Price must be a positive number';
        }

        $costPrice = $this->parseNumeric($row['cost_price'] ?? '');
        if ($costPrice === null || $costPrice < 0) {
            $errors[] = 'Cost price must be a non-negative number';
        }

        if ($price !== null && $costPrice !== null && $price < $costPrice) {
            $errors[] = 'Price must be greater than or equal to cost price';
        }

        $comparePrice = $this->parseNumeric($row['compare_price'] ?? '');
        if ($comparePrice !== null && $price !== null && $comparePrice <= $price) {
            $errors[] = 'Compare price must be greater than selling price';
        }

        $sku = trim($row['sku'] ?? '');
        if (! empty($sku)) {
            if (in_array(strtolower($sku), $this->fileSkus)) {
                $errors[] = "Duplicate SKU '{$sku}' within the import file";
            }
            $this->fileSkus[] = strtolower($sku);

            $existing = Product::where('sku', $sku)->where('seller_id', $sellerId)->exists();
            if ($existing) {
                $errors[] = "SKU '{$sku}' already exists in your inventory";
            }
        }

        $stock = $this->parseNumeric($row['stock'] ?? '');
        if ($stock !== null && ($stock < 0 || ! is_int($stock * 1))) {
            $errors[] = 'Stock must be a non-negative integer';
        }

        $weight = $this->parseNumeric($row['weight'] ?? '');
        if ($weight !== null && $weight < 0) {
            $errors[] = 'Weight must be non-negative';
        }

        foreach (['height', 'width', 'length'] as $dim) {
            $val = $this->parseNumeric($row[$dim] ?? '');
            if ($val !== null && $val < 0) {
                $errors[] = ucfirst($dim) . ' must be non-negative';
            }
        }

        $brandName = trim($row['brand'] ?? '');
        if (! empty($brandName)) {
            $brand = $this->resolveBrand($brandName);
            if ($brand === null) {
                $errors[] = "Brand '{$brandName}' not found";
            }
        }

        $status = strtolower(trim($row['status'] ?? ''));
        if (! empty($status) && ! in_array($status, ['draft', 'active', 'pending_approval'])) {
            $errors[] = "Status must be one of: draft, active, pending_approval";
        }

        $thumbnailUrl = trim($row['thumbnail_url'] ?? '');
        if (! empty($thumbnailUrl) && ! filter_var($thumbnailUrl, FILTER_VALIDATE_URL)) {
            $errors[] = 'Thumbnail URL is not a valid URL';
        }

        $country = trim($row['country_of_origin'] ?? '');
        if (! empty($country) && mb_strlen($country) > 100) {
            $errors[] = 'Country of origin must not exceed 100 characters';
        }

        $manufacturer = trim($row['manufacturer_name'] ?? '');
        if (! empty($manufacturer) && mb_strlen($manufacturer) > 255) {
            $errors[] = 'Manufacturer name must not exceed 255 characters';
        }

        return $errors;
    }

    public function resolveCategory(string $name): ?Category
    {
        $key = strtolower(trim($name));
        if (isset($this->categoryCache[$key])) {
            return $this->categoryCache[$key];
        }

        $category = Category::where(DB::raw('LOWER(name)'), $key)->first();

        if (! $category) {
            $category = Category::where(DB::raw('LOWER(slug)'), str_replace(' ', '-', $key))->first();
        }

        $this->categoryCache[$key] = $category;

        return $category;
    }

    public function resolveBrand(string $name): ?object
    {
        $key = strtolower(trim($name));
        if (isset($this->brandCache[$key])) {
            return $this->brandCache[$key];
        }

        $brand = \App\Domain\Product\Models\Brand::where(DB::raw('LOWER(name)'), $key)->first();

        if (! $brand) {
            $brand = \App\Domain\Product\Models\Brand::where(DB::raw('LOWER(slug)'), str_replace(' ', '-', $key))->first();
        }

        $this->brandCache[$key] = $brand;

        return $brand;
    }

    public function getResolvedCategoryId(string $name): ?int
    {
        $category = $this->resolveCategory($name);

        return $category?->id;
    }

    public function getResolvedBrandId(string $name): ?int
    {
        $brand = $this->resolveBrand($name);

        return $brand?->id;
    }

    public function parseNumeric(mixed $value): ?float
    {
        if ($value === null || $value === '' || (is_string($value) && trim($value) === '')) {
            return null;
        }

        $cleaned = str_replace([',', ' '], '', (string) $value);

        if (! is_numeric($cleaned)) {
            return null;
        }

        return (float) $cleaned;
    }

    public function reset(): void
    {
        $this->fileSkus = [];
        $this->categoryCache = [];
        $this->brandCache = [];
    }
}
