<?php

namespace App\Console\Commands;

use App\Domain\Product\Models\Color;
use App\Domain\Product\Models\Option;
use App\Domain\Product\Models\ProductVariant;
use App\Domain\Product\Models\Size;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MigrateVariantSystem extends Command
{
    protected $signature = 'migrate:variant-system';
    protected $description = 'Migrate from generic options/option_values to dedicated colors/sizes tables';

    private const SIZE_ORDER = [
        'xs' => 0, 's' => 1, 'm' => 2, 'l' => 3, 'xl' => 4,
        '2xl' => 5, '3xl' => 6, '4xl' => 7, '5xl' => 8,
        '22 inch' => 10, '24 inch' => 11, '27 inch' => 12, '32 inch' => 13,
    ];

    public function handle(): int
    {
        $this->warn('Starting variant system migration...');
        $this->newLine();

        $this->migrateColors();
        $this->migrateSizes();
        $this->updateProductVariants();
        $this->regenerateSkus();

        $this->newLine();
        $this->info('Variant system migration completed successfully!');

        return Command::SUCCESS;
    }

    private function migrateColors(): void
    {
        $this->info('Migrating colors...');

        $colorOption = Option::where('name', 'Color')->first();
        if (! $colorOption) {
            $this->warn('No Color option found, skipping.');

            return;
        }

        $count = 0;
        foreach ($colorOption->option_values as $value) {
            Color::firstOrCreate(
                ['slug' => Str::slug($value->value)],
                [
                    'name' => $value->value,
                    'hex_code' => '#CCCCCC',
                ]
            );
            $count++;
        }

        $this->info("Migrated {$count} colors.");
    }

    private function migrateSizes(): void
    {
        $this->info('Migrating sizes...');

        $sizeOption = Option::where('name', 'Size')->first();
        if (! $sizeOption) {
            $this->warn('No Size option found, skipping.');

            return;
        }

        $count = 0;
        foreach ($sizeOption->option_values as $value) {
            $slug = Str::slug($value->value);
            Size::firstOrCreate(
                ['slug' => $slug],
                [
                    'name' => $value->value,
                    'sort_order' => self::SIZE_ORDER[$slug] ?? 50,
                ]
            );
            $count++;
        }

        $this->info("Migrated {$count} sizes.");
    }

    private function updateProductVariants(): void
    {
        $this->info('Updating product variants with color_id/size_id...');

        $colorOption = Option::where('name', 'Color')->first();
        $sizeOption = Option::where('name', 'Size')->first();
        $bar = $this->output->createProgressBar(ProductVariant::count());

        ProductVariant::with('option_values.option')->chunk(100, function ($variants) use ($colorOption, $sizeOption, $bar) {
            foreach ($variants as $variant) {
                $colorId = null;
                $sizeId = null;

                foreach ($variant->option_values as $ov) {
                    if ($colorOption && $ov->option_id === $colorOption->id) {
                        $color = Color::where('slug', Str::slug($ov->value))->first();
                        $colorId = $color?->id;
                    } elseif ($sizeOption && $ov->option_id === $sizeOption->id) {
                        $size = Size::where('slug', Str::slug($ov->value))->first();
                        $sizeId = $size?->id;
                    }
                }

                if ($colorId || $sizeId) {
                    $variant->updateQuietly([
                        'color_id' => $colorId,
                        'size_id' => $sizeId,
                    ]);
                }

                $bar?->advance();
            }
        });

        $bar?->finish();
        $this->newLine();
        $this->info('Product variants updated.');
    }

    private function regenerateSkus(): void
    {
        $this->info('Regenerating SKUs...');

        ProductVariant::with('product', 'color', 'size')->chunk(100, function ($variants) {
            foreach ($variants as $variant) {
                $parts = [$variant->product?->slug ?? 'product'];

                if ($variant->color) {
                    $parts[] = $variant->color->slug;
                }

                if ($variant->size) {
                    $parts[] = $variant->size->slug;
                }

                $sku = implode('-', $parts);
                $sku = strtoupper(substr($sku, 0, 50));

                $existing = ProductVariant::where('sku', $sku)->where('id', '!=', $variant->id)->exists();
                if ($existing) {
                    $sku .= '-'.$variant->id;
                }

                $variant->updateQuietly(['sku' => $sku]);
            }
        });

        $this->info('SKUs regenerated.');
    }
}