<?php

namespace App\Http\Controllers\Admin;

use App\Domain\Auth\Models\User;
use App\Domain\Order\Models\Order;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $filters = [
            'search'    => trim((string) $request->query('search', '')),
            'verified'  => $request->query('verified'),
            'sort'      => $request->query('sort'),
            'direction' => $request->query('direction'),
        ];

        $query = User::with('country');

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

        $query->withCount('orders as orders_count');

        $customers = $query->paginate($perPage)->withQueryString();

        $customers->getCollection()->transform(function ($c) {
            $c->total_spent = (float) Order::where('user_id', $c->id)->sum('total');

            return $c;
        });

        // KPI counts over the unfiltered base
        $base = User::query();
        $counts = [
            'total'       => (clone $base)->count(),
            'verified'    => (clone $base)->whereNotNull('email_verified_at')->count(),
            'unverified'  => (clone $base)->whereNull('email_verified_at')->count(),
            'with_orders' => Order::query()->distinct('user_id')->count('user_id'),
        ];

        return view('admin.customers.index', compact('customers', 'counts', 'filters'));
    }

    public function profile(User $customer)
    {
        $orders = Order::with('items')->where('user_id', $customer->id)->get();

        $data = [];
        $data['total_orders']     = $orders->count();
        $data['total_spent']      = $orders->sum('total');
        $data['pending_orders']   = Order::pending()->where('user_id', $customer->id)->count();
        $data['shipped_orders']   = Order::shipped()->where('user_id', $customer->id)->count();
        $data['cancelled_orders'] = Order::cancelled()->where('user_id', $customer->id)->count();
        $data['delivered_orders'] = Order::delivered()->where('user_id', $customer->id)->count();
        $data['recent_orders']    = $orders->sortByDesc('created_at')->take(8)->values();

        $data['customer'] = $customer;

        return view('admin.customers.profile', $data);
    }
}
