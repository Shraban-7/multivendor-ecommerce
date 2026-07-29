@extends('admin.layouts.app')
@section('title', 'Reviews')

@section('content')
    <div class="flex justify-between items-start mb-4">
        <div>
            <h1 class="text-xl font-semibold text-ink">Reviews</h1>
            <p class="text-sm text-ink-secondary mt-1">Manage reported product reviews</p>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-ink border-collapse">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Product / Shop</th>
                    <th>Images</th>
                    <th>Description</th>
                    <th>Reporter</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($reviews as $review)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <div class="flex flex-col">
                            <strong class="text-ink text-sm">{{ $review->product->name }}</strong>
                            <small class="text-ink-tertiary">{{ $review->product->seller->business_name }}</small>
                        </div>
                        @if ($review->reports->count() > 0)
                        <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-white bg-red-500 rounded-full mt-1">
                            {{ $review->reports->count() }}
                            {{ Str::plural('Report', $review->reports->count()) }}
                        </span>
                        @endif
                    </td>

                    <td>
                        <div class="flex gap-2 overflow-auto">
                            @forelse ($review->images as $image)
                            <img src="{{ storage_url($image->image) }}"
                                class="border rounded-xs shadow-sm" alt="Review Image"
                                style="height: 64px; width: 64px; object-fit: cover;">
                            @empty
                            <span class="text-ink-tertiary text-xs">No images</span>
                            @endforelse
                        </div>
                    </td>
                    <td class="text-ink-secondary max-w-xs truncate">{{ $review->description }}</td>
                    <td>
                        @if ($review->reports->isNotEmpty())
                        <div class="space-y-1">
                            @foreach ($review->reports as $report)
                            <div>
                                @if ($report->user)
                                <x-user :user="$report->user" />
                                @else
                                <x-seller :seller="$report->seller" />
                                @endif
                            </div>
                            @endforeach
                        </div>
                        @else
                        <span class="text-ink-tertiary text-xs">—</span>
                        @endif
                    </td>

                    <td>
                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                            data-bs-target="#deleteModal{{ $review->id }}">
                            <i data-lucide="trash" class="icon-xs"></i> Delete
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-8 text-ink-tertiary">No reviews found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="flex justify-end mt-4">
        {{ $reviews->links() }}
    </div>

    @foreach ($reviews as $review)
    <div class="modal fade" id="deleteModal{{ $review->id }}" tabindex="-1"
        aria-labelledby="deleteModalLabel{{ $review->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-b border-border">
                    <h5 class="modal-title text-sm font-semibold text-ink" id="deleteModalLabel{{ $review->id }}">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-sm text-ink-secondary">
                    Are you sure you want to permanently delete this review?
                </div>
                <div class="modal-footer border-t border-border">
                    <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST">
                        @csrf
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Yes, Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach

@endsection