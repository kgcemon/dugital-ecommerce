<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::query();

        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(function($q) use ($term){
                $q->where('id', $term)
                    ->orWhere('name','LIKE',"%$term%")
                    ->orWhere('phone','LIKE',"%$term%")
                    ->orWhere('transaction_id','LIKE',"%$term%");
            });
        }

        $orders = $query->orderByDesc('id')->paginate(10)->appends($request->all());
        return view('admin.orders.index', compact('orders'));
    }

    // ✅ Bulk action only
    public function bulkAction(Request $request)
    {
        $action = $request->input('action');
        $orderIds = $request->input('order_ids', []);

        if (!$action || empty($orderIds)) {
            return redirect()->back()->with('error','Please select at least one order and an action.');
        }

        switch ($action) {
            case 'delete':
                Order::whereIn('id', $orderIds)->delete();
                return redirect()->back()->with('success','Selected orders deleted successfully.');
            case 'processing':
            case 'delivered':
            case 'cancelled':
                Order::whereIn('id', $orderIds)->update(['status'=>$action]);
                return redirect()->back()->with('success','Selected orders updated to '.ucfirst($action));
            default:
                return redirect()->back()->with('error','Invalid action selected.');
        }
    }

    // ✅ Single status update only
    public function updateSingle(Request $request)
    {
        $orderId = $request->input('order_id');
        $status = strtolower($request->input('status'));

        if(!$orderId || !$status){
            return redirect()->back()->with('error','Invalid order or status.');
        }

        $order = Order::find($orderId);
        if(!$order){
            return redirect()->back()->with('error','Order not found.');
        }

        $order->status = $status;
        $order->save();

        return redirect()->back()->with('success','Order status updated to '.ucfirst($status));
    }
}
