<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function reviewByProduct($slug)
    {
        $product = Product::where('slug', $slug)->first();
        $reviews = Review::where('product_id', $product->id)->orderBy('created_at', 'desc')->paginate(20);
        return view('user.review', compact('reviews'));
    }

    public function store($slug){

        return view('user.add-review');
    }

}
