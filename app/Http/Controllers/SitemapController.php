<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    public function index()
    {
        $urls = [
            [
                'loc' => url('/'),
                'lastmod' => now()->toAtomString(),
                'changefreq' => 'daily',
                'priority' => '1.0'
            ],
            [
                'loc' => url('/about'),
                'lastmod' => now()->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.8'
            ],
        ];

        // child sitemap link
        $sitemaps = [
            url('/sitemap-products.xml'),
        ];

        $content = view('sitemap-index', compact('urls', 'sitemaps'));

        return response($content, 200)
            ->header('Content-Type', 'application/xml');
    }


    public function products()
    {
        // 2 দিনের জন্য cache করা
        $urls = Cache::remember('sitemap_products', now()->addDays(2), function () {
            return Product::where('name', '!=', 'Wallet')
                ->get()
                ->map(function ($product) {
                    return [
                        'loc' => url('/product/' . $product->slug),
                        'lastmod' => $product->updated_at->toAtomString(),
                        'changefreq' => 'weekly',
                        'priority' => '0.8'
                    ];
                });
        });

        $content = view('sitemap', ['urls' => $urls]);

        return response($content, 200)
            ->header('Content-Type', 'application/xml');
    }
}
