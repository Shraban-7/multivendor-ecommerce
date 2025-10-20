<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\Seller;
use App\Models\Product;
use App\Models\Division;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SellerController extends Controller
{
    public function index()
    {
        $sellers = Seller::latest('id')->paginate(30);

        return view('admin.sellers.index', compact('sellers'));
    }

    public function profile(Seller $seller)
    {
        $data = [];
        $data['total_products'] = Product::where('seller_id', $seller->id)->count();
        $data['total_orders'] = Order::where('seller_id', $seller->id)->count();
        $data['pending_orders'] = Order::pending()->where('seller_id', $seller->id)->count();
        $data['shipped_orders'] = Order::shipped()->where('seller_id', $seller->id)->count();
        $data['cancelled_orders'] = Order::cancelled()->where('seller_id', $seller->id)->count();
        $data['delivered_orders'] = Order::delivered()->where('seller_id', $seller->id)->count();
        $data['total_revenue'] = Order::delivered()->where('seller_id', $seller->id)->sum('total');
        $data['total_customers'] = Order::where('seller_id', $seller->id)->distinct('user_id')->count('user_id');
        $data['total_commission'] = Order::where('seller_id', $seller->id)->sum('total_commission');
        $data['products'] = Product::where('seller_id', $seller->id)->paginate(102);
        $data['seller'] = $seller;
        return view('admin.sellers.profile', $data);
    }

    public function create()
    {
        $divisions = Division::all();
        return view('admin.sellers.create', compact('divisions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:sellers,email',
            'phone' => 'required|string|max:200',
            'nid_no' => 'nullable|string|max:50',
            'password' => 'required|min:5|confirmed',
            'business_name' => 'required|string|max:255',
            'business_email' => 'nullable|email|unique:sellers,business_email',
            'business_address' => 'required|string|max:1000',
            'division_id' => 'required|exists:divisions,id',
            'district_id' => 'required|exists:districts,id',
            'trade_license_no' => 'nullable|string|max:100',
            'image' => 'nullable|image|max:4096',
            'nid_front_image' => 'nullable|image|max:4096',
            'nid_back_image' => 'nullable|image|max:4096',
            'business_logo' => 'nullable|image|max:4096',
            'trade_license_image' => 'nullable|image|max:4096',
            'shop_image' => 'nullable|image|max:4096',
        ]);

        $data = $request->except(['password', 'image', 'nid_front_image', 'nid_back_image', 'business_logo', 'trade_license_image', 'shop_image']);
        $data['password'] = Hash::make($request->password);

        $username = str_slug('sellers', 'username', $request->name);
        $data['username'] = $username;
        $destinationDir = "images/{$username}";
        Storage::disk('public')->makeDirectory($destinationDir);

        $fields = [
            'image',
            'nid_front_image',
            'nid_back_image',
            'business_logo',
            'trade_license_image',
            'shop_image',
        ];

        foreach ($fields as $field) {
            if ($request->hasFile($field)) {
                $data[$field] = upload_file($request->file($field), $destinationDir);
            } else {
                $data[$field] = null;
            }
        }

        Seller::create($data);

        return successResponse('Seller created successfully.');
    }

    public function edit($username)
    {
        $seller = Seller::where('username',$username)->first();
        return view('admin.sellers.edit');
    }

    public function best_seller(Seller $seller, Request $request)
    {
        $seller->is_best_seller = $request->is_best_seller;
        $seller->save();

        return redirect()->back()->with('success', 'Best seller updated successfully');
    }

    public function toggleBlock(Seller $seller, Request $request)
    {
        $status = $request->input('status') == Seller::BLOCKED ? Seller::BLOCKED : Seller::ACTIVE;

        $seller->status = $status;
        $seller->save();

        $message = $status == Seller::ACTIVE ? 'Seller activated successfully' : 'Seller blocked successfully';

        return redirect()->back()->with('success', $message);
    }

    public function delete(Seller $seller)
    {
        $seller->status = Seller::DELETED;
        $seller->save();

        return redirect()->back()->with('success', 'Seller deleted successfully');
    }


    public function update(Seller $seller, Request $request)
    {
        $data = $request->validate([
            'commission_type' => 'required|string|in:flat,percentage',
            'commission_amount' => 'required|numeric',
            'status' => 'required'
        ]);

        $seller->update($data);

        return redirect()->back()->with('success', 'Seller update successfully');
    }
}
