<?php

namespace App\Domain\Review\Services;

use App\Domain\Review\Models\Review;
use App\Domain\Review\Repositories\Contracts\ReviewRepositoryInterface;
use Illuminate\Support\Facades\DB;

class ReviewService
{
    public function __construct(
        private readonly ReviewRepositoryInterface $reviewRepo,
    ) {}

    public function createReview(array $data, array $imagePaths = []): Review
    {
        return DB::transaction(function () use ($data, $imagePaths) {
            $review = $this->reviewRepo->store($data);

            foreach ($imagePaths as $path) {
                $review->images()->create(['image' => $path]);
            }

            return $review->load('images');
        });
    }

    public function averageRating(int $productId): float
    {
        return $this->reviewRepo->averageRating($productId);
    }
}
