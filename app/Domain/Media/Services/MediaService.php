<?php

namespace App\Domain\Media\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class MediaService
{
    public function __construct(
        protected string $disk = 'public',
        protected int $quality = 85,
    ) {
        $this->disk = config('filesystems.default', 'public') === 's3' ? 's3' : 'public';
    }

    public function disk(?string $disk = null): self
    {
        if ($disk) {
            $this->disk = $disk;
        }

        return $this;
    }

    public function storeImage(UploadedFile $file, string $path, ?int $width = 1200, ?int $height = null): string
    {
        $filename = Str::uuid().'.webp';
        $fullPath = trim($path, '/').'/'.$filename;

        $image = Image::read($file);
        if ($width) {
            $image->scaleDown(width: $width, height: $height);
        }

        $encoded = $image->toWebp(quality: $this->quality);
        Storage::disk($this->disk)->put($fullPath, (string) $encoded);

        return $fullPath;
    }

    public function delete(string $path): bool
    {
        if ($path === '' || ! Storage::disk($this->disk)->exists($path)) {
            return false;
        }

        return Storage::disk($this->disk)->delete($path);
    }

    public function url(string $path): string
    {
        return Storage::disk($this->disk)->url($path);
    }
}
