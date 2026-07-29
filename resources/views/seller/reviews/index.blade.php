@extends('seller.layouts.app')
@section('title', 'Product Reviews')
@section('content')

    <div class="flex justify-between items-center mb-3 flex-wrap gap-2">
        <h4 class="font-bold mb-0 text-ink">Product Reviews</h4>
        <div class="flex gap-2">
            <a href="{{ route('seller.reviews.index', ['status' => 'unreplied']) }}"
                class="inline-flex items-center justify-center px-3 py-1.5 text-sm font-medium rounded-xs focus:outline-none transition-colors gap-1 {{ request('status') === 'unreplied' ? 'bg-feedback-warning text-white hover:bg-feedback-warning/90' : 'bg-surface-muted text-ink border border-border hover:bg-border/30' }}">
                <i data-feather="message-square" class="icon-xs"></i> Needs Reply
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="px-4 py-2 rounded-sm bg-feedback-success/10 border border-feedback-success/20 text-feedback-success text-sm alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6 gap-3 mb-4">
        <div>
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 p-3 h-full" style="border-radius: 10px; border-left: 4px solid #F85606;">
                <div class="flex justify-between items-center">
                    <div>
                        <span class="text-ink-tertiary uppercase text-sm">Total</span>
                        <h5 class="font-bold mb-0 text-ink">{{ $stats['total'] }}</h5>
                    </div>
                    <i class="fas fa-star fa-2x opacity-25"></i>
                </div>
            </div>
        </div>
        <div>
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 p-3 h-full" style="border-radius: 10px; border-left: 4px solid #1D8A45;">
                <div class="flex justify-between items-center">
                    <div>
                        <span class="text-ink-tertiary uppercase text-sm">Approved</span>
                        <h5 class="font-bold mb-0 text-feedback-success">{{ $stats['approved'] }}</h5>
                    </div>
                    <i class="fas fa-check-circle fa-2x opacity-25"></i>
                </div>
            </div>
        </div>
        <div>
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 p-3 h-full" style="border-radius: 10px; border-left: 4px solid #D93025;">
                <div class="flex justify-between items-center">
                    <div>
                        <span class="text-ink-tertiary uppercase text-sm">Pending</span>
                        <h5 class="font-bold mb-0 text-feedback-danger">{{ $stats['pending'] }}</h5>
                    </div>
                    <i class="fas fa-clock fa-2x opacity-25"></i>
                </div>
            </div>
        </div>
        <div>
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 p-3 h-full" style="border-radius: 10px; border-left: 4px solid #0ea5e9;">
                <div class="flex justify-between items-center">
                    <div>
                        <span class="text-ink-tertiary uppercase text-sm">Replied</span>
                        <h5 class="font-bold mb-0 text-feedback-info">{{ $stats['replied'] }}</h5>
                    </div>
                    <i class="fas fa-reply fa-2x opacity-25"></i>
                </div>
            </div>
        </div>
        <div>
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 p-3 h-full" style="border-radius: 10px; border-left: 4px solid #B7791A;">
                <div class="flex justify-between items-center">
                    <div>
                        <span class="text-ink-tertiary uppercase text-sm">Avg Rating</span>
                        <h5 class="font-bold mb-0 text-feedback-warning">{{ $stats['avg_rating'] }}</h5>
                    </div>
                    <i class="fas fa-chart-line fa-2x opacity-25"></i>
                </div>
            </div>
        </div>
        <div>
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 p-3 h-full" style="border-radius: 10px; border-left: 4px solid #637381;">
                <div class="flex justify-between items-center">
                    <div>
                        <span class="text-ink-tertiary uppercase text-sm">Unreplied</span>
                        <h5 class="font-bold mb-0 text-ink-secondary">{{ $stats['unreplied'] }}</h5>
                    </div>
                    <i class="fas fa-message fa-2x opacity-25"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 mb-4" style="border-radius: 12px;">
        <div class="p-5">
            <div class="flex flex-wrap gap-2 mb-3">
                <a href="{{ route('seller.reviews.index') }}"
                    class="btn btn-sm {{ !request('status') && !request('rating') ? 'btn-dark' : 'btn-light' }}">
                    All
                </a>
                @foreach ([5, 4, 3, 2, 1] as $star)
                    <a href="{{ route('seller.reviews.index', ['rating' => $star, 'status' => request('status')]) }}"
                        class="btn btn-sm {{ request('rating') == $star ? 'btn-warning' : 'btn-light' }}">
                        {{ $star }} <i class="fas fa-star"></i>
                        @if (($ratingDistribution[$star]['percent'] ?? 0) > 0)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-surface-muted text-ink ms-1">{{ $ratingDistribution[$star]['percent'] }}%</span>
                        @endif
                    </a>
                @endforeach
            </div>

            <div class="flex flex-wrap items-center justify-between gap-2 mb-3">
                <div class="flex flex-wrap gap-1">
                    <a href="{{ route('seller.reviews.index', ['status' => 'approved', 'rating' => request('rating')]) }}"
                        class="btn btn-sm {{ request('status') === 'approved' ? 'btn-success' : 'btn-light' }}">Approved</a>
                    <a href="{{ route('seller.reviews.index', ['status' => 'pending', 'rating' => request('rating')]) }}"
                        class="btn btn-sm {{ request('status') === 'pending' ? 'btn-danger' : 'btn-light' }}">Pending</a>
                    <a href="{{ route('seller.reviews.index', ['status' => 'replied', 'rating' => request('rating')]) }}"
                        class="btn btn-sm {{ request('status') === 'replied' ? 'btn-info' : 'btn-light' }}">Replied</a>
                    <a href="{{ route('seller.reviews.index', ['status' => 'unreplied', 'rating' => request('rating')]) }}"
                        class="btn btn-sm {{ request('status') === 'unreplied' ? 'btn-warning' : 'btn-light' }}">Unreplied</a>
                </div>

                <form method="GET" class="flex gap-2">
                    @if (request('status'))
                        <input type="hidden" name="status" value="{{ request('status') }}">
                    @endif
                    @if (request('rating'))
                        <input type="hidden" name="rating" value="{{ request('rating') }}">
                    @endif
                    <div class="flex" style="max-width: 250px;">
                        <input type="text" name="search" class="w-full px-2.5 py-1.5 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" placeholder="Search reviews..."
                            value="{{ request('search') }}">
                        <button class="btn btn-primary btn-sm btn-icon" type="submit"><i class="fas fa-search"></i></button>
                    </div>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-ink border-collapse table-bordered table-hover bg-white mb-0 align-middle">
                    <thead class="bg-surface-muted">
                        <tr>
                            <th scope="col" class="text-sm font-semibold text-ink-tertiary" style="width: 50px;">#</th>
                            <th scope="col" class="text-sm font-semibold text-ink-tertiary">Product</th>
                            <th scope="col" class="text-sm font-semibold text-ink-tertiary">Customer</th>
                            <th scope="col" class="text-sm font-semibold text-ink-tertiary">Rating</th>
                            <th scope="col" class="text-sm font-semibold text-ink-tertiary">Review</th>
                            <th scope="col" class="text-sm font-semibold text-ink-tertiary">Status</th>
                            <th scope="col" class="text-sm font-semibold text-ink-tertiary">Reply</th>
                            <th scope="col" class="text-sm font-semibold text-ink-tertiary">Date</th>
                            <th scope="col" class="text-sm font-semibold text-ink-tertiary">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reviews as $review)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        @if ($review->product && $review->product->thumbnail)
                                            <img src="{{ asset($review->product->thumbnail) }}" alt=""
                                                style="width: 36px; height: 36px; object-fit: cover; border-radius: 6px;">
                                        @endif
                                        <span class="font-semibold text-sm">{{ $review->product?->name ?? 'Deleted Product' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-sm font-semibold">{{ $review->user?->name ?? 'Guest' }}</div>
                                    <div class="text-sm text-ink-tertiary">{{ $review->user?->phone ?? '' }}</div>
                                </td>
                                <td>
                                    <div class="whitespace-nowrap">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $review->rating ? 'text-feedback-warning' : 'text-ink-tertiary opacity-25' }}"
                                                style="font-size: 12px;"></i>
                                        @endfor
                                    </div>
                                </td>
                                <td style="max-width: 220px;">
                                    <div class="text-sm truncate">{{ $review->description }}</div>
                                    @if ($review->images->count() > 0)
                                        <span class="text-sm text-ink-tertiary"><i class="far fa-image me-1"></i>{{ $review->images->count() }} photo(s)</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($review->is_approved)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-feedback-success/10 text-feedback-success">Approved</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-feedback-danger/10 text-feedback-danger">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($review->hasReply())
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-feedback-info/10 text-feedback-info"><i class="fas fa-check me-1"></i>Replied</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-surface-muted text-ink-secondary">No Reply</span>
                                    @endif
                                </td>
                                <td class="text-sm whitespace-nowrap">{{ $review->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown">
                                            <i data-feather="more-horizontal" class="icon-xs"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <button class="dropdown-item text-sm" data-bs-toggle="modal"
                                                    data-bs-target="#viewReviewModal"
                                                    data-review-id="{{ $review->id }}"
                                                    data-product="{{ $review->product?->name ?? 'N/A' }}"
                                                    data-customer="{{ $review->user?->name ?? 'Guest' }}"
                                                    data-rating="{{ $review->rating }}"
                                                    data-description="{{ $review->description }}"
                                                    data-reply="{{ $review->seller_reply }}"
                                                    data-date="{{ $review->created_at->format('d/m/Y h:i A') }}"
                                                    data-approved="{{ $review->is_approved ? 'Yes' : 'No' }}"
                                                    data-helpful="{{ $review->helpful_count }}">
                                                    <i class="fas fa-eye me-2"></i>View Details
                                                </button>
                                            </li>
                                            <li>
                                                <button class="dropdown-item text-sm" data-bs-toggle="modal"
                                                    data-bs-target="#replyModal"
                                                    data-review-id="{{ $review->id }}"
                                                    data-customer="{{ $review->user?->name ?? 'Guest' }}"
                                                    data-current-reply="{{ $review->seller_reply }}">
                                                    <i class="fas fa-reply me-2"></i>
                                                    {{ $review->hasReply() ? 'Edit Reply' : 'Reply' }}
                                                </button>
                                            </li>
                                            <li>
                                                <form action="{{ route('seller.reviews.toggleApproval', $review) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item text-sm">
                                                        <i class="fas {{ $review->is_approved ? 'fa-eye-slash' : 'fa-eye' }} me-2"></i>
                                                        {{ $review->is_approved ? 'Hide Review' : 'Approve Review' }}
                                                    </button>
                                                </form>
                                            </li>
                                            @if ($review->hasReply())
                                                <li>
                                                    <form action="{{ route('seller.reviews.deleteReply', $review) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item text-sm text-feedback-danger"
                                                            onclick="return confirm('Delete your reply?')">
                                                            <i class="fas fa-trash-alt me-2"></i>Delete Reply
                                                        </button>
                                                    </form>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4 text-ink-tertiary">No reviews found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex justify-end mt-3">
                {{ $reviews->links() }}
            </div>
        </div>
    </div>

    {{-- View Review Modal --}}
    <div class="modal fade" id="viewReviewModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-bold">Review Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="text-sm text-ink-tertiary font-semibold">Product</label>
                        <p class="font-semibold mb-0" id="viewProduct"></p>
                    </div>
                    <div class="mb-3">
                        <label class="text-sm text-ink-tertiary font-semibold">Customer</label>
                        <p class="mb-0" id="viewCustomer"></p>
                    </div>
                    <div class="mb-3">
                        <label class="text-sm text-ink-tertiary font-semibold">Rating</label>
                        <p class="mb-0" id="viewRating"></p>
                    </div>
                    <div class="mb-3">
                        <label class="text-sm text-ink-tertiary font-semibold">Review</label>
                        <p class="mb-0" id="viewDescription"></p>
                    </div>
                    <div class="mb-3">
                        <label class="text-sm text-ink-tertiary font-semibold">Your Reply</label>
                        <p class="mb-0 text-feedback-info" id="viewReply"><em>No reply yet</em></p>
                    </div>
                    <div class="mb-3">
                        <label class="text-sm text-ink-tertiary font-semibold">Status</label>
                        <p class="mb-0" id="viewApproved"></p>
                    </div>
                    <div class="mb-0">
                        <label class="text-sm text-ink-tertiary font-semibold">Helpful Count</label>
                        <p class="mb-0" id="viewHelpful"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Reply Modal --}}
    <div class="modal fade" id="replyModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" id="replyForm">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title font-bold">Reply to Review</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-sm text-ink-tertiary mb-3">Replying to review from: <span class="font-semibold" id="replyCustomer"></span></p>
                        <div class="mb-3">
                            <label class="block text-xs font-medium text-ink-secondary mb-1 font-semibold">Your Reply</label>
                            <textarea name="reply" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" rows="4" placeholder="Write your response to this review..." id="replyTextarea"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm">Submit Reply</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('[data-bs-target="#viewReviewModal"]').forEach(btn => {
            btn.addEventListener('click', function () {
                document.getElementById('viewProduct').textContent = this.dataset.product;
                document.getElementById('viewCustomer').textContent = this.dataset.customer;
                document.getElementById('viewRating').innerHTML = renderStars(parseInt(this.dataset.rating));
                document.getElementById('viewDescription').textContent = this.dataset.description;
                document.getElementById('viewReply').innerHTML = this.dataset.reply
                    ? '<span class="text-feedback-success font-semibold">' + this.dataset.reply + '</span>'
                    : '<em class="text-ink-tertiary">No reply yet</em>';
                document.getElementById('viewApproved').textContent = this.dataset.approved;
                document.getElementById('viewHelpful').textContent = this.dataset.helpful;
            });
        });

        document.querySelectorAll('[data-bs-target="#replyModal"]').forEach(btn => {
            btn.addEventListener('click', function () {
                const reviewId = this.dataset.reviewId;
                document.getElementById('replyForm').action = '{{ url('/seller/reviews') }}/' + reviewId + '/reply';
                document.getElementById('replyCustomer').textContent = this.dataset.customer;
                document.getElementById('replyTextarea').value = this.dataset.currentReply || '';
            });
        });

        function renderStars(rating) {
            let html = '';
            for (let i = 1; i <= 5; i++) {
                html += '<i class="fas fa-star ' + (i <= rating ? 'text-feedback-warning' : 'text-ink-tertiary opacity-25') + '"></i> ';
            }
            return html;
        }
    </script>
@endpush
