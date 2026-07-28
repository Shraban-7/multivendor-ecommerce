<?php

namespace App\Domain\Bundle\Services;

use App\Domain\Bundle\Models\Bundle;
use App\Domain\Bundle\Models\BundleImage;
use App\Domain\Bundle\Models\BundleItem;
use App\Domain\Bundle\Models\BundlePricingRule;
use App\Domain\Product\Models\Tag;
use App\Domain\Vendor\Models\Seller;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class BundleService
{
    public function __construct(
        private readonly BundleValidationService $validationService,
        private readonly BundlePricingService $pricingService,
        private readonly BundleInventoryService $inventoryService,
    ) {}

    public function create(array $data, Seller $seller): Bundle
    {
        $slug = str_slug('bundles', 'slug', trim($data['name']));
        $sku = $data['sku'] ?? Bundle::generateSku($seller->id);

        $bundle = Bundle::create([
            'seller_id' => $seller->id,
            'name' => trim($data['name']),
            'slug' => $slug,
            'sku' => $sku,
            'barcode' => $data['barcode'] ?? null,
            'type' => $data['type'] ?? 'fixed',
            'price_type' => $data['price_type'] ?? 'auto',
            'price' => $data['price_type'] === 'manual' ? ($data['price'] ?? null) : null,
            'compare_price' => $data['compare_price'] ?? null,
            'discount_type' => $data['discount_type'] ?? null,
            'discount_value' => $data['discount_value'] ?? null,
            'status' => Bundle::STATUS_PENDING_APPROVAL,
            'is_visible' => false,
            'short_description' => $data['short_description'] ?? null,
            'description' => $data['description'] ?? null,
        ]);

        $this->syncItems($bundle, $data['items'] ?? []);
        $this->syncPricingRules($bundle, $data['pricing_rules'] ?? []);

        if (! empty($data['thumbnail'])) {
            $this->uploadThumbnail($bundle, $data['thumbnail'], "{$seller->username}/bundles");
        }

        if (! empty($data['gallery'])) {
            $this->uploadGallery($bundle, $data['gallery'], "{$seller->username}/bundles");
        }

        $this->inventoryService->updateCachedStock($bundle);

        return $bundle->fresh(['items.product', 'images']);
    }

    public function update(Bundle $bundle, array $data): Bundle
    {
        $updateData = [
            'name' => trim($data['name']),
            'short_description' => $data['short_description'] ?? null,
            'description' => $data['description'] ?? null,
            'barcode' => $data['barcode'] ?? null,
            'type' => $data['type'] ?? 'fixed',
            'price_type' => $data['price_type'] ?? 'auto',
            'compare_price' => $data['compare_price'] ?? null,
            'discount_type' => $data['discount_type'] ?? null,
            'discount_value' => $data['discount_value'] ?? null,
        ];

        if ($data['price_type'] === 'manual') {
            $updateData['price'] = $data['price'] ?? null;
        } else {
            $updateData['price'] = null;
        }

        if (isset($data['is_visible'])) {
            $updateData['is_visible'] = $data['is_visible'] ? true : false;
        }

        if (trim($data['name']) !== $bundle->name) {
            $updateData['slug'] = str_slug('bundles', 'slug', trim($data['name']));
        }

        $bundle->update($updateData);

        $this->syncItems($bundle, $data['items'] ?? []);
        $this->syncPricingRules($bundle, $data['pricing_rules'] ?? []);

        if (! empty($data['thumbnail'])) {
            $this->uploadThumbnail($bundle, $data['thumbnail'], "{$bundle->seller->username}/bundles");
        }

        if (! empty($data['gallery'])) {
            $this->uploadGallery($bundle, $data['gallery'], "{$bundle->seller->username}/bundles");
        }

        $this->inventoryService->updateCachedStock($bundle);

        return $bundle->fresh(['items.product', 'images']);
    }

    public function delete(Bundle $bundle): void
    {
        if (! empty($bundle->thumbnail)) {
            delete_file($bundle->thumbnail);
        }

        foreach ($bundle->images as $image) {
            delete_file($image->image);
        }

        $bundle->delete();
    }

    public function duplicate(Bundle $original, Seller $seller): Bundle
    {
        $newName = $original->name . ' (Copy)';

        $duplicated = Bundle::create([
            'seller_id' => $seller->id,
            'name' => $newName,
            'slug' => str_slug('bundles', 'slug', $newName),
            'sku' => Bundle::generateSku($seller->id),
            'barcode' => null,
            'type' => $original->type,
            'price_type' => $original->price_type,
            'price' => $original->price,
            'compare_price' => $original->compare_price,
            'discount_type' => $original->discount_type,
            'discount_value' => $original->discount_value,
            'status' => Bundle::STATUS_DRAFT,
            'is_visible' => false,
            'thumbnail' => $original->thumbnail,
            'short_description' => $original->short_description,
            'description' => $original->description,
        ]);

        foreach ($original->items as $item) {
            $duplicated->items()->create([
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'is_optional' => $item->is_optional,
                'sort_order' => $item->sort_order,
            ]);
        }

        foreach ($original->images as $image) {
            $duplicated->images()->create([
                'image' => $image->image,
                'sort_order' => $image->sort_order,
            ]);
        }

        foreach ($original->pricingRules as $rule) {
            $duplicated->pricingRules()->create([
                'min_items' => $rule->min_items,
                'max_items' => $rule->max_items,
                'discount_percent' => $rule->discount_percent,
                'label' => $rule->label,
            ]);
        }

        $this->inventoryService->updateCachedStock($duplicated);

        return $duplicated->fresh(['items.product', 'images']);
    }

    public function toggleVisibility(Bundle $bundle): void
    {
        $bundle->update(['is_visible' => ! $bundle->is_visible]);
    }

    private function syncItems(Bundle $bundle, array $items): void
    {
        $bundle->items()->delete();

        foreach ($items as $sortOrder => $item) {
            if (empty($item['product_id'])) continue;

            BundleItem::create([
                'bundle_id' => $bundle->id,
                'product_id' => $item['product_id'],
                'quantity' => max((int) ($item['quantity'] ?? 1), 1),
                'is_optional' => ! empty($item['is_optional']),
                'sort_order' => $sortOrder,
            ]);
        }
    }

    private function syncPricingRules(Bundle $bundle, array $rules): void
    {
        $bundle->pricingRules()->delete();

        if ($bundle->type !== 'mix_match') return;

        foreach ($rules as $rule) {
            if (empty($rule['min_items'])) continue;

            BundlePricingRule::create([
                'bundle_id' => $bundle->id,
                'min_items' => (int) ($rule['min_items'] ?? 2),
                'max_items' => ! empty($rule['max_items']) ? (int) $rule['max_items'] : null,
                'discount_percent' => (float) ($rule['discount_percent'] ?? 0),
                'label' => $rule['label'] ?? null,
            ]);
        }
    }

    private function uploadThumbnail(Bundle $bundle, UploadedFile $file, string $folder): void
    {
        if (! empty($bundle->thumbnail)) {
            delete_file($bundle->thumbnail);
        }

        $path = upload_file($file, $folder);
        $bundle->update(['thumbnail' => $path]);
    }

    private function uploadGallery(Bundle $bundle, array $files, string $folder): void
    {
        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $path = upload_file($file, $folder);
                $bundle->images()->create([
                    'image' => $path,
                ]);
            }
        }
    }
}
