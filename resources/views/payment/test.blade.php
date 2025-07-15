<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Test Payment</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
        <h2 class="text-2xl font-semibold mb-6 text-center">Test Payment - AamarPay</h2>

        @if (session('error'))
        <div class="bg-red-100 text-red-600 p-3 rounded mb-4">
            {{ session('error') }}
        </div>
        @endif

        <form method="GET" action="{{ route('payment.pay') }}">
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-1">Amount (BDT)</label>
                <input type="number" name="amount" value="100" class="w-full border border-gray-300 p-2 rounded" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-1">Name</label>
                <input type="text" name="cus_name" value="John Doe" class="w-full border border-gray-300 p-2 rounded" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-1">Email</label>
                <input type="email" name="cus_email" value="john@example.com" class="w-full border border-gray-300 p-2 rounded" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-1">Phone</label>
                <input type="text" name="cus_phone" value="017XXXXXXXX" class="w-full border border-gray-300 p-2 rounded" required>
            </div>

            <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 rounded hover:bg-indigo-700 transition">
                Pay Now
            </button>
        </form>
    </div>
</body>

</html>