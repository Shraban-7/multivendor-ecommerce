@extends('frontend.layouts.app')
@section('title', 'Withdraw')

@section('dashboard')
    <div class="space-y-6">
        <h1 class="text-lg font-bold text-[#191919]">Withdraw Funds</h1>

        <div class="grid md:grid-cols-3 gap-6 items-start">
            {{-- Withdraw Form --}}
            <div class="bg-white rounded-sm border border-[#E5E5E5]">
                <div class="px-5 py-3.5 border-b border-[#E5E5E5]">
                    <h2 class="text-base font-semibold text-[#191919]">Request Withdrawal</h2>
                </div>
                <div class="p-5 space-y-4">
                    <div class="p-4 bg-green-50 border border-green-200 rounded-sm">
                        <p class="text-xs text-[#595959]">Available Balance</p>
                        <p class="text-xl font-bold text-green-600">{{ money($available_balance) }}</p>
                    </div>

                    <form action="{{ route('affiliator.withdraw') }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="space-y-1.5">
                            <label for="amount" class="text-sm font-medium text-[#191919]">Amount</label>
                            <input type="number" name="amount" id="amount" step="0.01" min="1"
                                class="w-full px-3.5 py-2.5 border border-[#E5E5E5] rounded-sm text-sm focus:outline-none focus:border-[#F85606] focus:ring-1 focus:ring-[#F85606]/20 transition-colors">
                        </div>
                        <div class="space-y-1.5">
                            <label for="method" class="text-sm font-medium text-[#191919]">Withdraw Method</label>
                            <select name="method" id="method"
                                class="w-full px-3.5 py-2.5 border border-[#E5E5E5] rounded-sm text-sm focus:outline-none focus:border-[#F85606] bg-white">
                                @foreach ($payment_methods as $key => $method)
                                    <option value="{{ $key }}">{{ $method }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-1.5">
                            <label for="account_details" class="text-sm font-medium text-[#191919]">Account Details</label>
                            <textarea name="account_details" id="account_details" rows="3"
                                class="w-full px-3.5 py-2.5 border border-[#E5E5E5] rounded-sm text-sm focus:outline-none focus:border-[#F85606] focus:ring-1 focus:ring-[#F85606]/20 transition-colors resize-none"></textarea>
                        </div>
                        <button type="submit"
                            class="w-full py-2.5 bg-[#F85606] text-white text-sm font-semibold rounded-sm hover:bg-[#E04D05] transition-colors">
                            Submit Request
                        </button>
                    </form>
                </div>
            </div>

            {{-- Withdrawal History --}}
            <div class="md:col-span-2 bg-white rounded-sm border border-[#E5E5E5]">
                <div class="px-5 py-3.5 border-b border-[#E5E5E5]">
                    <h2 class="text-base font-semibold text-[#191919]">Withdrawal History</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-[#F5F5F5] text-xs font-semibold text-[#767676] uppercase tracking-wider">
                            <tr>
                                <th class="px-5 py-3">Amount</th>
                                <th class="px-5 py-3">Method</th>
                                <th class="px-5 py-3">Status</th>
                                <th class="px-5 py-3">Account Details</th>
                                <th class="px-5 py-3">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#E5E5E5]">
                            @forelse ($withdraw_histories as $history)
                                <tr class="hover:bg-[#FAFAFA] transition-colors">
                                    <td class="px-5 py-4 font-semibold text-[#191919]">{{ money($history->amount) }}</td>
                                    <td class="px-5 py-4 text-[#595959] capitalize">{{ $history->method }}</td>
                                    <td class="px-5 py-4">
                                        <span class="text-xs font-semibold px-2.5 py-1 rounded-sm bg-{{ $history->statusColor() }}-50 text-{{ $history->statusColor() }}-700">
                                            {{ $history->label() }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-[#595959] max-w-[200px] truncate">{{ $history->account_details }}</td>
                                    <td class="px-5 py-4 text-[#595959]">{{ $history->created_at->format('Y-m-d') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-5 py-12 text-center text-sm text-[#767676]">No withdrawal history found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
