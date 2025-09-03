<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class DepositController extends Controller
{
    public function deposit(Request $request){
        $payment = PaymentMethod::where('method', '!=', 'Wallet')->get();
        return route('deposit',$payment);
    }
}
