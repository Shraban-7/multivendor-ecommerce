<?php

namespace App\Domain\Review\Repositories;

use App\Domain\Review\Models\Review;
use App\Domain\Review\Repositories\Contracts\ReviewRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class EloquentReviewRepository implements ReviewRepositoryInterface
{
    public function findById(int $id): ?Review
    {
        return Review::find($id);
    }

    public function getByProduct(int $productId): Collection
    {
        return Review::where('product_id', $productId)->with('user', 'images')->get();
    }

    public function getReportedReviews(): Collection
    {
        $reportIds = \App\Domain\Review\Models\ReportReview::pluck('review_id');

        return Review::with('user', 'images', 'product', 'reports')
            ->whereIn('id', $reportIds)
            ->get();
    }

    public function store(array $data): Review
    {
        return Review::create($data);
    }

    public function update(Review $review, array $data): bool
    {
        return $review->update($data);
    }

    public function delete(Review $review): bool
    {
        return $review->delete();
    }

    public function averageRating(int $productId): float
    {
        return round((float) Review::where('product_id', $productId)->avg('rating'), 2);
    }

    public function getReportedReviewIds(): array
    {
        return \App\Domain\Review\Models\ReportReview::pluck('review_id')->toArray();
    }
}
