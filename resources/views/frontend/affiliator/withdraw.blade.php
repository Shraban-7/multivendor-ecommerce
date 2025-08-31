@extends('frontend.layouts.app')

@section('title', 'Withdraw')

@section('content')
    <main class="withdraw-page py-10 bg-gray-50">
        <div class="container mx-auto px-4">

            <!-- Page Heading -->
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Withdraw Funds</h1>

            <div class="grid md:grid-cols-3 gap-8">

                <!-- Withdraw Form -->
                <div class="md:col-span-1">
                    <div class="bg-white shadow rounded-2xl p-6">
                        <h2 class="text-lg font-semibold text-gray-700 mb-4">Request Withdrawal</h2>

                        <!-- Example: Current Balance -->
                        <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg">
                            <p class="text-sm text-gray-600">Available Balance</p>
                            <p class="text-xl font-bold text-green-600">{{ money($available_balance) }}</p>
                        </div>

                        <form action="{{ route('affiliator.withdraw') }}" method="POST" class="space-y-4">
                            @csrf

                            <!-- Amount -->
                            <div>
                                <label for="amount" class="block text-sm font-medium text-gray-600 mb-1">Amount</label>
                                <input type="number" name="amount" id="amount" step="0.01" min="1"
                                    class="w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring focus:ring-blue-200">
                            </div>

                            <!-- Method -->
                            <div>
                                <label for="method" class="block text-sm font-medium text-gray-600 mb-1">Withdraw
                                    Method</label>
                                <select name="method" id="method"
                                    class="w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring focus:ring-blue-200">
                                    @foreach ($payment_methods as $key => $method)
                                        <option value="{{ $key }}">{{ $method }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="account_details" class="block text-sm font-medium text-gray-600 mb-1">Account
                                    Details</label>
                                <textarea name="account_details" id="account_details" rows="4"
                                    class="w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring focus:ring-blue-200"></textarea>
                            </div>


                            <!-- Submit -->
                            <div>
                                <button type="submit"
                                    class="w-full bg-primary text-white px-3 py-2 rounded text-sm hover:opacity-90 transition">
                                    Submit Request
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Withdraw History -->
                <div class="md:col-span-2">
                    <div class="bg-white shadow rounded-2xl p-6">
                        <h2 class="text-lg font-semibold text-gray-700 mb-4">Withdrawal History</h2>

                        <div class="overflow-x-auto">
                            <table class="w-full border-collapse text-sm text-left">
                                <thead>
                                    <tr class="bg-gray-100 text-gray-600">
                                        <th class="p-3">Amount</th>
                                        <th class="p-3">Method</th>
                                        <th class="p-3">Status</th>
                                        <th class="p-3">Account Details</th> 
                                        <th class="p-3">Requested At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($withdraw_histories as $history)
                                        <tr class="border-t">
                                            <td class="p-3 font-semibold text-gray-800">${{ money($history->amount) }}</td>
                                            <td class="p-3">{{ ucfirst($history->method) }}</td>
                                            <td class="p-3">
                                                <span
                                                    class="px-2 py-1 text-xs rounded-full bg-{{ $history->statusColor() }}-100 text-{{ $history->statusColor() }}-600">
                                                    {{ $history->label() }}
                                                </span>
                                            </td>
                                            <td class="p-3">{{ $history->account_details }}</td>
                                            <td class="p-3">{{ $history->created_at->format('Y-m-d') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="p-3 text-center text-gray-500">No withdrawal history
                                                found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>

            </div>

        </div>
    </main>
@endsection
