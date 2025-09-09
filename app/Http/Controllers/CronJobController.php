<?php

namespace App\Http\Controllers;

use App\Models\Code;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class CronJobController extends Controller
{
    public function freeFireAutoTopUpJob()
    {
        $orders = Order::where('status', 'processing')->whereNull('order_note')->whereHas('product', function ($query) {$query->where('is_auto', 1);})->limit(4)->get();

        try {
            foreach ($orders as $order) {
                DB::beginTransaction();

                $order = Order::lockForUpdate()->find($order->id);

                if ($order->status !== 'processing' || $order->order_note !== null) {
                    DB::rollBack();
                    continue;
                }

                $code = Code::where('item_id', $order->item_id)
                    ->where('status', 'unused')
                    ->lockForUpdate()
                    ->first();

                if (!$code) {
                    DB::rollBack();
                    continue;
                }

                $type = (Str::startsWith($code->code, 'UPBD')) ? 1 : ((Str::startsWith($code->code, 'BDMB')) ? 2 : 1);
                $denom = (string) $order->item->denom;

                if (empty($denom)) {
                    DB::rollBack();
                    continue;
                }

                $denoms = explode(',', $denom);

                foreach ($denoms as $d) {
                    $response = Http::withHeaders([
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                        'RA-SECRET-KEY' => 'kpDvM4m9AOTl0+4Gcnvm7a+VgLJFjSNvuDVC9Jl6wH/RxXJqqCb0RQ==',
                    ])->post('https://autonow.codmshopbd.com/topup', [
                        "playerId"   => $order->customer_data,
                        "denom"      => $d,
                        "type"       => $type,
                        "voucherCode"=> $code->code,
                        "webhook"    => "https://codmshop.com/api/auto-webhooks"
                    ]);

                        $data = $response->json();

                        // SUCCESS হলে order update করব
                        $order->status = 'Delivery Running';
                        $order->order_note = $data['uid'] ?? 'running';
                        $order->save();

                        $code->status = 'used';
                        $code->order_id = $order->id;
                        $code->save();
                }

                DB::commit();
            }

            return 'Cron job run successfully';
        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }
    }
}
