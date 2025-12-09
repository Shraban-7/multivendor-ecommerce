<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FlashSale;
use Illuminate\Support\Facades\File;

class FlashSaleController extends Controller
{
    // ------------------------------------
    // SHOW ALL FLASH SALES
    // ------------------------------------
    public function index()
    {
        $flashSales = FlashSale::latest()->get();
        
        return view('admin.flash_sales.index', compact('flashSales'));
    }

    // ------------------------------------
    // CREATE PAGE
    // ------------------------------------
    public function create()
    {
        return view('admin.flash_sales.create');
    }

    // ------------------------------------
    // STORE FLASH SALE
    // ------------------------------------
    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'description' => 'nullable|string',
            'start_time'  => 'required|date',
            'end_time'    => 'required|date|after:start_time',
            'is_active'   => 'nullable',
        ]);

        $data = $request->except('image', 'is_active');
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        // Upload image
        if ($request->hasFile('image')) {
            $filename = time() . '_flashsale.' . $request->image->extension();
            $request->image->move(public_path('uploads/flash_sale'), $filename);
            $data['image'] = $filename;
        }

        FlashSale::create($data);

        return redirect()->route('admin.flash-sales.index')
            ->with('success', 'Flash Sale created successfully.');
    }

    // ------------------------------------
    // EDIT PAGE
    // ------------------------------------
    public function edit($id)
    {
        $sale = FlashSale::findOrFail($id);
        return view('admin.flash_sales.edit', compact('sale'));
    }

    // ------------------------------------
    // UPDATE FLASH SALE
    // ------------------------------------
    public function update(Request $request, $id)
    {
        $sale = FlashSale::findOrFail($id);

        $request->validate([
            'title'       => 'required|string|max:255',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'description' => 'nullable|string',
            'start_time'  => 'required|date',
            'end_time'    => 'required|date|after:start_time',
        ]);

        $data = $request->except('image', 'is_active');
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        // Replace old image if new one uploaded
        if ($request->hasFile('image')) {

            // delete old
            if ($sale->image && File::exists(public_path('uploads/flash_sale/' . $sale->image))) {
                File::delete(public_path('uploads/flash_sale/' . $sale->image));
            }

            $filename = time() . '_flashsale.' . $request->image->extension();
            $request->image->move(public_path('uploads/flash_sale'), $filename);
            $data['image'] = $filename;
        }

        $sale->update($data);

        return redirect()->route('admin.flash-sales.index')
            ->with('success', 'Flash Sale updated successfully.');
    }

    // ------------------------------------
    // SHOW FLASH SALE
    // ------------------------------------
    public function show($id)
    {
        $sale = FlashSale::with('flashSaleProducts.vendor', 'flashSaleProducts.product')->findOrFail($id);
        return view('admin.flash_sales.show', compact('sale'));
    }

    // ------------------------------------
    // DELETE FLASH SALE
    // ------------------------------------
    public function destroy($id)
    {
        $sale = FlashSale::findOrFail($id);

        if ($sale->image && File::exists(public_path('uploads/flash_sale/' . $sale->image))) {
            File::delete(public_path('uploads/flash_sale/' . $sale->image));
        }

        $sale->delete();

        return redirect()->route('admin.flash-sales.index')
            ->with('success', 'Flash Sale deleted successfully.');
    }
}
