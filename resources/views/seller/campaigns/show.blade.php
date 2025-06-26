@extends('seller.layouts.app')
@section('title', 'Campaign Details')
@section('content')

<div class="card mb-4 shadow-sm">
    <div class="row g-0">
        @if ($campaign->image)
            <div class="col-md-5">
                <img src="{{ storage_url($campaign->image) }}" class="img-fluid rounded-start w-100 h-100 object-fit-cover" alt="Campaign Image">
            </div>
        @endif
        <div class="col-md-7">
            <div class="card-body">
                <h4 class="card-title mb-3">Campaign Details</h4>

                <p class="mb-2">
                    <strong>Start Date:</strong> {{ \Carbon\Carbon::parse($campaign->start_date)->format('d M Y, h:i A') }} <br>
                    <strong>End Date:</strong> {{ \Carbon\Carbon::parse($campaign->end_date)->format('d M Y, h:i A') }}
                </p>

                <p class="mb-2">
                    <strong>Status:</strong>
                    <span class="badge {{ $campaign->is_active ? 'bg-success' : 'bg-secondary' }}">
                        {{ $campaign->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </p>

                <p class="mb-2">
                    <strong>Description:</strong><br>
                    {!! nl2br(e($campaign->description)) !!}
                </p>

                <div class="mt-4">
                    <a href="{{ route('seller.campaigns.edit', $campaign->id) }}" class="btn btn-sm btn-primary me-2">
                        <i data-feather="edit" class="icon-xs"></i> Edit Campaign
                    </a>
                    <a href="{{ route('seller.campaigns.index') }}" class="btn btn-sm btn-outline-secondary">Back to List</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
