<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{

    public function show()
    {
        // Dummy data for profile dashboard
        $totalOrders       = 25;
        $completedOrders   = 18;
        $pendingOrders     = 7;
        $refIncome         = 1200; // in ৳
        $totalReferrals    = 5;

        // Recent transactions (dummy)
        $recentTransactions = [
            (object) ['type' => 'Order #101', 'amount' => 250],
            (object) ['type' => 'Order #102', 'amount' => 180],
            (object) ['type' => 'Order #103', 'amount' => 320],
            (object) ['type' => 'Referral Bonus', 'amount' => 100],
            (object) ['type' => 'Order #104', 'amount' => 150],
        ];

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
