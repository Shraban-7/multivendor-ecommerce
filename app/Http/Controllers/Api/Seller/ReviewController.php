<?php

namespace App\Http\Controllers\Api\Seller;

use App\Domain\Review\Models\Review;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $sellerId = Auth::id();

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

        $sortField = $request->get('sort', 'created_at');
        $sortDir = $request->get('direction', 'desc');
        $allowedSorts = ['created_at', 'rating', 'helpful_count'];
        if (!in_array($sortField, $allowedSorts)) {
            $sortField = 'created_at';
        }

        $reviews = $query->orderBy($sortField, $sortDir)
            ->paginate($request->get('per_page', 15))
            ->withQueryString();

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

        return response()->json([
            'reviews' => $reviews,
            'stats' => $stats,
            'rating_distribution' => $ratingDistribution,
        ]);
    }

    public function show(Review $review)
    {
        $sellerId = Auth::id();

        if ($review->seller_id !== $sellerId) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $review->load(['user', 'product', 'images', 'reports']);

        return response()->json(['review' => $review]);
    }

    public function reply(Request $request, Review $review)
    {
        $sellerId = Auth::id();

        if ($review->seller_id !== $sellerId) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'reply' => 'required|string|max:5000',
        ]);

        $review->update([
            'seller_reply' => $request->reply,
            'replied_at' => now(),
        ]);

        return response()->json([
            'message' => 'Reply submitted successfully.',
            'review' => $review->fresh()->load('user', 'product'),
        ]);
    }

    public function deleteReply(Review $review)
    {
        $sellerId = Auth::id();

        if ($review->seller_id !== $sellerId) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $review->update([
            'seller_reply' => null,
            'replied_at' => null,
        ]);

        return response()->json(['message' => 'Reply deleted successfully.']);
    }

    public function toggleApproval(Review $review)
    {
        $sellerId = Auth::id();

        if ($review->seller_id !== $sellerId) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $review->update(['is_approved' => !$review->is_approved]);

        $review->product->recalculateRating();
        $review->seller?->recalculateRating();

        return response()->json([
            'message' => $review->is_approved ? 'Review approved.' : 'Review hidden.',
            'is_approved' => $review->is_approved,
        ]);
    }
}
