<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{

    public function show()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $totalOrders       = Order::where('user_id', $user->id)->count();
        $completedOrders   = Order::where('user_id', $user->id)->where('status', 'delivered')->count();
        $pendingOrders     = Order::where('user_id', $user->id)->where('status', 'hold')->count();;
        $refIncome         = 0; // in ৳
        $totalReferrals    = 0;

        // Recent transactions (dummy)
        $recentTransactions = Order::orderBy('id', 'desc')->take(5)->get();

        return view('user.profile', compact(
            'totalOrders',
            'completedOrders',
            'pendingOrders',
            'refIncome',
            'totalReferrals',
            'recentTransactions'
        ));
    }



    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
