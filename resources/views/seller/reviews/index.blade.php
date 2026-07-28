@extends('seller.layouts.app')
@section('title', 'Product Reviews')
@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h4 class="fw-bold mb-0 text-dark">Product Reviews</h4>
        <div class="d-flex gap-2">
            <a href="{{ route('seller.reviews.index', ['status' => 'unreplied']) }}"
                class="btn btn-sm {{ request('status') === 'unreplied' ? 'btn-warning' : 'btn-light border' }} d-inline-flex align-items-center gap-1">
                <i data-feather="message-square" class="icon-xs"></i> Needs Reply
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show py-2" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-xl-2 col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: 10px; border-left: 4px solid #F85606;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted text-uppercase small">Total</span>
                        <h5 class="fw-bold mb-0 text-dark">{{ $stats['total'] }}</h5>
                    </div>
                    <i class="fas fa-star fa-2x opacity-25"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: 10px; border-left: 4px solid #1D8A45;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted text-uppercase small">Approved</span>
                        <h5 class="fw-bold mb-0 text-success">{{ $stats['approved'] }}</h5>
                    </div>
                    <i class="fas fa-check-circle fa-2x opacity-25"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: 10px; border-left: 4px solid #D93025;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted text-uppercase small">Pending</span>
                        <h5 class="fw-bold mb-0 text-danger">{{ $stats['pending'] }}</h5>
                    </div>
                    <i class="fas fa-clock fa-2x opacity-25"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: 10px; border-left: 4px solid #0ea5e9;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted text-uppercase small">Replied</span>
                        <h5 class="fw-bold mb-0 text-info">{{ $stats['replied'] }}</h5>
                    </div>
                    <i class="fas fa-reply fa-2x opacity-25"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: 10px; border-left: 4px solid #B7791A;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted text-uppercase small">Avg Rating</span>
                        <h5 class="fw-bold mb-0 text-warning">{{ $stats['avg_rating'] }}</h5>
                    </div>
                    <i class="fas fa-chart-line fa-2x opacity-25"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: 10px; border-left: 4px solid #637381;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted text-uppercase small">Unreplied</span>
                        <h5 class="fw-bold mb-0 text-secondary">{{ $stats['unreplied'] }}</h5>
                    </div>
                    <i class="fas fa-message fa-2x opacity-25"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
        <div class="card-body">
            <div class="d-flex flex-wrap gap-2 mb-3">
                <a href="{{ route('seller.reviews.index') }}"
                    class="btn btn-sm {{ !request('status') && !request('rating') ? 'btn-dark' : 'btn-light border' }}">
                    All
                </a>
                @foreach ([5, 4, 3, 2, 1] as $star)
                    <a href="{{ route('seller.reviews.index', ['rating' => $star, 'status' => request('status')]) }}"
                        class="btn btn-sm {{ request('rating') == $star ? 'btn-warning' : 'btn-light border' }}">
                        {{ $star }} <i class="fas fa-star"></i>
                        @if (($ratingDistribution[$star]['percent'] ?? 0) > 0)
                            <span class="badge bg-secondary ms-1">{{ $ratingDistribution[$star]['percent'] }}%</span>
                        @endif
                    </a>
                @endforeach
            </div>

            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                <div class="d-flex flex-wrap gap-1">
                    <a href="{{ route('seller.reviews.index', ['status' => 'approved', 'rating' => request('rating')]) }}"
                        class="btn btn-sm {{ request('status') === 'approved' ? 'btn-success' : 'btn-light border' }}">Approved</a>
                    <a href="{{ route('seller.reviews.index', ['status' => 'pending', 'rating' => request('rating')]) }}"
                        class="btn btn-sm {{ request('status') === 'pending' ? 'btn-danger' : 'btn-light border' }}">Pending</a>
                    <a href="{{ route('seller.reviews.index', ['status' => 'replied', 'rating' => request('rating')]) }}"
                        class="btn btn-sm {{ request('status') === 'replied' ? 'btn-info' : 'btn-light border' }}">Replied</a>
                    <a href="{{ route('seller.reviews.index', ['status' => 'unreplied', 'rating' => request('rating')]) }}"
                        class="btn btn-sm {{ request('status') === 'unreplied' ? 'btn-warning' : 'btn-light border' }}">Unreplied</a>
                </div>

                <form method="GET" class="d-flex gap-2">
                    @if (request('status'))
                        <input type="hidden" name="status" value="{{ request('status') }}">
                    @endif
                    @if (request('rating'))
                        <input type="hidden" name="rating" value="{{ request('rating') }}">
                    @endif
                    <div class="input-group input-group-sm" style="max-width: 250px;">
                        <input type="text" name="search" class="form-control" placeholder="Search reviews..."
                            value="{{ request('search') }}">
                        <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover bg-white mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" class="small fw-semibold text-muted" style="width: 50px;">#</th>
                            <th scope="col" class="small fw-semibold text-muted">Product</th>
                            <th scope="col" class="small fw-semibold text-muted">Customer</th>
                            <th scope="col" class="small fw-semibold text-muted">Rating</th>
                            <th scope="col" class="small fw-semibold text-muted">Review</th>
                            <th scope="col" class="small fw-semibold text-muted">Status</th>
                            <th scope="col" class="small fw-semibold text-muted">Reply</th>
                            <th scope="col" class="small fw-semibold text-muted">Date</th>
                            <th scope="col" class="small fw-semibold text-muted">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reviews as $review)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @if ($review->product && $review->product->thumbnail)
                                            <img src="{{ asset($review->product->thumbnail) }}" alt=""
                                                style="width: 36px; height: 36px; object-fit: cover; border-radius: 6px;">
                                        @endif
                                        <span class="fw-semibold small">{{ $review->product?->name ?? 'Deleted Product' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="small fw-semibold">{{ $review->user?->name ?? 'Guest' }}</div>
                                    <div class="small text-muted">{{ $review->user?->phone ?? '' }}</div>
                                </td>
                                <td>
                                    <div class="text-nowrap">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted opacity-25' }}"
                                                style="font-size: 12px;"></i>
                                        @endfor
                                    </div>
                                </td>
                                <td style="max-width: 220px;">
                                    <div class="small text-truncate">{{ $review->description }}</div>
                                    @if ($review->images->count() > 0)
                                        <span class="small text-muted"><i class="far fa-image me-1"></i>{{ $review->images->count() }} photo(s)</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($review->is_approved)
                                        <span class="badge bg-success-subtle text-success">Approved</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($review->hasReply())
                                        <span class="badge bg-info-subtle text-info"><i class="fas fa-check me-1"></i>Replied</span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary">No Reply</span>
                                    @endif
                                </td>
                                <td class="small text-nowrap">{{ $review->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-light border btn-sm" type="button" data-bs-toggle="dropdown">
                                            <i data-feather="more-horizontal" class="icon-xs"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <button class="dropdown-item small" data-bs-toggle="modal"
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
                                                <button class="dropdown-item small" data-bs-toggle="modal"
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
                                                    <button type="submit" class="dropdown-item small">
                                                        <i class="fas {{ $review->is_approved ? 'fa-eye-slash' : 'fa-eye' }} me-2"></i>
                                                        {{ $review->is_approved ? 'Hide Review' : 'Approve Review' }}
                                                    </button>
                                                </form>
                                            </li>
                                            @if ($review->hasReply())
                                                <li>
                                                    <form action="{{ route('seller.reviews.deleteReply', $review) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item small text-danger"
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
                                <td colspan="9" class="text-center py-4 text-muted">No reviews found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end mt-3">
                {{ $reviews->links() }}
            </div>
        </div>
    </div>

    {{-- View Review Modal --}}
    <div class="modal fade" id="viewReviewModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Review Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="small text-muted fw-semibold">Product</label>
                        <p class="fw-semibold mb-0" id="viewProduct"></p>
                    </div>
                    <div class="mb-3">
                        <label class="small text-muted fw-semibold">Customer</label>
                        <p class="mb-0" id="viewCustomer"></p>
                    </div>
                    <div class="mb-3">
                        <label class="small text-muted fw-semibold">Rating</label>
                        <p class="mb-0" id="viewRating"></p>
                    </div>
                    <div class="mb-3">
                        <label class="small text-muted fw-semibold">Review</label>
                        <p class="mb-0" id="viewDescription"></p>
                    </div>
                    <div class="mb-3">
                        <label class="small text-muted fw-semibold">Your Reply</label>
                        <p class="mb-0 text-info" id="viewReply"><em>No reply yet</em></p>
                    </div>
                    <div class="mb-3">
                        <label class="small text-muted fw-semibold">Status</label>
                        <p class="mb-0" id="viewApproved"></p>
                    </div>
                    <div class="mb-0">
                        <label class="small text-muted fw-semibold">Helpful Count</label>
                        <p class="mb-0" id="viewHelpful"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light border btn-sm" data-bs-dismiss="modal">Close</button>
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
                        <h5 class="modal-title fw-bold">Reply to Review</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="small text-muted mb-3">Replying to review from: <span class="fw-semibold" id="replyCustomer"></span></p>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Your Reply</label>
                            <textarea name="reply" class="form-control" rows="4" placeholder="Write your response to this review..." id="replyTextarea"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light border btn-sm" data-bs-dismiss="modal">Cancel</button>
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
                    ? '<span class="text-success fw-semibold">' + this.dataset.reply + '</span>'
                    : '<em class="text-muted">No reply yet</em>';
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
                html += '<i class="fas fa-star ' + (i <= rating ? 'text-warning' : 'text-muted opacity-25') + '"></i> ';
            }
            return html;
        }
    </script>
@endpush
