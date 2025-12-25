<?php

namespace App\Services;

use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ImageOptimizerService
{
    /**
     * Optimize and upload an image.
     *
     * @param UploadedFile $file The uploaded file.
     * @param string $path The storage path (e.g., 'products').
     * @param int|null $width Max width (optional).
     * @param int|null $height Max height (optional).
     * @return string The stored file path.
     */
    public function uploadAndOptimize(UploadedFile $file, string $path, ?int $width = 1200, ?int $height = null): string
    {
        $filename = Str::uuid() . '.webp';
        $fullPath = $path . '/' . $filename;

        $image = Image::read($file);

        // Resize the image strictly if width is provided, maintaining aspect ratio
        // This prevents vendors from uploading 6000px wide raw photos
        if ($width) {
            $image->scaleDown(width: $width, height: $height);
        }

        // Encode to WebP with 80-90% quality
        // WebP offers superior compression (30% smaller than JPEG) with comparable quality
        $encoded = $image->toWebp(quality: 85);
        
        Storage::disk('public')->put($fullPath, (string) $encoded);

        return $fullPath;
    }
}
