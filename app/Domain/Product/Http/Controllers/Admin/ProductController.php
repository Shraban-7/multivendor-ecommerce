<?php

namespace App\Domain\Product\Http\Controllers\Admin;

use App\Domain\Product\Models\Product;
use App\Domain\Product\Repositories\Contracts\BrandRepositoryInterface;
use App\Domain\Product\Repositories\Contracts\CategoryRepositoryInterface;
use App\Domain\Product\Repositories\Contracts\ProductRepositoryInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepo,
        private readonly CategoryRepositoryInterface $categoryRepo,
        private readonly BrandRepositoryInterface $brandRepo,
    ) {}

    public function index()
    {
        $products = Product::with('seller', 'unit', 'category', 'subcategory', 'variants')->latest('id')->paginate(25);
        $categories = $this->categoryRepo->getAllWithSubcategories();
        $brands = $this->brandRepo->getAll();

        return view('admin.products.index', compact('products', 'categories', 'brands'));
    }

    public function updateStatus(Request $request, $id)
    {
        $product = $this->productRepo->findOrFail($id);
        $this->productRepo->update($product, ['status' => $request->status]);

        return back()->with('success', 'Product status updated successfully!');
    }
}
