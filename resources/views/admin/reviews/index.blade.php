@php
    $counts = $counts ?? ['total' => 0, 'reported' => 0, 'images' => 0, 'replies' => 0];
@endphp
@extends('admin.layouts.app')
@section('title', 'Reviews Moderation')

@section('content')

{{-- ═══ HERO ═══ --}}
<section class="bg-white rounded-sm shadow-sm overflow-hidden mb-3 relative">
    <div class="absolute top-0 left-0 right-0 h-1" style="background: linear-gradient(90deg, #B7791A, #d97706, #fbbf24);"></div>
    <div class="p-5 lg:p-6 pt-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="min-w-0">
                <nav class="flex items-center gap-1 mb-2 text-xs text-ink-tertiary">
                    <i data-lucide="star" class="text-feedback-warning" style="width:12px;height:12px;"></i>
                    <span>Reach</span>
                    <i data-lucide="chevron-right" style="width:12px;height:12px;"></i>
                    <span class="text-ink-soft font-semibold">Reviews</span>
                </nav>
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <h1 class="text-xl font-bold text-ink-emphasis mb-0">Reviews Moderation</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-feedback-warning/15 text-feedback-warning">
                        <i data-lucide="message-square" style="width:11px;height:11px;" class="me-1"></i> Reported Reviews
                    </span>
                </div>
                <p class="text-sm text-ink-secondary mb-0">Moderate reported and low-quality reviews across the marketplace.</p>
            </div>
        </div>
    </div>
</section>

{{-- Flash --}}
@if (session('success'))
    <div class="px-4 py-2 rounded-sm bg-feedback-success/10 text-feedback-success text-sm mb-3 alert-dismissible fade show">{{ session('success') }}</div>
@endif

{{-- ═══ KPI TILES ═══ --}}
@php
    $tiles = [
        ['key' => 'total',    'label' => 'Total Reviews',   'top' => '#B7791A', 'text' => 'text-feedback-warning',  'icon' => 'star'],
        ['key' => 'reported', 'label' => 'Reported',        'top' => '#ef4444', 'text' => 'text-feedback-danger',   'icon' => 'triangle-alert'],
        ['key' => 'images',   'label' => 'With Photos',     'top' => '#0ea5e9', 'text' => 'text-feedback-info',     'icon' => 'image'],
        ['key' => 'replies',  'label' => 'With Replies',    'top' => '#10b981', 'text' => 'text-feedback-success',  'icon' => 'reply'],
    ];
@endphp
<section class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-3">
    @foreach ($tiles as $tile)
        <article class="bg-white rounded-sm shadow-sm overflow-hidden relative">
            <div class="absolute top-0 left-0 right-0 h-1" style="background-color: {{ $tile['top'] }};"></div>
            <div class="p-4 pt-5">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider">{{ $tile['label'] }}</span>
                    <i data-lucide="{{ $tile['icon'] }}" class="text-ink-tertiary" style="width:14px;height:14px;"></i>
                </div>
                <h3 class="text-2xl font-bold {{ $tile['text'] }} mb-0">{{ number_format($counts[$tile['key']] ?? 0) }}</h3>
            </div>
        </article>
    @endforeach
</section>

{{-- ═══ TABLE CARD ═══ --}}
<section class="bg-white rounded-sm shadow-sm overflow-hidden">
    <div class="px-5 py-3 bg-surface-muted flex items-center gap-2">
        <i data-lucide="message-square" style="width:14px;height:14px;" class="text-ink-tertiary"></i>
        <h3 class="text-sm font-bold text-ink-emphasis mb-0">Reported Reviews</h3>
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
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary w-10">#</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Product / Shop</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Images</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Description</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Reporter</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($reviews as $review)
                    <tr class="border-t border-border hover:bg-surface-muted/40 transition-colors">
                        <td class="px-4 py-3 text-ink-tertiary">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3">
                            <div class="font-semibold text-ink-emphasis text-sm">{{ $review->product?->name ?? 'Deleted Product' }}</div>
                            <small class="text-ink-tertiary">{{ $review->product?->seller?->business_name ?? '—' }}</small>
                            @if ($review->reports->count() > 0)
                                <div class="mt-1">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-feedback-danger text-white">
                                        <i data-lucide="triangle-alert" style="width:10px;height:10px;" class="me-1"></i>
                                        {{ $review->reports->count() }} {{ Str::plural('Report', $review->reports->count()) }}
                                    </span>
                                </div>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex gap-1.5 overflow-x-auto">
                                @forelse ($review->images as $image)
                                    <img src="{{ storage_url($image->image) }}" alt="Review Image"
                                         style="width:48px;height:48px;object-fit:cover;border-radius:6px;" class="shrink-0">
                                @empty
                                    <span class="text-ink-tertiary text-xs">No images</span>
                                @endforelse
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm text-ink-soft" style="max-width:260px;">
                            <div class="truncate">{{ $review->description }}</div>
                            <div class="text-xs text-ink-tertiary mt-0.5 inline-flex items-center gap-1">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i data-lucide="star"
                                       class="{{ $i <= $review->rating ? 'text-feedback-warning' : 'opacity-20' }}"
                                       style="width:11px;height:11px;"></i>
                                @endfor
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            @if ($review->reports->isNotEmpty())
                                <div class="space-y-1">
                                    @foreach ($review->reports as $report)
                                        <div>
                                            @if ($report->user)
                                                <x-user :user="$report->user" />
                                            @elseif ($report->seller)
                                                <x-seller :seller="$report->seller" />
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-ink-tertiary text-xs">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <button type="button" class="btn btn-light btn-sm text-feedback-danger hover:text-feedback-danger"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteModal{{ $review->id }}">
                                <i data-lucide="trash-2" style="width:13px;height:13px;"></i> Delete
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="py-10 text-center">
                                <i data-lucide="star-off" class="text-ink-tertiary mx-auto mb-2" style="width:36px;height:36px;"></i>
                                <p class="text-ink-soft font-semibold mb-1">No reviews to moderate</p>
                                <p class="text-ink-tertiary text-xs">Reported reviews will appear here.</p>
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

@foreach ($reviews as $review)
    <div class="modal fade" id="deleteModal{{ $review->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <div>
                            <h5 class="modal-title font-bold text-feedback-danger">Delete Review</h5>
                            <small class="text-ink-tertiary">This action cannot be undone</small>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="bg-feedback-danger/10 rounded-xs p-4 flex items-start gap-3">
                            <i data-lucide="triangle-alert" class="text-feedback-danger shrink-0 mt-0.5" style="width:18px;height:18px;"></i>
                            <div class="text-sm text-ink-soft">Permanently delete this review and remove it from the marketplace?</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">
                            <i data-lucide="trash-2" style="width:14px;height:14px;"></i> Yes, Delete
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

@endsection
