<?php

namespace App\Domain\Tax\Services;

use App\Domain\Product\Models\Product;
use App\Domain\Tax\Models\SellerTaxConfig;
use App\Domain\Tax\Models\TaxClass;
use App\Domain\Vendor\Models\Seller;
use Illuminate\Support\Collection;

class TaxCalculatorService
{
    public const TAX_BEHAVIOR_INCLUSIVE = 'inclusive';
    public const TAX_BEHAVIOR_EXCLUSIVE = 'exclusive';

    /**
     * Calculate tax for a collection of order items.
     *
     * Returns:
     * - 'total_tax': float total tax amount
     * - 'breakdown': array of per-item tax details
     * - 'tax_behavior': 'inclusive' or 'exclusive'
     */
    public function calculateForOrder(
        Seller $seller,
        Collection $items,
        float $subTotal,
        float $discount,
    ): array {
        $config = $this->getSellerConfig($seller);
        $taxClass = $config?->taxClass;
        $behavior = $config?->tax_behavior ?? self::TAX_BEHAVIOR_EXCLUSIVE;

        if ($config?->is_tax_exempt) {
            return [
                'total_tax' => 0.0,
                'breakdown' => [],
                'tax_behavior' => $behavior,
            ];
        }

        $rate = $this->resolveRate($taxClass);
        $breakdown = [];
        $totalTax = 0.0;

        foreach ($items as $item) {
            $productId = is_array($item) ? ($item['product_id'] ?? null) : $item->product_id;
            $unitPrice = is_array($item) ? ($item['unit_price'] ?? $item['price'] ?? 0) : ($item->unit_price ?? $item->price ?? 0);
            $qty = is_array($item) ? ($item['quantity'] ?? 1) : ($item->quantity ?? 1);
            $lineTotal = $unitPrice * $qty;

            if ($rate > 0) {
                $taxAmount = $this->calculateLineTax($lineTotal, $rate, $behavior);
                $totalTax += $taxAmount;
                $breakdown[] = [
                    'product_id' => $productId,
                    'line_total' => $lineTotal,
                    'rate' => $rate,
                    'tax_amount' => $taxAmount,
                    'tax_class' => $taxClass?->name ?? 'Default',
                ];
            }
        }

        $totalTax = round($totalTax, 2);

        return [
            'total_tax' => $totalTax,
            'breakdown' => $breakdown,
            'tax_behavior' => $behavior,
        ];
    }

    /**
     * Calculate a single item's tax, respecting inclusive/exclusive behavior.
     */
    public function calculateLineTax(float $lineTotal, float $ratePercent, string $behavior): float
    {
        if ($behavior === self::TAX_BEHAVIOR_INCLUSIVE) {
            return round($lineTotal - ($lineTotal / (1 + $ratePercent / 100)), 2);
        }

        return round(($ratePercent / 100) * $lineTotal, 2);
    }

    /**
     * Get the effective tax rate for the seller.
     * Uses seller-specific config, otherwise falls back to the default active rate.
     */
    public function resolveRate(?TaxClass $taxClass): float
    {
        if (! $taxClass) {
            $taxClass = TaxClass::with('activeRates')->first();
        } else {
            $taxClass->load('activeRates');
        }

        $rate = $taxClass?->activeRates->first();

        return $rate?->rate ?? 0.0;
    }

    /**
     * Get or create default tax configuration for a seller.
     */
    public function getSellerConfig(Seller $seller): ?SellerTaxConfig
    {
        return SellerTaxConfig::with('taxClass.rates')
            ->where('seller_id', $seller->id)
            ->first();
    }
}
