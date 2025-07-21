<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\Storage;

class ImageUploadController extends Controller
{
    public function showForm()
    {
        return view('upload');
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
            //->crop(width: 2500, height: 2500, position: 'center')
            //->scale(width: 500, height: 500)
            ->place(
                element: $watermark,
                position: 'bottom-right',
                offset_x: 10, // 10px from the right
                offset_y: 10, // 10px from the bottom
                opacity: 70 // 70%
            )
            ->save(Storage::path("public/{$newImagePath}"));

        delete_file($imagePath);

        return view('upload');
    }
}
