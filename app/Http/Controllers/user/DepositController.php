<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class DepositController extends Controller
{
    public function deposit(Request $request){
        $amount = $request->input('amount');
        return route('addMoney/'.$amount);
    }

    public function addMoney($amount){
        $payment = PaymentMethod::where('method', '!=', 'Wallet')->get();
        return view('user.deposit', compact('amount', 'payment'));
    }
}
