<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class SiteProductsScreenController extends Controller
{
    public function index($slug) {
        try {
//            // Cache key generate
//            $cacheKey = 'product_'.$slug;
//
//            // Permanent cache try, update er somoy forget kora hobe
//            $product = Cache::rememberForever($cacheKey, function() use ($slug) {
//                return Product::with('items')->where('slug', $slug)->first();
//            });

            $product = Product::with('items')->where('slug', $slug)->first();

            if ($product) {
                return view('user.product', compact('product'));
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Product not found',
                ]);
            }

        } catch (\Exception $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage(),
            ]);
        }
    }
}
