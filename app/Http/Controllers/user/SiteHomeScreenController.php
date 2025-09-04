<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use App\Models\SliderImages;

class SiteHomeScreenController extends Controller
{

    public function index() {
        $products = Categorie::with('products')
            ->select('name', 'id')
            ->orderBy('sort')
            ->paginate(50);
        $images = SliderImages::first();
        return view('user.home', compact('products','images'));
    }



}
