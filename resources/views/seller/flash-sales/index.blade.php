@extends('seller.layouts.app')

@section('content')
    <h4 class="fw-bold mb-4 text-dark">Flash Sales</h4>

    <h5 class="fw-semibold mb-3">Active Flash Sales</h5>
    <div class="row">
        @foreach ($flashSales as $sale)
            <div class="col-md-4">
                <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px;">
                    <div class="card-body">
                        <h5 class="fw-semibold">{{ $sale->title }}</h5>
                        <p>{!! $sale->description !!}</p>

                        <p class="text-muted small">
                            {{ $sale->start_time->format('d M Y, h:i A') }}
                            to
                            {{ $sale->end_time->format('d M Y, h:i A') }}
                        </p>

                        <a href="{{ route('seller.flash-sales.details', $sale->id) }}" class="btn btn-primary w-100 d-inline-flex align-items-center justify-content-center gap-1">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <h5 class="fw-semibold mt-5 mb-3">My Previous Flash Sales</h5>
    <div class="row">
        @foreach ($sellerFlashSales as $sale)
            <div class="col-md-4">
                <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px; border-left: 4px solid #dee2e6;">
                    <div class="card-body">
                        <h5 class="fw-semibold">{{ $sale->name }}</h5>
                        <p>{!! $sale->description !!}</p>

                        <a href="{{ route('seller.flash-sales.details', $sale->id) }}"
                            class="btn btn-outline-secondary w-100 d-inline-flex align-items-center justify-content-center gap-1">
                            View My Submissions
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
