<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use App\Models\Product;
use Illuminate\Http\Request;

class DepositController extends Controller
{
    public function deposit(Request $request){
        $payment = PaymentMethod::where('method', '!=', 'Wallet')->get();
        $amount = (integer)$request->input("amount");
        $product = Product::where('name', 'Wallet')->first();
        if(!$product){
            return back()->with('error', 'Deposit temporarily unavailable');
        }
        return view('user.deposit',compact('payment','amount','product'));
    }
}
