@php
    $stats = $stats ?? [
        'total' => 0, 'approved' => 0, 'pending' => 0,
        'replied' => 0, 'unreplied' => 0, 'avg_rating' => '—',
    ];
    $ratingDistribution = $ratingDistribution ?? [1=>['percent'=>0],2=>['percent'=>0],3=>['percent'=>0],4=>['percent'=>0],5=>['percent'=>0]];
@endphp
@extends('seller.layouts.app')
@section('title', 'Product Reviews')

@section('content')

{{-- ═══ HERO ═══ --}}
<section class="bg-white rounded-sm shadow-sm overflow-hidden mb-3 relative">
    <div class="absolute top-0 left-0 right-0 h-1" style="background: linear-gradient(90deg, #B7791A, #d97706, #fbbf24);"></div>
    <div class="p-5 lg:p-6 pt-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="min-w-0">
                <nav class="flex items-center gap-1 mb-2 text-xs text-ink-tertiary">
                    <i data-lucide="star" class="text-feedback-warning" style="width:12px;height:12px;"></i>
                    <span>Workspace</span>
                    <i data-lucide="chevron-right" style="width:12px;height:12px;"></i>
                    <span class="text-ink-soft font-semibold">Product Reviews</span>
                </nav>
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <h1 class="text-xl font-bold text-ink-emphasis mb-0">Product Reviews</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-feedback-warning/15 text-feedback-warning">
                        <i data-lucide="message-square" style="width:11px;height:11px;" class="me-1"></i> Customer Feedback
                    </span>
                </div>
                <p class="text-sm text-ink-secondary mb-0">Approve reviews, post replies and track your rating performance.</p>
            </div>
        </div>
    </div>
</section>

@if (session('success'))
    <div class="px-4 py-2 rounded-sm bg-feedback-success/10 text-feedback-success text-sm mb-3 alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- ═══ KPI TILES ═══ --}}
@php
    $tiles = [
        ['key' => 'total',      'label' => 'Total',         'top' => '#F85606', 'text' => 'text-brand-deep',       'icon' => 'star'],
        ['key' => 'approved',   'label' => 'Approved',      'top' => '#10b981', 'text' => 'text-feedback-success', 'icon' => 'circle-check'],
        ['key' => 'pending',    'label' => 'Pending',       'top' => '#ef4444', 'text' => 'text-feedback-danger',  'icon' => 'clock'],
        ['key' => 'replied',    'label' => 'Replied',       'top' => '#0ea5e9', 'text' => 'text-feedback-info',    'icon' => 'reply'],
        ['key' => 'unreplied',  'label' => 'Unreplied',     'top' => '#6b7280', 'text' => 'text-ink-secondary',    'icon' => 'message-square'],
        ['key' => 'avg_rating', 'label' => 'Avg Rating',    'top' => '#B7791A', 'text' => 'text-feedback-warning', 'icon' => 'chart-line', 'value' => $stats['avg_rating']],
    ];
@endphp
<section class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 mb-3">
    @foreach ($tiles as $tile)
        <article class="bg-white rounded-sm shadow-sm overflow-hidden relative">
            <div class="absolute top-0 left-0 right-0 h-1" style="background-color: {{ $tile['top'] }};"></div>
            <div class="p-3 pt-4">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider">{{ $tile['label'] }}</span>
                    <i data-lucide="{{ $tile['icon'] }}" class="text-ink-tertiary" style="width:13px;height:13px;"></i>
                </div>
                <h3 class="text-xl font-bold {{ $tile['text'] }} mb-0">
                    @if (isset($tile['value']))
                        {{ $tile['value'] }}
                    @else
                        {{ number_format($stats[$tile['key']] ?? 0) }}
                    @endif
                </h3>
            </div>
        </article>
    @endforeach
</section>

{{-- ═══ FILTER + TABLE CARD ═══ --}}
<section class="bg-white rounded-sm shadow-sm overflow-hidden">
    <div class="px-5 py-3 bg-surface-muted flex items-center gap-2 flex-wrap">
        <i data-lucide="sliders-horizontal" style="width:14px;height:14px;" class="text-ink-tertiary"></i>
        <h3 class="text-sm font-bold text-ink-emphasis mb-0">Search & Filter</h3>
        <div class="grow"></div>
        <div class="flex flex-wrap gap-1.5">
            <a href="{{ route('seller.reviews.index') }}"
               class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold {{ !request('status') && !request('rating') ? 'bg-brand text-white' : 'bg-surface-muted text-ink-secondary' }}">
                All
            </a>
            @foreach ([5, 4, 3, 2, 1] as $star)
                <a href="{{ route('seller.reviews.index', ['rating' => $star, 'status' => request('status')]) }}"
                   class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold {{ request('rating') == $star ? 'bg-feedback-warning text-white' : 'bg-surface-muted text-ink-secondary' }}">
                    {{ $star }} <i data-lucide="star" style="width:10px;height:10px;" class="ms-1"></i>
                    @if (($ratingDistribution[$star]['percent'] ?? 0) > 0)
                        <span class="ms-1 opacity-75">{{ $ratingDistribution[$star]['percent'] }}%</span>
                    @endif
                </a>
            @endforeach
        </div>
    </div>
    <div class="p-4 border-t border-border">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-2">
            <div class="md:col-span-8 flex flex-wrap gap-1.5">
                <a href="{{ route('seller.reviews.index', ['status' => 'approved', 'rating' => request('rating')]) }}"
                   class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold {{ request('status') === 'approved' ? 'bg-feedback-success text-white' : 'bg-surface-muted text-ink-secondary' }}">
                    <i data-lucide="check-circle-2" style="width:11px;height:11px;" class="me-1"></i> Approved
                </a>
                <a href="{{ route('seller.reviews.index', ['status' => 'pending', 'rating' => request('rating')]) }}"
                   class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold {{ request('status') === 'pending' ? 'bg-feedback-danger text-white' : 'bg-surface-muted text-ink-secondary' }}">
                    <i data-lucide="hourglass" style="width:11px;height:11px;" class="me-1"></i> Pending
                </a>
                <a href="{{ route('seller.reviews.index', ['status' => 'replied', 'rating' => request('rating')]) }}"
                   class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold {{ request('status') === 'replied' ? 'bg-feedback-info text-white' : 'bg-surface-muted text-ink-secondary' }}">
                    <i data-lucide="reply" style="width:11px;height:11px;" class="me-1"></i> Replied
                </a>
                <a href="{{ route('seller.reviews.index', ['status' => 'unreplied', 'rating' => request('rating')]) }}"
                   class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold {{ request('status') === 'unreplied' ? 'bg-feedback-warning text-white' : 'bg-surface-muted text-ink-secondary' }}">
                    <i data-lucide="message-square" style="width:11px;height:11px;" class="me-1"></i> Unreplied
                </a>
            </div>
            <form method="GET" class="md:col-span-4 relative">
                @if (request('status')) <input type="hidden" name="status" value="{{ request('status') }}"> @endif
                @if (request('rating')) <input type="hidden" name="rating" value="{{ request('rating') }}"> @endif
                <i data-lucide="search" class="absolute top-1/2 -translate-y-1/2 text-ink-tertiary" style="width:14px;height:14px; left: 10px;"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search reviews…"
                       class="w-full pl-8 pr-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
            </form>
        </div>
    </div>

    <div class="px-4 pt-4 pb-1 text-xs text-ink-tertiary">
        Showing <span class="text-ink-emphasis font-semibold">{{ $reviews->firstItem() ?? 0 }}</span>
        – <span class="text-ink-emphasis font-semibold">{{ $reviews->lastItem() ?? 0 }}</span>
        of <span class="text-ink-emphasis font-semibold">{{ $reviews->total() }}</span> reviews
    </div>

    <div class="overflow-x-auto px-4 pb-4">
        <table class="w-full text-left text-sm border-collapse">
            <thead class="bg-surface-muted">
                <tr>
                    <th class="px-4 py-2.5 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary w-10">#</th>
                    <th class="px-4 py-2.5 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Product</th>
                    <th class="px-4 py-2.5 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Customer</th>
                    <th class="px-4 py-2.5 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Rating</th>
                    <th class="px-4 py-2.5 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Review</th>
                    <th class="px-4 py-2.5 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Status</th>
                    <th class="px-4 py-2.5 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Reply</th>
                    <th class="px-4 py-2.5 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Date</th>
                    <th class="px-4 py-2.5 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary text-right">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($reviews as $review)
                    <tr class="border-t border-border hover:bg-surface-muted/40 transition-colors">
                        <td class="px-4 py-3 text-ink-tertiary">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                @if ($review->product && $review->product->thumbnail)
                                    <img src="{{ asset($review->product->thumbnail) }}" alt=""
                                         style="width: 36px; height: 36px; object-fit: cover; border-radius: 6px;">
                                @endif
                                <span class="font-semibold text-ink-emphasis text-sm">{{ $review->product?->name ?? 'Deleted Product' }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="font-medium text-ink-emphasis">{{ $review->user?->name ?? 'Guest' }}</div>
                            <small class="text-ink-tertiary">{{ $review->user?->phone ?? '—' }}</small>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            @for ($i = 1; $i <= 5; $i++)
                                <i data-lucide="star"
                                   class="{{ $i <= $review->rating ? 'text-feedback-warning' : 'text-ink-tertiary opacity-25' }}"
                                   style="width: 12px; height: 12px;"></i>
                            @endfor
                        </td>
                        <td class="px-4 py-3" style="max-width: 240px;">
                            <div class="text-sm text-ink-soft truncate">{{ $review->description }}</div>
                            @if ($review->images->count() > 0)
                                <small class="text-ink-tertiary inline-flex items-center gap-1 mt-0.5">
                                    <i data-lucide="image" style="width:11px;height:11px;"></i> {{ $review->images->count() }} photo(s)
                                </small>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if ($review->is_approved)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider bg-feedback-success/15 text-feedback-success">
                                    <i data-lucide="check" style="width:11px;height:11px;" class="me-1"></i> Approved
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider bg-feedback-danger/15 text-feedback-danger">
                                    <i data-lucide="hourglass" style="width:11px;height:11px;" class="me-1"></i> Pending
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if ($review->hasReply())
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider bg-feedback-info/15 text-feedback-info">
                                    <i data-lucide="check" style="width:11px;height:11px;" class="me-1"></i> Replied
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider bg-surface-muted text-ink-tertiary">
                                    No Reply
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-xs text-ink-secondary whitespace-nowrap">
                            <i data-lucide="calendar" style="width:11px;height:11px;" class="me-1 align-text-bottom text-ink-tertiary"></i>
                            {{ $review->created_at->format('d M Y') }}
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="dropdown inline-block">
                                <button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown">
                                    <i data-lucide="more-horizontal" style="width:14px;height:14px;"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end py-1" style="min-width:180px;">
                                    <li>
                                        <button class="dropdown-item text-sm py-1.5" data-bs-toggle="modal"
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
                                            <i data-lucide="eye" style="width:13px;height:13px;" class="me-2 text-ink-tertiary"></i> View Details
                                        </button>
                                    </li>
                                    <li>
                                        <button class="dropdown-item text-sm py-1.5" data-bs-toggle="modal"
                                                data-bs-target="#replyModal"
                                                data-review-id="{{ $review->id }}"
                                                data-customer="{{ $review->user?->name ?? 'Guest' }}"
                                                data-current-reply="{{ $review->seller_reply }}">
                                            <i data-lucide="reply" style="width:13px;height:13px;" class="me-2 text-ink-tertiary"></i>
                                            {{ $review->hasReply() ? 'Edit Reply' : 'Reply' }}
                                        </button>
                                    </li>
                                    <li>
                                        <form action="{{ route('seller.reviews.toggleApproval', $review) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-sm py-1.5">
                                                <i data-lucide="{{ $review->is_approved ? 'eye-off' : 'eye' }}" style="width:13px;height:13px;" class="me-2 text-ink-tertiary"></i>
                                                {{ $review->is_approved ? 'Hide Review' : 'Approve Review' }}
                                            </button>
                                        </form>
                                    </li>
                                    @if ($review->hasReply())
                                        <li><hr class="dropdown-divider my-1"></li>
                                        <li>
                                            <form action="{{ route('seller.reviews.deleteReply', $review) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="dropdown-item text-sm py-1.5 text-feedback-danger"
                                                        onclick="return confirm('Delete your reply?')">
                                                    <i data-lucide="trash-2" style="width:13px;height:13px;" class="me-2"></i> Delete Reply
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
                        <td colspan="9">
                            <div class="py-10 text-center">
                                <i data-lucide="star-off" class="text-ink-tertiary mx-auto mb-2" style="width:36px;height:36px;"></i>
                                <p class="text-ink-soft font-semibold mb-1">No reviews yet</p>
                                <p class="text-ink-tertiary text-xs">Customer reviews will appear here once they rate your products.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="flex justify-end p-4 border-t border-border">
        {{ $reviews->links() }}
    </div>
</section>

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
                    <label class="text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider">Product</label>
                    <p class="font-semibold mb-0 text-ink-emphasis" id="viewProduct"></p>
                </div>
                <div class="mb-3">
                    <label class="text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider">Customer</label>
                    <p class="mb-0 text-ink-emphasis" id="viewCustomer"></p>
                </div>
                <div class="mb-3">
                    <label class="text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider">Rating</label>
                    <p class="mb-0" id="viewRating"></p>
                </div>
                <div class="mb-3">
                    <label class="text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider">Review</label>
                    <p class="mb-0 text-ink-soft" id="viewDescription"></p>
                </div>
                <div class="mb-3">
                    <label class="text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider">Your Reply</label>
                    <p class="mb-0 text-feedback-info" id="viewReply"><em class="text-ink-tertiary">No reply yet</em></p>
                </div>
                <div class="mb-3">
                    <label class="text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider">Status</label>
                    <p class="mb-0 text-ink-emphasis" id="viewApproved"></p>
                </div>
                <div class="mb-0">
                    <label class="text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider">Helpful Count</label>
                    <p class="mb-0 text-ink-emphasis" id="viewHelpful"></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
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
                    <p class="text-sm text-ink-tertiary mb-3">Replying to review from: <span class="font-semibold text-ink-emphasis" id="replyCustomer"></span></p>
                    <div class="mb-3">
                        <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Your Reply</label>
                        <x-textarea-input name="reply" value="" rows="4" placeholder="Write your response to this review..." id="replyTextarea" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i data-lucide="send" style="width:14px;height:14px;"></i> Submit Reply
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.querySelectorAll('[data-bs-target="#viewReviewModal"]').forEach(btn => {
            btn.addEventListener('click', function () {
                document.getElementById('viewProduct').textContent = this.dataset.product;
                document.getElementById('viewCustomer').textContent = this.dataset.customer;
                document.getElementById('viewRating').innerHTML = renderStars(parseInt(this.dataset.rating));
                if (window.renderIcons) {
                    window.renderIcons(document.getElementById('viewRating'));
                }
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
                html += '<i data-lucide="star" class="' + (i <= rating ? 'text-feedback-warning' : 'text-ink-tertiary opacity-25') + '" style="width:14px;height:14px;display:inline;vertical-align:text-bottom;"></i> ';
            }
            return html;
        }
    </script>
@endpush

@endsection
