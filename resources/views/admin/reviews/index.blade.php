@extends('admin.layouts.app')
@section('title', 'Reviews')

@section('content')
<div class="mb-3 flex justify-between items-center">
    <h4 class="mb-0">Reviews</h4>
</div>

<div class="overflow-x-auto ">
    <table id="review-table" class="w-full text-left text-sm text-ink border-collapse mb-3 bg-white table-bordered">
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
            @foreach ($reviews as $review)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                    <div class="flex flex-col">
                        <strong class="text-ink text-sm">{{ $review->product->name }}</strong>
                        <small class="text-ink-tertiary">{{ $review->product->seller->business_name }}</small>
                    </div>
                    @if ($review->reports->count() > 0)
                    <span class="badge bg-feedback-danger mt-1">
                        {{ $review->reports->count() }}
                        {{ Str::plural('Report', $review->reports->count()) }}
                    </span>
                    @endif
                </td>

                <td>
                    <div class="flex gap-2 overflow-auto">
                        @foreach ($review->images as $image)
                        <img src="{{ storage_url($image->image) }}"
                            class="img-fluid rounded-lg border shadow-sm" alt="Review Image"
                            style="height: 80px; width: 80px; object-fit: cover;">
                        @endforeach
                    </div>
                </td>
                <td>
                    {{ $review->description }}
                </td>
                <td>
                    @if ($review->reports->isNotEmpty())
                    <ul class="list-none mb-0">
                        @foreach ($review->reports as $report)
                        <li>
                            @if ($report->user)
                            <x-user :user="$report->user" />
                            @else
                            <x-seller :seller="$report->seller" />
                            @endif
                        </li>
                        @endforeach
                    </ul>
                    @else
                    @endif
                </td>

                <td>
                    <!-- Delete Button -->
                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                        data-bs-target="#deleteModal{{ $review->id }}">
                        <i data-feather="trash" class="icon-xs"></i> Delete
                    </button>

                    <!-- Delete Confirmation Modal -->
                    <div class="modal fade" id="deleteModal{{ $review->id }}" tabindex="-1"
                        aria-labelledby="deleteModalLabel{{ $review->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteModalLabel{{ $review->id }}">Confirm
                                        Deletion</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Are you sure you want to permanently delete this review?
                                </div>
                                <div class="modal-footer">
                                    <form action="{{ route('admin.reviews.destroy', $review->id) }}"
                                        method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-danger">Yes, Delete</button>
                                        <button type="button" class="btn btn-light"
                                            data-bs-dismiss="modal">Cancel</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@push('scripts')
<script>
    new DataTable('#review-table');
</script>
@endpush
@endsection