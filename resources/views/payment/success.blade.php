@extends('frontend.layouts.app')

@section('title', 'Payment Successful')

@section('content')
    <main class="pb-10">
        <section class="container section-padding my-12 md:my-16">
            <div class="mx-auto max-w-lg flex flex-col items-center text-center gap-3 md:gap-4">
                <span
                    class="mb-2 flex h-14 w-14 xsm:h-16 xsm:w-16 items-center justify-center rounded-full border-2 xsm:border-[5px] border-leaf-green bg-leaf-green/20 text-3xl xsm:text-4xl text-leaf-green">
                    <i class="fa-solid fa-check"></i>
                </span>

                <h1 class="text-lg xsm:text-xl md:text-2xl font-semibold text-theme-dark">
                    Payment Successful
                </h1>
                <p class="text-sm text-davy-gray w-11/12 sm:w-4/5">
                    Your payment was received and the order has been placed. A confirmation will follow shortly.
                </p>

                @if (! empty($order))
                    <div class="mt-2 w-full rounded-sm border border-ds-border-default bg-ds-surface-muted px-4 py-3 text-left">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <p class="text-xs text-davy-gray">Invoice</p>
                                <p class="text-sm font-semibold text-theme-dark">{{ $order->invoice_id }}</p>
                            </div>
                            <span class="rounded-sm bg-leaf-green/15 px-2.5 py-1 text-xs font-medium text-leaf-green">
                                Paid
                            </span>
                        </div>
                    </div>
                @endif

                <p class="mt-2 text-sm text-davy-gray">
                    Redirecting in <span id="countdown" class="font-semibold text-primary">5</span> seconds…
                </p>

                <div class="mt-3 flex flex-wrap items-center justify-center gap-2 xsm:gap-4 text-xs xsm:text-sm">
                    @if (! empty($order))
                        <a href="{{ route('orders.tracking', $order->invoice_id) }}"
                            class="inline-flex items-center gap-2 border-2 border-primary/30 px-4 py-2 font-bold uppercase text-primary eq hover:bg-primary hover:text-white rounded-sm">
                            Track Order
                        </a>
                        <a href="{{ route('orders.details', $order->invoice_id) }}"
                            class="inline-flex items-center border-2 border-primary bg-primary px-4 py-2 font-bold uppercase text-white eq hover:bg-theme-dark hover:border-theme-dark rounded-sm">
                            View Order
                        </a>
                    @else
                        <a href="{{ route('orders.index') }}"
                            class="inline-flex items-center border-2 border-primary bg-primary px-4 py-2 font-bold uppercase text-white eq hover:bg-theme-dark hover:border-theme-dark rounded-sm">
                            My Orders
                        </a>
                    @endif
                    <a href="{{ route('products.index') }}"
                        class="inline-flex items-center border-2 border-primary/30 px-4 py-2 font-bold uppercase text-primary eq hover:bg-primary hover:text-white rounded-sm">
                        Continue Shopping
                    </a>
                </div>
            </div>
        </section>
    </main>
@endsection

@push('scripts')
    <script>
        let timeLeft = 5;
        const countdownElement = document.getElementById('countdown');
        const redirectUrl = @json(
            ! empty($order)
                ? route('orders.details', $order->invoice_id)
                : route('orders.index')
        );

        const timer = setInterval(() => {
            timeLeft -= 1;
            if (countdownElement) {
                countdownElement.textContent = Math.max(0, timeLeft);
            }
            if (timeLeft <= 0) {
                clearInterval(timer);
                window.location.href = redirectUrl;
            }
        }, 1000);
    </script>
@endpush
