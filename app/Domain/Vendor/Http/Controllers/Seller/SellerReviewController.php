<?php

namespace App\Domain\Vendor\Http\Controllers\Seller;

use App\Domain\Product\Models\Product;
use App\Domain\Review\Models\Review;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SellerReviewController extends Controller
{
    public function index(Request $request)
    {
        $sellerId = get_seller_id();

        $query = Review::with(['user', 'product:id,name,thumbnail', 'images'])
            ->forSeller($sellerId);

        if ($request->filled('rating')) {
            $query->rating((int) $request->rating);
        }

        if ($request->filled('status')) {
            match ($request->status) {
                'approved' => $query->approved(),
                'pending' => $query->pending(),
                'replied' => $query->hasReply(),
                'unreplied' => $query->withoutReply(),
                default => null,
            };
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('product', fn ($p) => $p->where('name', 'like', "%{$search}%"));
            });
        }

        $reviews = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'total' => Review::forSeller($sellerId)->count(),
            'avg_rating' => round((float) Review::forSeller($sellerId)->approved()->avg('rating') ?? 0, 2),
            'approved' => Review::forSeller($sellerId)->approved()->count(),
            'pending' => Review::forSeller($sellerId)->pending()->count(),
            'replied' => Review::forSeller($sellerId)->hasReply()->count(),
            'unreplied' => Review::forSeller($sellerId)->withoutReply()->count(),
        ];

        $ratingDistribution = [];
        for ($i = 5; $i >= 1; $i--) {
            $count = Review::forSeller($sellerId)->rating($i)->count();
            $ratingDistribution[$i] = [
                'count' => $count,
                'percent' => $stats['total'] > 0 ? round(($count / $stats['total']) * 100) : 0,
            ];
        }

        return view('seller.reviews.index', compact('reviews', 'stats', 'ratingDistribution'));
    }

    public function show(Review $review)
    {
        $sellerId = get_seller_id();

        if ($review->seller_id !== $sellerId) {
            abort(403);
        }

        $review->load(['user', 'product', 'images']);

        return view('seller.reviews.show', compact('review'));
    }

    public function reply(Request $request, Review $review)
    {
        $sellerId = get_seller_id();

        if ($review->seller_id !== $sellerId) {
            abort(403);
        }

        $request->validate([
            'reply' => 'required|string|max:5000',
        ]);

        $review->update([
            'seller_reply' => $request->reply,
            'replied_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Reply submitted successfully.');
    }

    public function deleteReply(Review $review)
    {
        $sellerId = get_seller_id();

        if ($review->seller_id !== $sellerId) {
            abort(403);
        }

        $review->update([
            'seller_reply' => null,
            'replied_at' => null,
        ]);

        return redirect()->back()->with('success', 'Reply removed successfully.');
    }

    public function toggleApproval(Review $review)
    {
        $sellerId = get_seller_id();

        if ($review->seller_id !== $sellerId) {
            abort(403);
        }

        $review->update(['is_approved' => !$review->is_approved]);

        $review->product->recalculateRating();
        $review->seller?->recalculateRating();

        $message = $review->is_approved ? 'Review approved.' : 'Review hidden.';

        return redirect()->back()->with('success', $message);
    }
}
