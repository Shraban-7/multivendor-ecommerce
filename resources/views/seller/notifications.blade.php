@extends('seller.layouts.app')
@section('title', 'Notifications')

@section('content')
    <div class="row justify-content-start">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h4 class="mb-0 fw-bold">Notifications</h4>
                </div>

                <div class="card-body">
                    @if ($notifications->count())
                        <ul class="list-unstyled mb-0">
                            @foreach ($notifications as $notification)
                                <li
                                    class="border rounded mb-3 p-3
                                           @if (!$notification->is_read) bg-light border-primary @else bg-white @endif">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <h5 class="mb-1 text-dark">
                                            {{ $notification->title }}
                                        </h5>
                                        @if (!$notification->is_read)
                                            <span class="badge bg-primary">New</span>
                                        @endif
                                    </div>

                                    <p class="mb-2 text-muted">
                                        {{ $notification->message }}
                                    </p>

                                    {{-- Uncomment if linking to a target --}}
                                    {{-- @if ($notification->target_type && $notification->target_id)
                                        <a href="{{ route('target.route', [$notification->target_type, $notification->target_id]) }}"
                                           class="text-decoration-none text-primary small">
                                           View Details
                                        </a>
                                    @endif --}}

                                    <div class="text-muted small mt-2">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-bell-slash fa-3x mb-3"></i>
                            <p class="mb-0">No notifications found.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
@endsection
