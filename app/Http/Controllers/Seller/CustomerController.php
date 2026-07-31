<?php

namespace App\Http\Controllers\Seller;

use App\Domain\Auth\Models\User;
use App\Domain\Order\Models\Order;
use App\Domain\Vendor\Models\Seller;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $seller = Seller::find(get_seller_id());

        $filters = [
            'search'    => trim((string) $request->query('search', '')),
            'verified'  => $request->query('verified'),
            'sort'      => $request->query('sort'),
            'direction' => $request->query('direction'),
        ];

        // Website customers: users who placed at least one order with this seller
        $orderedUserIds = Order::where('seller_id', $seller->id)->pluck('user_id')->unique();

        $query = User::with('country')
            ->whereIn('id', $orderedUserIds);

        if ($filters['search'] !== '') {
            $term = '%'.$filters['search'].'%';
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', $term)
                    ->orWhere('email', 'like', $term)
                    ->orWhere('phone', 'like', $term)
                    ->orWhere('username', 'like', $term);
            });
        }

        if ($filters['verified'] === 'yes') {
            $query->whereNotNull('email_verified_at');
        } elseif ($filters['verified'] === 'no') {
            $query->whereNull('email_verified_at');
        }

        $sort = $filters['sort'] ?? 'latest';
        $direction = $filters['direction'] ?? 'desc';

        match (true) {
            $sort === 'name'        => $query->orderBy('name', $direction === 'asc' ? 'asc' : 'desc'),
            $sort === 'email'       => $query->orderBy('email', $direction === 'asc' ? 'asc' : 'desc'),
            $sort === 'orders'      => $query->orderBy('orders_count', $direction === 'asc' ? 'asc' : 'desc'),
            default                 => $query->latest('id'),
        };

        $perPage = (int) $request->query('per_page', 25);

        // orders count subquery used by Orders column + KPI counts
        $query->withCount([
            'orders as orders_count' => function ($q) use ($seller) {
                $q->where('seller_id', $seller->id);
            },
        ]);

        $customers = $query->paginate($perPage)->withQueryString();

        $customers->getCollection()->transform(function ($c) {
            $c->total_spent = $c->orders_count > 0
                ? (float) Order::where('seller_id', get_seller_id())->where('user_id', $c->id)->sum('total')
                : 0;

            return $c;
        });

        // KPI counts (re-issue over same base, no pagination)
        $base = User::whereIn('id', $orderedUserIds);
        $counts = [
            'total'       => (clone $base)->count(),
            'verified'    => (clone $base)->whereNotNull('email_verified_at')->count(),
            'unverified'  => (clone $base)->whereNull('email_verified_at')->count(),
            'repeat'      => Order::where('seller_id', $seller->id)
                ->select('user_id')
                ->groupBy('user_id')
                ->havingRaw('COUNT(*) > 1')
                ->get()
                ->pluck('user_id')
                ->pipe(function ($ids) use ($base) {
                    return (clone $base)->whereIn('id', $ids)->count();
                }),
        ];

        return view('seller.customers.index', compact('customers', 'counts', 'filters'));
    }
}
