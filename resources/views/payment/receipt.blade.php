<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Payment Receipt</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,600,700" rel="stylesheet" />
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-100 py-10 px-4">
    <div class="max-w-2xl mx-auto bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">🧾 Payment Receipt</h2>
            <p class="text-sm text-gray-500">Thank you! Your payment details have been submitted successfully.</p>
        </div>

        <div class="p-6 space-y-4">
            <div class="grid grid-cols-2 text-sm">
                <div>
                    <p class="text-gray-500">Receipt ID:</p>
                    <p class="font-medium text-gray-900">{{ $payment->id }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Date:</p>
                    <p class="font-medium text-gray-900">{{ $payment->created_at->format('d M Y, h:i A') }}</p>
                </div>
            </div>

            <hr class="border-gray-200">

            <div class="grid grid-cols-2 gap-6 text-sm">
                <div>
                    <h4 class="text-gray-700 font-semibold mb-2">Customer Info</h4>
                    <p class="text-gray-900">{{ $payment->customer_name }}</p>
                    <p class="text-gray-600">{{ $payment->customer_phone }}</p>
                </div>

                <div>
                    <h4 class="text-gray-700 font-semibold mb-2">Payment Details</h4>
                    <p class="text-gray-900">Method: <span class="capitalize">{{ $payment->gateway }}</span></p>
                    <p class="text-gray-900">Amount: ৳{{ number_format($payment->amount, 2) }}</p>
                    <p class="text-gray-900">Transaction ID: {{ $payment->transaction_id }}</p>
                    <p class="text-gray-900">Status:
                        <span class="@if($payment->status === 'successful') text-green-600 @elseif($payment->status === 'failed') text-red-600 @else text-yellow-600 @endif capitalize">
                            {{ $payment->status }}
                        </span>
                    </p>
                </div>
            </div>

            @if(isset($payment->response['screenshot']))
            <div>
                <h4 class="text-gray-700 font-semibold mb-2">Screenshot</h4>
                <img src="{{ asset('storage/' . $payment->response['screenshot']) }}" alt="Payment Screenshot" class="w-64 border rounded">
            </div>
            @endif

            <div class="text-center mt-6">
                <a href="{{ url('/') }}" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded font-semibold text-sm">
                    Back to Home
                </a>
            </div>
        </div>
    </div>
</body>

</html>