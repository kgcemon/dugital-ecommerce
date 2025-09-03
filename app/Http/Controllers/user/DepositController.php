<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Order;
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
            return view('user.deposit', compact('amount', 'payment'))->with('error', 'Deposit temporarily unavailable');
        }
        return view('user.deposit',compact('payment','amount','product'));
    }

    public function depositStore(Request $request){
        $validate = $request->validate([
            'amount' => 'required',
            'payment_id' => 'required',
            'transaction_id' => 'required',
            'payment_number' => 'sometimes|nullable|numeric',
        ]);
        try {
            $user = $request->user();
            $product = Product::where('name', 'Wallet')->first();
            $amount = (integer)$request->input("amount");
            if(!$product){
                return back()->with('error', 'Deposit temporarily unavailable');
            }
            Order::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'quantity' => 1,
                'total' => $amount,
                'customer_data' => "Deposit $amount",
                'payment_method' => $request->input("payment_method"),
                'transaction_id' => $request->input("transaction_id"),
                'number' => '010000000',
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Deposit successful',
            ]);
        }catch (\Exception $exception){
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage(),
            ]);
        }
    }
}
