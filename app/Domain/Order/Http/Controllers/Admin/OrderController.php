<?php

namespace App\Domain\Order\Http\Controllers\Admin;

use App\Domain\Order\Repositories\Contracts\OrderRepositoryInterface;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepo,
    ) {}

    public function index()
    {
        $orders = $this->orderRepo->getAllOrders(['seller', 'billing_address', 'user', 'items']);

        return view('admin.orders.index', compact('orders'));
    }
}
