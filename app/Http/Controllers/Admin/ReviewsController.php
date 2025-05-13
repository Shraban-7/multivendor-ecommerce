<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReportReview;
use App\Models\Review;
use Illuminate\Http\Request;

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
