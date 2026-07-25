<?php

namespace App\Http\Controllers\Admin;

use App\Domain\Review\Models\ReportReview;
use App\Domain\Review\Models\Review;
use App\Http\Controllers\Controller;

class ReviewsController extends Controller
{
    public function index()
    {
        $reportIds = ReportReview::pluck('review_id');
        $reviews = Review::with('user', 'images', 'product', 'reports')->whereIn('id', $reportIds)->get();

        return view('admin.reviews.index', compact('reviews'));
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return redirect()->route('admin.reviews.index')->with('Review deleted successfully');
    }
}
