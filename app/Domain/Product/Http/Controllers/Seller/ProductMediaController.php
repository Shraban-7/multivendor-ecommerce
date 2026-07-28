<?php

namespace App\Domain\Product\Http\Controllers\Seller;

use App\Domain\Product\Models\Product;
use App\Domain\Product\Models\ProductImage;
use App\Domain\Product\Repositories\Contracts\ProductRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Services\ImageOptimizerService;
use Illuminate\Http\Request;

class ProductMediaController extends Controller
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepo,
    ) {}

    public function index(Product $product)
    {
        abort_unless($product->seller_id === get_seller_id(), 403);
        $product->load('images');

        return view('seller.products.media.index', compact('product'));
    }

    public function upload(Request $request, Product $product)
    {
        abort_unless($product->seller_id === get_seller_id(), 403);

        $request->validate([
            'images' => 'required|array',
            'images.*' => 'required|image|mimes:jpeg,jpg,png,webp|max:4096',
        ]);

        $imageService = new ImageOptimizerService;
        $imageFolder = "{$product->seller->username}/products";
        $uploaded = [];

        $maxPosition = $product->images()->max('position') ?? 0;

        foreach ($request->file('images') as $file) {
            $path = $imageService->uploadAndOptimize($file, $imageFolder);
            $maxPosition++;

            $image = $product->images()->create([
                'image' => $path,
                'type' => 'gallery',
                'position' => $maxPosition,
                'is_primary' => ! $product->images()->exists(),
            ]);

            $uploaded[] = $image;
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => count($uploaded) . ' image(s) uploaded',
                'images' => collect($uploaded)->map(fn($img) => [
                    'id' => $img->id,
                    'url' => $img->image_url,
                    'position' => $img->position,
                    'is_primary' => $img->is_primary,
                ]),
            ]);
        }

        return redirect()->back()->with('success', count($uploaded) . ' image(s) uploaded successfully');
    }

    public function destroy(Product $product, ProductImage $image)
    {
        abort_unless($product->seller_id === get_seller_id(), 403);
        abort_if($image->product_id !== $product->id, 404);

        delete_file($image->image);
        $wasPrimary = $image->is_primary;
        $image->delete();

        if ($wasPrimary) {
            $firstRemaining = $product->images()->ordered()->first();
            if ($firstRemaining) {
                $firstRemaining->update(['is_primary' => true]);
            }
        }

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Image deleted']);
        }

        return redirect()->back()->with('success', 'Image deleted successfully');
    }

    public function reorder(Request $request, Product $product)
    {
        abort_unless($product->seller_id === get_seller_id(), 403);

        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:product_images,id',
        ]);

        foreach ($request->order as $index => $imageId) {
            ProductImage::where('id', $imageId)
                ->where('product_id', $product->id)
                ->update(['position' => $index + 1]);
        }

        return response()->json(['success' => true, 'message' => 'Order updated']);
    }

    public function setPrimary(Product $product, ProductImage $image)
    {
        abort_unless($product->seller_id === get_seller_id(), 403);
        abort_if($image->product_id !== $product->id, 404);

        $product->images()->update(['is_primary' => false]);
        $image->update(['is_primary' => true]);

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Primary image updated']);
        }

        return redirect()->back()->with('success', 'Primary image updated');
    }

    public function replace(Request $request, Product $product, ProductImage $image)
    {
        abort_unless($product->seller_id === get_seller_id(), 403);
        abort_if($image->product_id !== $product->id, 404);

        $request->validate([
            'image' => 'required|image|mimes:jpeg,jpg,png,webp|max:4096',
        ]);

        $imageService = new ImageOptimizerService;
        $imageFolder = "{$product->seller->username}/products";

        delete_file($image->image);

        $path = $imageService->uploadAndOptimize($request->file('image'), $imageFolder);
        $image->update(['image' => $path]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Image replaced',
                'image' => ['id' => $image->id, 'url' => $image->image_url],
            ]);
        }

        return redirect()->back()->with('success', 'Image replaced successfully');
    }
}
