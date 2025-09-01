<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

class SiteProductsScreenController extends Controller
{
    public function index($slug) {
        try {

            $cacheKey = 'product_'.$slug;

            $product = Cache::rememberForever($cacheKey, function() use ($slug) {
                return Product::with('items')->where('slug', $slug)->first();
            });

            if (auth()->check()) {
                $payment = PaymentMethod::all();
            } else {
                $payment = PaymentMethod::where('method', '!=', 'Wallet')->get();
            }

            if ($product) {
                return view('user.product', compact('product', 'payment'));
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Product not found',
                ]);
            }

        } catch (\Exception $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage(),
            ]);
        }
    }

    public function addOrder(Request $request)
    {

        $validated = $request->validate([
            'product_id' => 'required',
            'item_id' => 'required',
            'customer_data' => 'required',
            'payment_id' => 'required',
            'transaction_id' => 'sometimes|unique:orders',
        ]);

        $user = auth()->user();
        $product = Product::find($validated['product_id']);
        $items = Item::where('id', $validated['item_id'])->first();
        $paymentMethod = PaymentMethod::where('id', $validated['payment_id'])->first();
        if ($items && $product ) {
            $order = (object) new Order();
            $checkDuplicate = $order->where('transaction_id', $validated['transaction_id'])->count();
            if ($checkDuplicate > 0) {
                return response()->json([
                    'status' => false,
                    'message' => 'transaction_id already used',
                ]);
            }
            $order->quantity = 1;
            $order->total = $items->price;
            if ($user){
                $order->name = $user->name;
                $order->email = $user->email;
                $order->phone = $user->phone;
            }else{
                $order->user_id = null;
                $order->name    = "guest";
            }

            $order->product_id = $validated['product_id'];
            $order->item_id   = $validated['item_id'];
            $order->customer_data = $validated['customer_data'];
            if ($paymentMethod->method == 'Wallet') {
                if ($user->wallet < $product->price) {
                    return response()->json([
                        'status' => false,
                        'message' => "আপনার ওয়ালেটে যথেস্ট টাকা নেই দয়া করে টাকা এড করে আবার চেস্টা করুন",
                    ]);
                }
                $user->wallet -= $product->price;
                $user->save();
            }else{
                $order->transaction_id = $validated['transaction_id'];
                $order->number = "01855555444";
            }
            $order->payment_method = 1;
            $order->save();

            return response()->json([
                'status' => true,
                'message' => 'Order created successfully',
                'order' => $order,
            ]);

        }else{
            return back()->with('error', 'Item not found');
        }

    }

    public function thankYouPage()
    {
        return view('user.thank-you');
    }
}
