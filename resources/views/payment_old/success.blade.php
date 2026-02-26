<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Payment Success</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-green-50 min-h-screen p-6">
    <div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
        <h1 class="text-2xl font-bold text-green-700 mb-4">✅ Payment Successful</h1>

        <p class="mb-4 text-gray-700">Below is the payment response:</p>

        <pre class="bg-gray-100 p-4 rounded overflow-x-auto text-sm text-gray-800">
        {{ json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}
        </pre>

        <a href="{{ url('/payment/test') }}" class="inline-block mt-6 text-blue-600 hover:underline">⟵ Back to test</a>
    </div>
</body>

</html>