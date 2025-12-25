<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Str;

class OptimizeExistingImages extends Command
{
    //php artisan images:optimize images/my-shop


    protected $signature = 'images:optimize 
        {path=images/my-shop : Relative path inside storage/app/public}
        {--width=1200 : Max width for resizing}';

    protected $description = 'Optimize existing images and convert to WebP (non-WebP only)';

    public function handle()
    {
        $path = $this->argument('path');
        $maxWidth = (int) $this->option('width');

        $disk = Storage::disk('public');

        if (! $disk->exists($path)) {
            $this->error("Path not found: {$path}");
            return Command::FAILURE;
        }

        $files = $disk->allFiles($path);

        $this->info('Found ' . count($files) . ' files.');

        foreach ($files as $file) {

            // Skip webp files
            if (Str::endsWith(strtolower($file), '.webp')) {
                continue;
            }

            // Skip non-images
            if (! preg_match('/\.(jpg|jpeg|png)$/i', $file)) {
                continue;
            }

            try {
                $this->line("Optimizing: {$file}");

                $absolutePath = $disk->path($file);

                $image = Image::read($absolutePath);

                // Resize safely
                $image->scaleDown(width: $maxWidth);

                // Encode
                if (function_exists('imagewebp')) {
                    $encoded = $image->toWebp(85);
                    $newFile = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $file);
                } else {
                    $encoded = $image->toJpeg(85);
                    $newFile = preg_replace('/\.(jpg|jpeg|png)$/i', '.jpg', $file);
                }

                // Save optimized file
                $disk->put($newFile, (string) $encoded);

                // Delete old file ONLY after success
                $disk->delete($file);
            } catch (\Throwable $e) {
                $this->error("Failed: {$file}");
                $this->error($e->getMessage());
            }
        }

        $this->info('Image optimization completed.');

        return Command::SUCCESS;
    }
}
