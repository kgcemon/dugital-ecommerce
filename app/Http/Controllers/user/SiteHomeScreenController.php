<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use App\Models\SliderImages;
use Illuminate\Support\Facades\Cache;

class SiteHomeScreenController extends Controller
{

    public function index() {

        $products = Cache::remember('home_products', 60 * 24, function () {
            return Categorie::with('products')
                ->select('name', 'id')
                ->orderBy('sort')
                ->paginate(50);
        });

        $images = Cache::remember('home_slider_images', 60 * 24, function () {
            return SliderImages::first();
        });

        return view('user.home', compact('products','images'));
    }

}
