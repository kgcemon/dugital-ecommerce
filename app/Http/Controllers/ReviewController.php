<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function reviewByProduct($id)
    {
        $review = Review::where('product_id', $id)->orderBy('creation_date', 'desc')->paginate(20);
        return $review;
    }
}
