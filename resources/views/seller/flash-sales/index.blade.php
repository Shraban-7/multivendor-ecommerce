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

                        <a href="{{ route('seller.flash-sales.details', $sale->id) }}" class="inline-flex items-center justify-center px-4 py-2 bg-brand-deep text-white text-sm font-medium rounded-xs hover:bg-brand focus:outline-none focus:ring-2 focus:ring-brand-tint disabled:opacity-50 transition-colors w-full inline-flex items-center justify-center gap-1">
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
                            class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded-xs border border-border text-ink-secondary hover:bg-surface-muted transition-colors w-full inline-flex items-center justify-center gap-1">
                            View My Submissions
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
