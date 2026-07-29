<?php

namespace App\Domain\Review\Http\Controllers\Admin;

use App\Domain\Review\Models\Review;
use App\Domain\Review\Repositories\Contracts\ReviewRepositoryInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReviewsController extends Controller
{
    public function __construct(
        private readonly ReviewRepositoryInterface $reviewRepo,
    ) {}

    public function index(Request $request)
    {
        $reviews = Review::with(['product.seller', 'images', 'reports.user', 'reports.seller'])
            ->whereHas('reports')
            ->latest()
            ->paginate(25);

        return view('admin.reviews.index', compact('reviews'));
    }

    public function destroy(Review $review)
    {
        $this->reviewRepo->delete($review);

        return redirect()->route('admin.reviews.index')->with('success', 'Review deleted successfully');
    }
}
