<?php

namespace App\Domain\Review\Services;

use App\Domain\Review\Models\Review;
use Illuminate\Support\Facades\DB;

class ReviewService
{
    /**
     * @param  array<string, mixed>  $data
     * @param  array<int, string>  $imagePaths
     */
    public function createReview(array $data, array $imagePaths = []): Review
    {
        return DB::transaction(function () use ($data, $imagePaths) {
            $review = Review::create($data);

            foreach ($imagePaths as $path) {
                $review->images()->create(['image' => $path]);
            }

            return $review->load('images');
        });
    }

    public function averageRating(int $productId): float
    {
        return round((float) Review::where('product_id', $productId)->avg('rating'), 2);
    }
}
