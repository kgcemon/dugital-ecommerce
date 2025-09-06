<?php

namespace App\Providers;
use App\Models\Product;
use Spatie\Export\Exporter;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */

    public function boot(Exporter $exporter)
    {
        // আমরা crawl বন্ধ করে explicit path দেবো
        $exporter->crawl(false);

        // home
        $paths = ['/'];

        // products slug নিন (adjust column name: slug বা id মত)
        $slugs = Product::query()->pluck('slug')->toArray();

        foreach ($slugs as $slug) {
            $paths[] = "/products/{$slug}"; // adjust route prefix আপনার রাউট অনুযায়ী
        }

        $exporter->paths($paths);
    }

}
