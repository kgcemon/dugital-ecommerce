<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;

class FreeFireLikeController extends Controller
{
    public function index(){
        return view('user.ff-like-sender');
    }
}
