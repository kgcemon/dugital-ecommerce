<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentSms;
use Illuminate\Http\Request;

class PaymentSMSController extends Controller
{
    public function index(){
        $data = PaymentSMS::orderBy('id', 'desc')->paginate(10);
        return view('admin.paymentSms.sms', compact('data'));
    }


    public function search(Request $request)
    {
        $keywords = $request->get('search');
        $data = PaymentSms::where('trxID', 'like', '%' . $keywords . '%')->orderBy('id', 'desc')->paginate(10);
        return view('admin.paymentSms.sms', compact('data'));
    }


    public function SmsWhooks(Request $request)
    {
        $allowedSenders = ["bKash", "NAGAD", "Nagad", "16216"];
        $bkashNumbers   = ["bKash", "16247"];
        $nagadNumbers   = ["NAGAD", "16167"];
        $rocketNumbers  = ["Rocket", "16216"];

        $sendResponse = function ($status, $message, $code = 200) {
            return response()->json([
                'status'    => $status,
                'message'   => $message,
                'timestamp' => now()->format('Y-m-d H:i:s')
            ], $code);
        };

        $sender       = $request->input('sender');
        $sms          = $request->input('sms');

        if (!$sender || !$sms) {
            return $sendResponse(false, 'Missing required fields', 400);
        }

        if (!in_array($sender, $allowedSenders)) {
            return $sendResponse(true, 'SMS received (not from allowed sender)', 200);
        }

        $txn_id = '';
        $amount = '';
        $number = '';
        $is_valid_sms = false;

        // bKash Type 1
        if ((in_array($sender, $bkashNumbers) || strcasecmp($sender, "bKash") === 0)
            && str_contains($sms, "You have received")
            && preg_match('/You have received Tk ([\d,]+(?:\.\d{2})?) from (\d+).* TrxID (\w+)/', $sms, $matches)) {
            $amount = str_replace(',', '', $matches[1]);
            $number = $matches[2];
            $txn_id = $matches[3];
            $is_valid_sms = true;
        }
        // bKash Type 2
        elseif ((in_array($sender, $bkashNumbers) || strcasecmp($sender, "bKash") === 0)
            && str_contains($sms, "Cash In")
            && preg_match('/Cash In Tk ([\d,]+(?:\.\d{2})?) from (\d+).* TrxID (\w+)/', $sms, $matches)) {
            $amount = str_replace(',', '', $matches[1]);
            $number = $matches[2];
            $txn_id = $matches[3];
            $is_valid_sms = true;
        }
        // Nagad
        elseif ((in_array($sender, $nagadNumbers) || strcasecmp($sender, "NAGAD") === 0)
            && (preg_match('/Amount: Tk ([\d,]+(?:\.\d{2})?).*Sender: (\d+).*TxnID: (\w+).*Balance: Tk ([\d,]+\.\d{2})/s', $sms, $matches)
                || preg_match('/Amount: Tk ([\d,]+(?:\.\d{2})?).*Uddokta: (\d+).*TxnID: (\w+).*Balance: ([\d,]+\.\d{2})/s', $sms, $matches))) {
            $amount = str_replace(',', '', $matches[1]);
            $number = $matches[2];
            $txn_id = $matches[3];
            $is_valid_sms = true;
        }
        // Rocket
        elseif ((in_array($sender, $rocketNumbers) || strcasecmp($sender, "Rocket") === 0)
            && str_contains($sms, "received from")
            && preg_match('/Tk([\d,]+(?:\.\d{2})?) received from A\/C:\**\*(\d+).* TxnId:(\d+)/', $sms, $matches)) {
            $amount = str_replace(',', '', $matches[1]);
            $number = $matches[2];
            $txn_id = $matches[3];
            $is_valid_sms = true;
        }

        if (!$is_valid_sms) {
            return $sendResponse(true, 'SMS received but not a valid payment notification', 200);
        }

        // Duplicate check
        $exists = PaymentSms::where('trxID', $txn_id)->exists();
        if ($exists) {
            return $sendResponse(true, 'Transaction ID already exists', 200);
        }

        try {
            PaymentSms::create([
                'sender'       => $sender,
                'number'       => $number,
                'trxID'        => $txn_id,
                'amount'       => $amount,
                'status'       => 0,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        } catch (\Exception $e) {
            \Log::error("PaymentSms Insert Error: " . $e->getMessage());
            return $sendResponse(false, $e->getMessage());
        }

        return $sendResponse(true, 'Payment SMS processed and stored successfully', 200);
    }

}
