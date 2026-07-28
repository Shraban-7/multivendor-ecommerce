<?php

namespace App\Domain\Vendor\Http\Controllers\Seller;

use App\Domain\Vendor\Models\Seller;
use App\Domain\Vendor\Services\DashboardService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        private readonly DashboardService $dashboardService,
    ) {}

    public function dashboard(Request $request)
    {
        $seller = Seller::find(get_seller_id());

        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());

        $data = $this->dashboardService->dashboard($seller->id, $startDate, $endDate);

        return view('seller.dashboard', array_merge(
            $data['overview'],
            [
                'chartData' => $data['chartData'],
                'top_selling_products' => $data['topProducts'],
                'latest_orders' => $data['latestOrders'],
                'lowStockProducts' => $data['lowStockProducts'],
                'pendingPayout' => $data['pendingPayout'],
                'orderStatusDistribution' => $data['orderStatusDistribution'],
                'seller' => $seller,
            ]
        ));
    }
}
