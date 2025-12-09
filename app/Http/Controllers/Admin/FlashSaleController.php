<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FlashSale;
use Illuminate\Support\Facades\File;

class FlashSaleController extends Controller
{
    public function index()
    {
        $flashSales = FlashSale::latest()->paginate(20);

        return view('admin.flash_sales.index', compact('flashSales'));
    }

    public function create()
    {
        return view('admin.flash_sales.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp,avif',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'is_active' => 'nullable',
        ]);

        $data = $request->except('image', 'is_active');
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = upload_file($request->file('image'), 'flash_sale');
        }

        $data['image'] = $imagePath;

        FlashSale::create($data);

        return redirect()->route('admin.flash-sales.index')
            ->with('success', 'Flash Sale created successfully.');
    }

    public function edit($id)
    {
        $sale = FlashSale::findOrFail($id);
        return view('admin.flash_sales.edit', compact('sale'));
    }

    public function update(Request $request, $id)
    {
        $sale = FlashSale::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        $data = $request->except('image', 'is_active');
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        if ($request->hasFile('image')) {
            if ($sale->image != null) {
                delete_file($sale->image);
            }

            $data['image'] = upload_file($request->file('image'), 'flash_sale');
        }

        $sale->update($data);

        return redirect()->route('admin.flash-sales.index')
            ->with('success', 'Flash Sale updated successfully.');
    }

    public function show($id)
    {
        $sale = FlashSale::with('flashSaleProducts.vendor', 'flashSaleProducts.product')->findOrFail($id);
        return view('admin.flash_sales.show', compact('sale'));
    }

    public function destroy($id)
    {
        $sale = FlashSale::findOrFail($id);

        if ($sale->image != null) {
            delete_file($sale->image);
        }

        $sale->delete();

        return redirect()->route('admin.flash-sales.index')
            ->with('success', 'Flash Sale deleted successfully.');
    }
}
