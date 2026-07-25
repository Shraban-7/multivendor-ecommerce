<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class ImageUploadController extends Controller
{
    public function index()
    {
        return view('admin.crop-save');
    }

    public function save(Request $request)
    {
        $request->validate([
            'croppedImage' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $imagePath = null;

        if ($request->hasFile('croppedImage')) {
            $imagePath = upload_file($request->file('croppedImage'), 'images/temp');

            $watermark = Storage::disk('public')->get('images/watermark.png');

            $newImagePath = str_replace('temp', 'products/new', $imagePath);

            Image::read(Storage::disk('public')->get($imagePath))
                ->place(
                    element: $watermark,
                    position: 'bottom-right',
                    offset_x: 10,
                    offset_y: 10,
                    opacity: 70
                )
                ->save(Storage::path("public/{$newImagePath}"));

            delete_file($imagePath);

            return response()->json([
                'success' => true,
                'path' => asset('storage/'.$imagePath),
            ]);
        }

        return response()->json(['success' => false, 'message' => 'No file uploaded'], 400);
    }

    public function upload(Request $request)
    {
        $validated = $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = upload_file($request->file('image'), 'images/temp');
        }

        $watermark = Storage::disk('public')->get('images/watermark.png');

        $newImagePath = str_replace('temp', 'products/new', $imagePath);

        Image::read(Storage::disk('public')->get($imagePath))
            // ->crop(width: 2500, height: 2500, position: 'center')
            // ->scale(width: 500, height: 500)
            ->place(
                element: $watermark,
                position: 'bottom-right',
                offset_x: 10,
                offset_y: 10,
                opacity: 70
            )
            ->save(Storage::path("public/{$newImagePath}"));

        delete_file($imagePath);

        return view('upload');
    }
}
