<?php
namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use Illuminate\Http\Request;

class SellerController extends Controller
{

    public function profile($username, Request $request)
    {
        $seller = Seller::where('username', $username)->first();

        if ($request->isMethod('GET')) {
            return view('seller.profile', compact('seller'));
        }

        $data = $request->validate([
            'name'             => 'required|string|max:255',
            'email'            => 'required|string|email|max:255|unique:sellers,email,' . $seller->id,
            'phone'            => 'required|string',
            'business_name'    => 'required|string|max:255',
            'business_email'   => 'required|string|email|max:255|unique:sellers,email,' . $seller->id,
            'business_address' => 'required|string',
            'image'            => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048',
            'business_logo'    => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048',
        ]);

        if ($seller->name !== $request->name) {
            $data['username'] = str_slug('sellers', 'username', $request->name);
        } else {
            $data['username'] = $seller->username;
        }

        $usernameForPath = $data['username'];

        if ($request->hasFile('image')) {
            if (! empty($seller->image)) {
                delete_file($seller->image);
            }
            $imagePath     = "images/{$usernameForPath}/logo";
            $data['image'] = upload_file($request->file('image'), $imagePath);
        } else {
            $data['image'] = $seller->image;
        }

        if ($request->hasFile('business_logo')) {
            if (! empty($seller->business_logo)) {
                delete_file($seller->business_logo);
            }
            $logoPath              = "images/{$usernameForPath}/logo";
            $data['business_logo'] = upload_file($request->file('business_logo'), $logoPath);
        } else {
            $data['business_logo'] = $seller->business_logo;
        }

        $seller->update($data);

        return redirect()->back()->with('success', 'Profile Updated Successfully');
    }

}
