<?php

namespace App\Http\Controllers;

use App\Mail\OfferMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OfferController extends Controller
{
    public function index()
    {
        return view('admin.offer-send');
    }

    public function send(Request $request)
    {
        $request->validate([
            'discount' => 'required|numeric',
            'coupon' => 'required|string',
            'expiryDate' => 'required|date',
        ]);

        $users = User::select('id', 'name', 'email')->get();
        $success = 0;
        $failed = 0;

        foreach ($users as $user) {
            try {
                Mail::to($user->email)->send(new OfferMail(
                    $user->name,
                    $request->discount,
                    $request->coupon,
                    $request->expiryDate,
                    url('/offers')
                ));
                $success++;
            } catch (\Exception $e) {
                $failed++;
            }
        }

        return response()->json([
            'total' => count($users),
            'success' => $success,
            'failed' => $failed,
        ]);
    }
}
