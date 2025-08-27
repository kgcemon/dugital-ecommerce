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
            // Cache key generate
            $cacheKey = 'product_'.$slug;

            // Permanent cache try, update er somoy forget kora hobe
            $product = Cache::rememberForever($cacheKey, function() use ($slug) {
                return Product::with('items')->where('slug', $slug)->first();
            });


            $payment = PaymentMethod::all();

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
            'payment_method' => 'required',
            'transaction_id' => 'required',
        ]);

        $user = auth('sanctum')->user();
        $product = Product::find($validated['product_id']);
        $items = Item::where('id', $validated['item_id'])->first();
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
            $order->payment_method = 1;
            $order->transaction_id = $validated['transaction_id'];
            $order->number = $request->input('payment_number');
            $order->save();

            return view('user.thank-you', compact('order'));

        }else{
            return back()->with('error', 'Item not found');
        }

    }
}
