<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function index()
    {
        $disk = 'public';

        return apiResponse([
            'watermarked' => Storage::disk($disk)->files('images/watermarked'),
            'cropped' => Storage::disk($disk)->files('images/cropped'),
        ]);
    }

    public function store(Request $request)
    {
        $validator = validateRequest($request, [
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'watermark' => 'nullable|image|max:4096',
        ]);

        if ($validator->fails()) {
            return sendValidationError($validator->errors());
        }

        $uploaded = [];
        foreach ($request->file('images') as $image) {
            $path = upload_file($image, 'images/watermarked');
            $uploaded[] = $path;
        }

        return apiResponse([
            'images' => $uploaded,
        ], 'Images uploaded successfully.');
    }

    public function deleteAll()
    {
        $disk = 'public';
        Storage::disk($disk)->delete(Storage::disk($disk)->files('images/watermarked'));

        return successResponse('All watermarked images deleted.');
    }

    public function croppedImage(Request $request)
    {
        $validator = validateRequest($request, [
            'image' => 'required|mimes:jpeg,png,jpg,gif,webp',
        ]);

        if ($validator->fails()) {
            return sendValidationError($validator->errors());
        }

        $path = upload_file($request->file('image'), 'images/cropped');

        return apiResponse(['path' => $path], 'Cropped image saved successfully.');
    }

    public function deleteCroppedImage()
    {
        $disk = 'public';
        Storage::disk($disk)->delete(Storage::disk($disk)->files('images/cropped'));

        return successResponse('All cropped images deleted.');
    }
}
