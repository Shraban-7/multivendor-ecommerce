@extends('frontend.layouts.app')

@section('title', 'Track Order | Account')

@section('content')
    @php
        $billing = is_string($order->billing_information)
            ? json_decode($order->billing_information, true)
            : $order->billing_information;
    @endphp
    <main class="tracking-page pb-5 sm:pb-10">

        <!-- Order Info Card -->
        <section class="max-w-4xl mx-auto mt-10 px-4">
            <div class="bg-white rounded-lg shadow p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-700">Order Summary</h2>
                    <span class="text-sm text-gray-500">Placed on:
                        <strong>{{ $order->created_at->format('M d, Y') }}</strong></span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-gray-700">
                    <div>
                        <p class="font-medium">Order ID:</p>
                        <p>#{{ $order->invoice_id }}</p>
                    </div>
                    <div>
                        <p class="font-medium">Current Status:</p>
                        <p class="text-orange-600 font-semibold">Shipped</p>
                    </div>
                    <div>
                        <p class="font-medium">Customer:</p>
                        <p>{{ $billing['customer_name'] }}</p>
                    </div>
                    <div>
                        <p class="font-medium">Payment Method:</p>
                        <p>{{ $order->payment_type->title() }}</p>
                    </div>
                    <div class="sm:col-span-2">
                        <p class="font-medium">Shipping Address:</p>
                        <p>{{ $billing['address'] }}, {{ $billing['district'] }},{{ $billing['division'] }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Status Timeline -->
        <section class="max-w-4xl mx-auto mt-10 px-4">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-6">Status History</h3>

                <ol class="relative border-l border-orange-500">
                    @forelse ($order->statusLogs as $log)
                        @php
                            $oldStatus =
                                $log->old_status instanceof \App\Enums\OrderStatus
                                    ? $log->old_status
                                    : \App\Enums\OrderStatus::from((int) $log->old_status);

                            $newStatus =
                                $log->new_status instanceof \App\Enums\OrderStatus
                                    ? $log->new_status
                                    : \App\Enums\OrderStatus::from((int) $log->new_status);
                        @endphp

                        <li class="mb-10 ml-6">
                            <span
                                class="absolute -left-3 flex items-center justify-center w-6 h-6 bg-orange-500 rounded-full ring-8 ring-white">
                                <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 00-1.414 0L9 11.586 5.707 8.293a1 1 0 10-1.414 1.414l4 4a1 1 0 001.414 0l7-7a1 1 0 000-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </span>
                            <h4 class="font-semibold text-gray-800"><span class="text-orange-600">{{ ucfirst($oldStatus->label()) }} → {{ ucfirst($newStatus->label()) }}</span></h4>
                            <p class="text-sm text-gray-600">By: {{ ucfirst($log->changed_by) }}</p>
                            @if ($log->remarks)
                                <p class="text-sm text-gray-500 mt-1 italic">“{{ $log->remarks }}”</p>
                            @endif

                            <time class="block mt-2 text-xs text-gray-400">
                                {{ $log->created_at->format('M j, Y - g:i A') }}
                            </time>
                        </li>
                    @empty
                        <p class="text-gray-500 text-sm ml-6">No status changes recorded yet.</p>
                    @endforelse
                </ol>
            </div>
        </section>

    </main>
@endsection
