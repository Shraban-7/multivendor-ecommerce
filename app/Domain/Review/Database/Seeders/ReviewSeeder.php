<?php

namespace App\Domain\Review\Database\Seeders;

use App\Domain\Auth\Models\User;
use App\Domain\Product\Models\Product;
use App\Domain\Review\Models\Review;
use App\Domain\Vendor\Models\Seller;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReviewSeeder extends Seeder
{
    /** @var list<string> */
    private array $comments = [
        'Excellent quality! Exactly as described and arrived quickly.',
        'Very good product. Worth the price and packaging was neat.',
        'Great value for money. Would buy again from this seller.',
        'Solid build and works perfectly. Happy with this purchase.',
        'Good product overall. Delivery was on time.',
        'Nice quality and looks premium. Recommended.',
        'Works as expected. Customer support was helpful too.',
        'Really satisfied. The product matches the photos.',
        'Awesome deal! Better than I expected for this price.',
        'Decent product. A few minor flaws but still useful.',
        'Top-notch quality. Using it daily without issues.',
        'Perfect for everyday use. Comfortable and durable.',
    ];

    public function run(): void
    {
        $products = Product::query()->select('id', 'seller_id', 'name')->orderBy('id')->get();
        $users = User::query()->orderBy('id')->get(['id', 'name']);

        if ($products->isEmpty()) {
            $this->command?->warn('No products found. Run ProductSeeder first.');

            return;
        }

        if ($users->isEmpty()) {
            $this->command?->warn('No users found. Run UserSeeder first.');

            return;
        }

        $now = now();
        $rows = [];
        $userCount = $users->count();

        foreach ($products as $index => $product) {
            // Every product gets 2–5 approved reviews (weighted toward 4–5 stars).
            $reviewCount = 2 + ($index % 4);

            for ($i = 0; $i < $reviewCount; $i++) {
                $user = $users[($index + $i) % $userCount];
                $rows[] = [
                    'order_id' => 0,
                    'order_item_id' => 0,
                    'product_id' => $product->id,
                    'user_id' => $user->id,
                    'seller_id' => $product->seller_id,
                    'rating' => $this->weightedRating($index + $i),
                    'description' => $this->comments[($index + $i) % count($this->comments)],
                    'seller_reply' => null,
                    'replied_at' => null,
                    'is_approved' => true,
                    'helpful_count' => ($index + $i) % 7,
                    'created_at' => $now->copy()->subDays(($index + $i) % 45),
                    'updated_at' => $now,
                ];
            }
        }

        DB::transaction(function () use ($rows) {
            Review::query()->delete();

            foreach (array_chunk($rows, 500) as $chunk) {
                Review::insert($chunk);
            }

            $stats = Review::query()
                ->where('is_approved', true)
                ->selectRaw('product_id, ROUND(AVG(rating), 1) as avg_rating, COUNT(*) as rating_count')
                ->groupBy('product_id')
                ->get();

            foreach ($stats as $stat) {
                Product::where('id', $stat->product_id)->update([
                    'avg_rating' => (float) $stat->avg_rating,
                    'rating_count' => (int) $stat->rating_count,
                ]);
            }

            // Reset products with no reviews (should be none after this seeder).
            Product::whereNotIn('id', $stats->pluck('product_id'))->update([
                'avg_rating' => 0,
                'rating_count' => 0,
            ]);

            $sellerStats = Review::query()
                ->where('is_approved', true)
                ->selectRaw('seller_id, ROUND(AVG(rating), 2) as rating, COUNT(*) as rating_count')
                ->groupBy('seller_id')
                ->get();

            foreach ($sellerStats as $stat) {
                Seller::where('id', $stat->seller_id)->update([
                    'rating' => (float) $stat->rating,
                    'rating_count' => (int) $stat->rating_count,
                ]);
            }
        });

        $rated = Product::where('rating_count', '>', 0)->count();
        $this->command?->info("Created ".count($rows)." reviews. Rated products: {$rated}/{$products->count()}.");
    }

    private function weightedRating(int $seed): int
    {
        // Bias toward positive ratings so the catalog looks healthy.
        return match ($seed % 10) {
            0 => 3,
            1, 2 => 4,
            default => 5,
        };
    }
}
