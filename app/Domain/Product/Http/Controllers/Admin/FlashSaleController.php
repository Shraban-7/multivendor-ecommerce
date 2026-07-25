<?php

namespace App\Domain\Product\Http\Controllers\Admin;

use App\Domain\Product\Models\FlashSale;
use App\Domain\Product\Models\FlashSaleProduct;
use App\Domain\Product\Repositories\Contracts\FlashSaleRepositoryInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FlashSaleController extends Controller
{
    public function __construct(
        private readonly FlashSaleRepositoryInterface $flashSaleRepo,
    ) {}

    public function index()
    {
        $flashSales = $this->flashSaleRepo->getPaginated();

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

        $this->flashSaleRepo->store($data);

        return redirect()->route('admin.flash-sales.index')
            ->with('success', 'Flash Sale created successfully.');
    }

    public function edit($id)
    {
        $sale = $this->flashSaleRepo->findOrFail($id);

        return view('admin.flash_sales.edit', compact('sale'));
    }

    public function update(Request $request, $id)
    {
        $sale = $this->flashSaleRepo->findOrFail($id);

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

        $this->flashSaleRepo->update($sale, $data);

        return redirect()->route('admin.flash-sales.index')
            ->with('success', 'Flash Sale updated successfully.');
    }

    public function show($id)
    {
        $sale = FlashSale::with('products.seller', 'products')->findOrFail($id);

        return view('admin.flash_sales.show', compact('sale'));
    }

    public function productReview(Request $request, $id, $productId)
    {
        $data = $request->validate([
            'status' => 'required|integer',
        ]);

        $flashSale = FlashSaleProduct::where('id', $id)->where('product_id', $productId)->first();
        $flashSale->update($data);

        return successResponse('Product Status Update Successfully');
    }

    public function destroy($id)
    {
        $sale = $this->flashSaleRepo->findOrFail($id);

        if ($sale->image != null) {
            delete_file($sale->image);
        }

        $this->flashSaleRepo->delete($sale);

        return redirect()->route('admin.flash-sales.index')
            ->with('success', 'Flash Sale deleted successfully.');
    }
}
