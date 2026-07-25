<?php

namespace App\Jobs;

use App\Domain\Media\Services\MediaService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessUploadedImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public string $path,
        public ?string $disk = null,
    ) {}

    public function handle(MediaService $media): void
    {
        // Placeholder for async image post-processing / CDN purge hooks.
        if ($this->disk) {
            $media->disk($this->disk);
        }
    }
}
