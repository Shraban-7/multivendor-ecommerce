<?php

namespace App\Domain\Bundle\Services;

use App\Domain\Bundle\Models\Bundle;

class BundlePricingService
{
    public function calculatePrice(Bundle $bundle): float
    {
        if ($bundle->price_type === 'manual' && $bundle->price !== null) {
            return (float) $bundle->price;
        }

        $total = $this->calculateSubtotal($bundle);

        $total = $this->applyDiscount($bundle, $total);

        return round(max($total, 0), 2);
    }

    public function calculateSubtotal(Bundle $bundle): float
    {
        $total = 0.0;
        foreach ($bundle->items as $item) {
            if ($item->product) {
                $total += (float) $item->product->price * (int) $item->quantity;
            }
        }
        return $total;
    }

    public function applyDiscount(Bundle $bundle, float $subtotal): float
    {
        if ($bundle->discount_type === 'percentage' && $bundle->discount_value > 0) {
            return $subtotal - ($subtotal * ((float) $bundle->discount_value / 100));
        }

        if ($bundle->discount_type === 'fixed' && $bundle->discount_value > 0) {
            return $subtotal - (float) $bundle->discount_value;
        }

        return $subtotal;
    }

    public function savingsAmount(Bundle $bundle): float
    {
        $subtotal = $this->calculateSubtotal($bundle);
        $final = $this->calculatePrice($bundle);

        return round(max($subtotal - $final, 0), 2);
    }

    public function savingsPercent(Bundle $bundle): float
    {
        $subtotal = $this->calculateSubtotal($bundle);
        if ($subtotal <= 0) {
            return 0;
        }

        $final = $this->calculatePrice($bundle);

        return round((($subtotal - $final) / $subtotal) * 100, 1);
    }
}
