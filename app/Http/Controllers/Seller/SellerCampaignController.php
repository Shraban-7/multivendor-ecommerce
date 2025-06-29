<?php
namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\SellerCampaign;
use App\Models\SellerCampaignProduct;
use Illuminate\Http\Request;

class SellerCampaignController extends Controller
{
    public function index()
    {
        $seller    = seller();
        $campaigns = SellerCampaign::where('seller_id', $seller->id)->get();

        return view('seller.campaigns.index', compact('campaigns'));
    }

    public function create()
    {
        return view('seller.campaigns.create');
    }

    public function store(Request $request)
    {
        $seller = seller();

        $data = $request->validate([
            'title'       => 'required|string',
            'start_date'  => 'required|date|before_or_equal:end_date',
            'end_date'    => 'required|date|after_or_equal:start_date',
            'image'       => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
            'description' => 'required|string',
            'is_active'   => 'nullable|boolean',
        ]);

        $data['seller_id'] = $seller->id;

        $data['slug'] = str_slug('seller_campaigns', 'slug', $data['title']);

        $data['image'] = upload_file($request->file('image'), 'images/seller/campaign');

        SellerCampaign::create($data);

        return redirect()->route('seller.campaigns.index')->with('success', 'Campaign create successfully');
    }

    public function show(SellerCampaign $campaign)
    {
        $seller = seller();

        $campaign_product_ids = SellerCampaignProduct::where('seller_campaign_id', $campaign->id)->pluck('id');

        $campaign_products = Product::whereIn('id', $campaign_product_ids)->get();

        $products = Product::where('seller_id', $seller->id)->get();

        return view('seller.campaigns.show', compact('campaign', 'products', 'campaign_products'));
    }

    public function add_products(SellerCampaign $campaign, Request $request)
    {
        $request->validate([
            'product_ids'   => 'nullable|array',
            'product_ids.*' => 'exists:products,id',
        ]);

        if ($request->filled('product_ids')) {
            $campaign->products()->sync($request->product_ids);
        }

        return redirect()->back()->with('success','Products added this campaign successfully');
    }
}
