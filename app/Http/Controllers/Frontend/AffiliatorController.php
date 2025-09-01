<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\AffiliateClick;
use App\Http\Controllers\Controller;
use App\Models\AffiliateCommission;
use App\Models\AffiliatePayout;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AffiliatorController extends Controller
{
    public function dashboard(Request $request)
    {
        $affiliate_id = Auth::id();

        $filter = $request->get('filter', 'year');
        switch ($filter) {
            case 'week':
                $startDate = now()->startOfWeek();
                $endDate = now()->endOfWeek();
                $months = collect(range(0, 6))
                    ->map(fn($i) => now()->startOfWeek()->addDays($i)->format('D'))
                    ->values();
                $groupByFormat = 'D';
                break;

            case 'month':
                $startDate = now()->startOfMonth();
                $endDate = now()->endOfMonth();
                $daysInMonth = now()->daysInMonth;
                $months = collect(range(1, $daysInMonth))
                    ->map(fn($d) => str_pad($d, 2, '0', STR_PAD_LEFT))
                    ->values();
                $groupByFormat = 'd';
                break;

            case '3months':
                $startDate = now()->subMonths(2)->startOfMonth();
                $endDate = now()->endOfMonth();
                $months = collect(range(0, 2))
                    ->map(fn($i) => now()->subMonths($i)->format('M'))
                    ->reverse()
                    ->values();
                $groupByFormat = 'M';
                break;

            case '6months':
                $startDate = now()->subMonths(5)->startOfMonth();
                $endDate = now()->endOfMonth();
                $months = collect(range(0, 5))
                    ->map(fn($i) => now()->subMonths($i)->format('M'))
                    ->reverse()
                    ->values();
                $groupByFormat = 'M';
                break;

            default: 
                $startDate = now()->subMonths(11)->startOfMonth();
                $endDate = now()->endOfMonth();
                $months = collect(range(0, 11))
                    ->map(fn($i) => now()->subMonths($i)->format('M'))
                    ->reverse()
                    ->values();
                $groupByFormat = 'M';
                break;
        }

        $clicks = AffiliateClick::where('affiliate_id', $affiliate_id)->count();
        $earnings = AffiliateCommission::where('affiliate_id', $affiliate_id)->where('status', AffiliateCommission::APPROVED)->sum('commission_amount');
        $total_orders = Order::where('affiliate_id', $affiliate_id)->count();
        $pending_earnings = AffiliateCommission::where('affiliate_id', $affiliate_id)
            ->where('status', AffiliateCommission::APPROVED)
            ->sum('commission_amount');

        $clicks_data = AffiliateClick::where('affiliate_id', $affiliate_id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->groupBy(fn($c) => $c->created_at->format($groupByFormat))
            ->map->count();

        $earnings_data = AffiliateCommission::where('affiliate_id', $affiliate_id)
            ->where('status', AffiliateCommission::APPROVED)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->groupBy(fn($e) => $e->created_at->format($groupByFormat))
            ->map->sum('commission_amount');

        $pending_data = AffiliateCommission::where('affiliate_id', $affiliate_id)
            ->where('status', AffiliateCommission::APPROVED)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->groupBy(fn($p) => $p->created_at->format($groupByFormat))
            ->map->sum('commission_amount');

        $orders_data = Order::where('affiliate_id', $affiliate_id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->groupBy(fn($o) => $o->created_at->format($groupByFormat))
            ->map->count();

        $clicks_data = $months->map(fn($m) => $clicks_data[$m] ?? 0)->values();
        $earnings_data = $months->map(fn($m) => $earnings_data[$m] ?? 0)->values();
        $pending_data = $months->map(fn($m) => $pending_data[$m] ?? 0)->values();
        $orders_data = $months->map(fn($m) => $orders_data[$m] ?? 0)->values();

        return view('frontend.affiliator.dashboard', compact(
            'clicks',
            'earnings',
            'total_orders',
            'pending_earnings',
            'months',
            'clicks_data',
            'earnings_data',
            'pending_data',
            'orders_data',
            'filter'
        ));
    }



    public function withdraw(Request $request)
    {
        $affiliate = Auth::user();

        if ($request->isMethod('GET')) {
            $payment_methods = AffiliatePayout::payment_methods();
            $available_balance = $affiliate->balance;
            $withdraw_histories = AffiliatePayout::where('affiliate_id', $affiliate->id)->get();

            return view('frontend.affiliator.withdraw', compact('payment_methods', 'withdraw_histories', 'available_balance'));
        }

        $data = $request->validate([
            'amount' => 'required|numeric|min:1|max:' . $affiliate->balance,
            'method' => 'required',
            'account_details' => 'required'
        ]);

        if ($affiliate->balance < $data['amount']) {
            return back()->withErrors(['amount' => 'Insufficient balance']);
        }

        $data['affiliate_id'] = $affiliate->id;

        $withdraw = AffiliatePayout::create($data);

        $affiliate->balance -= $withdraw->amount;
        $affiliate->save();

        return redirect()->back()->with('success', 'Withdraw Request Sent Successfully');
    }
}
