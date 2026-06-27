<?php

namespace App\Jobs;

use App\Support\WebpImageOptimizer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateAvifImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;
    public int $timeout = 120;

    public function __construct(public string $webpRelativePath)
    {
    }

    public function handle(WebpImageOptimizer $optimizer): void
    {
        $source = public_path($this->webpRelativePath);

        if (! is_file($source)) {
            return;
        }

        $optimizer->createAvif($source);
    }
}
