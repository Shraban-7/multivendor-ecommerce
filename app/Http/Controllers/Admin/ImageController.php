<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;


class ImageController extends Controller
{
    public function index()
    {
        $disk = 'public';
        $directory = 'images/watermarked';

        $files = Storage::disk($disk)->files($directory);

        return view('admin.image', [
            'watermarkedImages' => $files
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'watermark'  => 'nullable|image|max:4096',
            'images'     => 'required|array',
            'images.*'   => 'image|mimes:jpeg,png,jpg,gif,webp|max:4096',

        ]);

        $watermarkPath = upload_file($request->file('watermark'), 'images/watermarks');
        $directory = 'images/watermarked';
        $disk = 'public';

        $watermarkImage = null;
        if ($watermarkPath) {
            $watermarkImage = Image::read(Storage::disk($disk)->get($watermarkPath));
        }

        foreach ($request->file('images') as $image) {

            $tempPath = upload_file($image, 'images/temp');

            $fileName = basename($tempPath);
            $finalPath = $directory . '/' . $fileName;
            $fullFinalPath = Storage::disk($disk)->path($finalPath);

            if (!Storage::disk($disk)->exists($directory)) {
                Storage::disk($disk)->makeDirectory($directory);
            }

            if ($watermarkImage) {
                Image::read(Storage::disk($disk)->get($tempPath))
                    ->place(
                        element: $watermarkImage,
                        position: 'bottom-right',
                        offset_x: 10,
                        offset_y: 10,
                        opacity: 70
                    )
                    ->save($fullFinalPath);

                delete_file($tempPath);
            }
        }

        return redirect()->back()->with('success', 'Images uploaded with watermark successfully');
    }

    public function deleteAll()
    {
        $directory = 'images/watermarked';
        $disk = 'public';

        $files = Storage::disk($disk)->files($directory);
        Storage::disk($disk)->delete($files);

        return redirect()->back()->with('success', 'Deleted all watermarked images.');
    }
}
