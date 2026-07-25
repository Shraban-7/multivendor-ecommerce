<?php

namespace App\Domain\Review\Repositories\Contracts;

use App\Domain\Review\Models\Review;
use Illuminate\Database\Eloquent\Collection;

interface ReviewRepositoryInterface
{
    public function findById(int $id): ?Review;

    public function getByProduct(int $productId): Collection;

    public function getReportedReviews(): Collection;

    public function store(array $data): Review;

    public function update(Review $review, array $data): bool;

    public function delete(Review $review): bool;

    public function averageRating(int $productId): float;

    public function getReportedReviewIds(): array;
}
