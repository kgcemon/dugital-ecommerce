<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use Illuminate\Support\Facades\Cache;

class SiteHomeScreenController extends Controller
{

    public function index() {
        $cacheKey = 'home_products';
        Cache::forget($cacheKey);
        $products = Cache::rememberForever($cacheKey, function() {
            return Categorie::with('products')
                ->select('name', 'id')
                ->orderBy('sort')
                ->paginate(50);
        });

        return view('user.home', compact('products'));
    }



}
