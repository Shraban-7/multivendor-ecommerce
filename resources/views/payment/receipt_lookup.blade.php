<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Find Receipt</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center px-4">
    <div class="bg-white p-6 rounded-lg shadow max-w-md w-full">
        <h2 class="text-xl font-bold mb-4 text-center">🔍 Find Your Payment Receipt</h2>

        @if (session('error'))
        <div class="bg-red-100 text-red-700 p-2 rounded mb-4 text-sm">{{ session('error') }}</div>
        @endif

        <form action="" method="POST" class="space-y-4">
            @csrf
            <label class="block">
                <span class="text-sm text-gray-700">Transaction ID</span>
                <input type="text" name="transaction_id" required class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500">
            </label>

            <button class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 rounded">
                Search Receipt
            </button>
        </form>
    </div>
</body>

</html>