<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class DepositController extends Controller
{
    public function deposit(Request $request){
        $payment = PaymentMethod::where('method', '!=', 'Wallet')->get();
        $amount = $request->input("amount");
        return view('user.deposit',compact('payment','amount'));
    }
}
