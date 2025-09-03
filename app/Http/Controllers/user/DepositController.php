<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\PaymentSms;
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

    public function depositStore(Request $request)
    {
        $validate = $request->validate([
            'amount'         => 'required|numeric|min:1',
            'payment_id'     => 'required|integer',
            'transaction_id' => 'required|string|min:5',
            'payment_number' => 'nullable|numeric',
        ]);


        try {
            $user = $request->user();

            $status = 'hold';

            // Duplicate trxID চেক (only if provided)
            if (!empty($validate['transaction_id'])) {
                $checkDuplicate = Order::where('transaction_id', $validate['transaction_id'])->count();
                if ($checkDuplicate > 0) {
                    return response()->json([
                        'status'  => false,
                        'message' => 'This transaction ID is already used.',
                    ], 409);
                }
            }

            $paySMS = null;
            if (!empty($validate['transaction_id'])) {
                $paySMS = PaymentSms::where('trxID', $validate['transaction_id'])
                    ->where('amount', '>=', $request->input('amount'))
                    ->first();
            }

            if ($paySMS) {
                $status         = 'delivered';
                $user->wallet += $validate['amount'];
                $user->save();
            } else {
                if (empty($validate['transaction_id']) || empty($validate['payment_number'])) {
                    return response()->json([
                        'status'  => false,
                        'message' => 'Transaction ID and payment number are required for this payment method.',
                    ], 422);
                }
            }

            $product = Product::where('name', 'Wallet')->first();
            if (!$product) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Deposit temporarily unavailable',
                ]);
            }

            $amount = (int) $request->input("amount");

            Order::create([
                'user_id'        => $user->id,
                'name'           => $user->name,
                'email'          => $user->email,
                'product_id'     => $product->id,
                'quantity'       => 1,
                'total'          => $amount,
                'customer_data'  => "Deposit $amount",
                'payment_method' => $request->input("payment_id"),
                'transaction_id' => $request->input("transaction_id"),
                'number'         => $request->input("payment_number") ?? 'N/A',
                'status'         => $status,
            ]);

            return response()->json([
                'status'  => true,
                'message' => 'Deposit successful',
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'status'  => false,
                'message' => $exception->getMessage(),
            ]);
        }
    }

}
