<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function reviewByProduct($id)
    {
        $reviews = Review::where('product_id', $id)->orderBy('creation_date', 'desc')->paginate(20);
        return view('user.review', compact('reviews'));
    }
}
