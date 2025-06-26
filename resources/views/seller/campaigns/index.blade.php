@extends('seller.layouts.app')
@section('title', 'Campaigns')
@section('content')

    <div class="mb-3 d-flex justify-content-between align-items-end">
        <h4 class="mb-0">Campaigns</h4>
        <a href="{{ route('seller.campaigns.create') }}" class="btn btn-theme">
            <i data-feather="plus" class="icon-xs"></i> Add Campaign
        </a>
    </div>

    <div class="row">
        @forelse ($campaigns as $campaign)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card shadow-sm h-100">
                    <a href="{{ route('seller.campaigns.show', $campaign->id) }}">
                        <img src="{{ storage_url($campaign->image) }}" class="card-img-top"
                            style="height: 180px; object-fit: cover;" alt="Campaign Image">
                    </a>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <span>
                                {{ $campaign->start_date->format('d M Y H:i') }}
                                -
                                {{ $campaign->end_date->format('d M Y H:i') }}
                            </span>
                            <a href="{{ route('seller.campaigns.edit', $campaign->id) }}"
                                class="btn btn-sm btn-light border">
                                <i data-feather="edit" class="icon-xs"></i> Edit
                            </a>
                        </div>

                        <div class="d-flex mb-2">
                            <a href="{{ route('seller.campaigns.show', $campaign->id) }}">
                                <h3 class="card-title me-1 mb-0">{{ $campaign->title }}</h3>
                            </a>
                            <div>
                                <span class="badge {{ $campaign->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $campaign->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>

                        <p class="card-text">{{ Str::limit($campaign->description, 80) }}</p>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">No campaigns found.</div>
            </div>
        @endforelse
    </div>

@endsection
