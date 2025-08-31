<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Categorie;

class SiteHomeScreenController extends Controller
{

    public function index() {
        $products = Categorie::with('products')
            ->select('name', 'id')
            ->orderBy('sort')
            ->paginate(50);
        return view('user.home', compact('products'));
    }



}
