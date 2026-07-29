@extends('seller.layouts.app')

@section('content')
    <h4 class="font-bold mb-4 text-ink">Flash Sales</h4>

    <h5 class="font-semibold mb-3">Active Flash Sales</h5>
    <div class="grid grid-cols-1">
        @foreach ($flashSales as $sale)
            <div class="md:col-span-1">
                <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm mb-3" style="border-radius: 12px;">
                    <div class="p-5">
                        <h5 class="font-semibold">{{ $sale->title }}</h5>
                        <p>{!! $sale->description !!}</p>

                        <p class="text-ink-tertiary text-sm">
                            {{ $sale->start_time->format('d M Y, h:i A') }}
                            to
                            {{ $sale->end_time->format('d M Y, h:i A') }}
                        </p>

                        <a href="{{ route('seller.flash-sales.details', $sale->id) }}" class="btn btn-primary w-full">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <h5 class="font-semibold mt-5 mb-3">My Previous Flash Sales</h5>
    <div class="grid grid-cols-1">
        @foreach ($sellerFlashSales as $sale)
            <div class="md:col-span-1">
                <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm mb-3" style="border-radius: 12px; border-left: 4px solid #dee2e6;">
                    <div class="p-5">
                        <h5 class="font-semibold">{{ $sale->name }}</h5>
                        <p>{!! $sale->description !!}</p>

                        <a href="{{ route('seller.flash-sales.details', $sale->id) }}"
                            class="btn btn-light w-full">
                            View My Submissions
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
