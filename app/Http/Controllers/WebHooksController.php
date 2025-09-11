<?php

namespace App\Http\Controllers;

use App\Mail\OrderDeliveredMail;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class WebHooksController extends Controller
{
    public function OrderUpdate(Request $request)
    {
        // Decode JSON as associative array
        $data = $request->input();

        if (!$data || !isset($data['uid'])) {
            return response()->json(['status' => false, 'message' => 'Invalid data'], 400);
        }

        $status = $data['status'] ?? null;
        $message = $data['message'] ?? null;
        $uid = $data['uid'];

        $order = Order::where('order_note', $uid)->first();

        if ($order) {
            if ($status) {
                $order->status = 'delivered';
                $user = User::find($order->user_id);
                if ($user) {
                    try {
                        Mail::to($user->email)->send(new OrderDeliveredMail(
                            $user->name,
                            $order->id,
                            now(),
                            $order->total,
                            url('/thank-you/'.$order->uid),
                            $order->item->name ?? "",
                            $order->customer_data ?? "",
                        ));
                    } catch (\Exception $e) {}
                }
            } else {
                $order->status = 'Delivery Running';
            }

            if ($message !== null) {
                $order->order_note = $message;
            }

            $order->save();

            return response()->json(['status' => true, 'message' => 'Order updated']);
        } else {
            return response()->json(['status' => false, 'message' => 'Order not found'], 404);
        }
    }
}
