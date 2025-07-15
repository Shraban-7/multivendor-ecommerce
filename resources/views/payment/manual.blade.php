<!-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manual Payment - Submit Transaction</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,600,700" rel="stylesheet" />
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen py-10 px-4">
    <div class="max-w-3xl mx-auto bg-white rounded-xl shadow-md overflow-hidden">
        <div class="p-6 sm:p-10">
            <div class="mb-6 text-center">
                <h2 class="text-3xl font-bold text-gray-800">💳 Manual Payment</h2>
                <p class="text-sm text-gray-500 mt-2">Send payment via bKash / Nagad / Rocket and submit your details below.</p>
            </div>

            @if (session('success'))
            <div class="bg-green-50 text-green-800 border border-green-200 rounded p-4 mb-6 text-sm">
                {{ session('success') }}
            </div>
            @endif

            <form method="POST" action="" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Select Payment Method</label>
                    <div class="relative">
                        <select name="method" class="w-full border border-gray-300 rounded px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                            <option value="">-- Choose Method --</option>
                            <option value="bkash">bKash</option>
                            <option value="nagad">Nagad</option>
                            <option value="rocket">Rocket</option>
                        </select>
                    </div>
                    @error('method') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Transaction ID</label>
                    <input type="text" name="transaction_id" class="w-full border border-gray-300 rounded px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                    @error('transaction_id') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Amount Paid (BDT)</label>
                    <input type="number" name="amount" step="0.01" class="w-full border border-gray-300 rounded px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                    @error('amount') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Your Name</label>
                        <input type="text" name="customer_name" class="w-full border border-gray-300 rounded px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                        @error('customer_name') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                        <input type="text" name="customer_phone" class="w-full border border-gray-300 rounded px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                        @error('customer_phone') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Upload Screenshot (optional)</label>
                    <input type="file" name="screenshot" accept="image/*" class="block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:border file:border-gray-200 file:rounded file:text-sm file:bg-gray-50 hover:file:bg-gray-100">
                    @error('screenshot') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 rounded transition">
                        Submit Payment Details
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>

</html> -->



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manual Payment</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,600,700" rel="stylesheet">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen py-10 px-4">
    <div class="max-w-3xl mx-auto bg-white rounded-xl shadow-md overflow-hidden relative">
        <!-- Floating Logos -->
        <div class="absolute -top-10 left-4 flex space-x-2">
            <img src="/images/bkash.png" alt="bKash" class="h-12">
            <img src="/images/nagad.png" alt="Nagad" class="h-12">
            <img src="/images/rocket.png" alt="Rocket" class="h-12">
        </div>

        <div class="p-6 sm:p-10 mt-6">
            <div class="text-center mb-6">
                <h2 class="text-3xl font-bold text-gray-800">Manual Payment</h2>
                <p class="text-sm text-gray-500 mt-1">Pay using bKash, Nagad, or Rocket. Then fill in the details below.</p>
            </div>

            <!-- Payment Instructions -->
            <div class="bg-yellow-50 border border-yellow-300 text-yellow-800 p-4 rounded mb-6 text-sm">
                <p class="mb-1 font-medium">Instructions:</p>
                <ul class="list-disc ml-5">
                    <li>Send the exact amount to the number below using your preferred method.</li>
                    <li>Save the transaction ID and a screenshot if available.</li>
                    <li>Submit the form below for verification.</li>
                </ul>
                <div class="mt-3 font-semibold">bKash / Nagad Number: <span class="text-gray-900">+8801XXXXXXXXX</span></div>
            </div>

            @if (session('success'))
            <div class="bg-green-100 border border-green-200 text-green-700 p-4 rounded mb-6 text-sm">
                {{ session('success') }}
            </div>
            @endif

            <form method="POST" action="" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                    <select name="method" required class="w-full border border-gray-300 rounded px-4 py-2">
                        <option value="">-- Select --</option>
                        <option value="bkash">bKash</option>
                        <option value="nagad">Nagad</option>
                        <option value="rocket">Rocket</option>
                    </select>
                    @error('method') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Transaction ID</label>
                    <input type="text" name="transaction_id" class="w-full border border-gray-300 rounded px-4 py-2" required>
                    @error('transaction_id') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Amount Paid (BDT)</label>
                    <input type="number" name="amount" step="0.01" class="w-full border border-gray-300 rounded px-4 py-2" required>
                    @error('amount') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Your Name</label>
                        <input type="text" name="customer_name" class="w-full border border-gray-300 rounded px-4 py-2" required>
                        @error('customer_name') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                        <input type="text" name="customer_phone" class="w-full border border-gray-300 rounded px-4 py-2" required>
                        @error('customer_phone') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Upload Screenshot (optional)</label>
                    <input type="file" name="screenshot" accept="image/*" class="w-full border border-gray-300 rounded px-4 py-2 file:rounded file:border file:bg-gray-50">
                    @error('screenshot') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- CAPTCHA -->
                <div class="mt-4">
                    <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
                    @error('g-recaptcha-response')
                    <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 rounded">
                        Submit Payment
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>