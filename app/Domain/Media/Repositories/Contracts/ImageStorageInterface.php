<?php

namespace App\Domain\Media\Repositories\Contracts;

use Illuminate\Http\UploadedFile;

interface ImageStorageInterface
{
    public function storeImage(UploadedFile $file, string $path, ?int $width = 1200, ?int $height = null): string;

    public function delete(string $path): bool;

    public function url(string $path): string;
}
