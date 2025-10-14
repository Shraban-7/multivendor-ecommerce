<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Order Tracking</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 font-sans">

    <!-- Order Info Card -->
    <section class="max-w-4xl mx-auto mt-10 px-4">
        <div class="bg-white rounded-lg shadow p-6 space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-700">Order Summary</h2>
                <span class="text-sm text-gray-500">Placed on: <strong>Oct 12, 2025</strong></span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-gray-700">
                <div>
                    <p class="font-medium">Order ID:</p>
                    <p>#123456</p>
                </div>
                <div>
                    <p class="font-medium">Current Status:</p>
                    <p class="text-orange-600 font-semibold">Shipped</p>
                </div>
                <div>
                    <p class="font-medium">Customer:</p>
                    <p>Jane Doe</p>
                </div>
                <div>
                    <p class="font-medium">Payment Method:</p>
                    <p>Credit Card</p>
                </div>
                <div class="sm:col-span-2">
                    <p class="font-medium">Shipping Address:</p>
                    <p>123 Main Street, Springfield, IL 62704, USA</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Status Timeline -->
    <section class="max-w-4xl mx-auto mt-10 px-4">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-6">Status History</h3>

            <ol class="relative border-l border-orange-500">
                <!-- Example Status Log Item -->
                <li class="mb-10 ml-6">
                    <span class="absolute -left-3 flex items-center justify-center w-6 h-6 bg-orange-500 rounded-full ring-8 ring-white">
                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 00-1.414 0L9 11.586 5.707 8.293a1 1 0 10-1.414 1.414l4 4a1 1 0 001.414 0l7-7a1 1 0 000-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </span>
                    <h4 class="font-semibold text-gray-800">Status Changed: <span class="text-orange-600">Processing → Shipped</span></h4>
                    <p class="text-sm text-gray-600">By: Admin</p>
                    <p class="text-sm text-gray-500 mt-1 italic">"Order packed and handed to courier."</p>
                    <time class="block mt-2 text-xs text-gray-400">Oct 13, 2025 - 10:45 AM</time>
                </li>

                <li class="mb-10 ml-6">
                    <span class="absolute -left-3 flex items-center justify-center w-6 h-6 bg-orange-500 rounded-full ring-8 ring-white">
                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 00-1.414 0L9 11.586 5.707 8.293a1 1 0 10-1.414 1.414l4 4a1 1 0 001.414 0l7-7a1 1 0 000-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </span>
                    <h4 class="font-semibold text-gray-800">Status Changed: <span class="text-orange-600">Pending → Processing</span></h4>
                    <p class="text-sm text-gray-600">By: Seller</p>
                    <p class="text-sm text-gray-500 mt-1 italic">"Preparing your items."</p>
                    <time class="block mt-2 text-xs text-gray-400">Oct 12, 2025 - 4:30 PM</time>
                </li>

                <!-- Add more status logs as needed -->
            </ol>
        </div>
    </section>

    <!-- Footer -->
    <footer class="mt-16 p-4 text-center text-sm text-gray-400">
        &copy; 2025 Your Company. All rights reserved.
    </footer>

</body>

</html>