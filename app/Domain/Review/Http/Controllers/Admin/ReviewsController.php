<?php

namespace App\Domain\Review\Http\Controllers\Admin;

use App\Domain\Review\Models\Review;
use App\Domain\Review\Repositories\Contracts\ReviewRepositoryInterface;
use App\Http\Controllers\Controller;

class ReviewsController extends Controller
{
    public function __construct(
        private readonly ReviewRepositoryInterface $reviewRepo,
    ) {}

    public function index()
    {
        $reviews = $this->reviewRepo->getReportedReviews();

        return view('admin.reviews.index', compact('reviews'));
    }

    public function destroy(Review $review)
    {
        $this->reviewRepo->delete($review);

        return redirect()->route('admin.reviews.index')->with('Review deleted successfully');
    }
}
