<?php

namespace App\Http\Controllers;

use App\Mail\OrderDeliveredMail;
use App\Mail\OrderRefundMail;
use App\Models\Code;
use App\Models\Order;
use App\Models\User;
use App\Models\WalletTransaction;
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
        $user = User::find($order->user_id);

        if ($order) {
            if ($status) {
                $order->status = 'delivered';
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
               if ($message == 'Invalid Player ID'){
                   if ($user) {
                       $user->wallet += $order->total;
                       $user->save();
                       $user->increment('wallet', $order->total);
                       WalletTransaction::create([
                           'user_id'   => $user->id,
                           'amount'    => $order->total,
                           'type'      => 'credit',
                           'description' => 'Refund to Wallet Order id: ' . $order->id,
                           'status'    => 1,
                       ]);
                       Code::where('order_id', $order->id)->where('denom',$order->item->denom)->update([
                           'order_id' => null,
                           'status'  => "unused",
                       ]);
                       try {
                           Mail::to($user->email)->send(new OrderRefundMail(
                               $user->name,
                               $order->id,
                               now()->format('d M Y, h:i A'),
                               $order->total,
                               url('/order/'.$order->uid)
                           ));

                       }catch (\Exception $e) {}
                   }
               }
            }

            $order->save();

            return response()->json(['status' => true, 'message' => 'Order updated']);
        } else {
            return response()->json(['status' => false, 'message' => 'Order not found'], 404);
        }
    }
}
