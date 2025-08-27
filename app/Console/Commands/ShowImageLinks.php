<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ShowImageLinks extends Command
{
    protected $signature = 'images:links';
    protected $description = 'Show all image links from storage/app/public';

    public function handle()
    {
        // storage/app/public er shob file
        $files = Storage::files('public');

        if (empty($files)) {
            $this->info("No images found in storage/app/public");
            return;
        }

        foreach ($files as $file) {
            $url = asset(str_replace('public', 'storage', $file));
            $this->line($url);
        }
    }
}
