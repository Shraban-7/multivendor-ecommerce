@extends('seller.layouts.app')

@section('content')
    <h4 class="mb-4">Flash Sales</h4>

    <!-- Active Flash Sales -->
    <h5 class="mb-3">Active Flash Sales</h5>
    <div class="row">
        @foreach ($flashSales as $sale)
            <div class="col-md-4">
                <div class="card shadow-sm mb-3">
                    <div class="card-body">
                        <h5>{{ $sale->title }}</h5>
                        <p>{!! $sale->description !!}</p>

                        <p class="text-muted">
                            {{ $sale->start_time }} to {{ $sale->end_time }}
                        </p>

                        <a href="{{ route('seller.flash-sales.details', $sale->id) }}" class="btn btn-primary w-100">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Previous Flash Sales -->
    <h5 class="mt-5 mb-3">My Previous Flash Sales</h5>
    <div class="row">
        @foreach ($sellerFlashSales as $sale)
            <div class="col-md-4">
                <div class="card shadow-sm mb-3 border-secondary">
                    <div class="card-body">
                        <h5>{{ $sale->name }}</h5>
                        <p>{!! $sale->description !!}</p>

                        <a href="{{ route('seller.flash-sales.details', $sale->id) }}"
                            class="btn btn-outline-secondary w-100">
                            View My Submissions
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
