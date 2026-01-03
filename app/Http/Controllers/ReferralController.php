<?php

namespace App\Http\Controllers;

use App\Models\Referral;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReferralController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $referrals = Referral::where('referrer_id', $user->id)
            ->with('referred')
            ->latest()
            ->paginate(20);

        $stats = [
            'total_referrals' => $referrals->total(),
            'completed_referrals' => Referral::where('referrer_id', $user->id)
                ->where('status', 'completed')
                ->count(),
            'pending_referrals' => Referral::where('referrer_id', $user->id)
                ->where('status', 'pending')
                ->count(),
            'total_rewards' => Referral::where('referrer_id', $user->id)
                ->where('status', 'completed')
                ->sum('reward_amount'),
        ];

        return view('referrals.index', compact('referrals', 'stats'));
    }

    public function generateCode()
    {
        $user = auth()->user();
        
        if (!$user->referral_code) {
            $user->update([
                'referral_code' => strtoupper(Str::random(8))
            ]);
        }

        return back()->with('success', 'Referral code generated: ' . $user->referral_code);
    }

    public function applyCode(Request $request)
    {
        $validated = $request->validate([
            'referral_code' => 'required|string|exists:users,referral_code',
        ]);

        $user = auth()->user();
        $referrer = User::where('referral_code', $validated['referral_code'])->first();

        if ($referrer->id === $user->id) {
            return back()->with('error', 'You cannot use your own referral code.');
        }

        if (Referral::where('referred_id', $user->id)->exists()) {
            return back()->with('error', 'You have already used a referral code.');
        }

        // Create referral record
        $referral = Referral::create([
            'referrer_id' => $referrer->id,
            'referred_id' => $user->id,
            'referral_code' => $validated['referral_code'],
            'status' => 'pending',
        ]);

        // Award initial reward to referrer (if configured)
        // This could be a signup bonus, discount, etc.

        return back()->with('success', 'Referral code applied successfully!');
    }

    public function checkCompletion()
    {
        // This would typically be called via a scheduled job
        // Check for pending referrals where the referred user has made a purchase
        $pendingReferrals = Referral::where('status', 'pending')
            ->with('referred')
            ->get();

        foreach ($pendingReferrals as $referral) {
            // Check if referred user has completed a purchase
            $hasPurchase = Order::where('user_id', $referral->referred_id)
                ->where('status', 'completed')
                ->exists();

            if ($hasPurchase) {
                $this->completeReferral($referral);
            }
        }
    }

    private function completeReferral(Referral $referral)
    {
        // Calculate reward (could be percentage of first purchase, fixed amount, etc.)
        $firstOrder = Order::where('user_id', $referral->referred_id)
            ->where('status', 'completed')
            ->first();

        $rewardAmount = $firstOrder ? ($firstOrder->total_price * 0.10) : 0; // 10% of first purchase

        $referral->update([
            'status' => 'completed',
            'reward_amount' => $rewardAmount,
            'completed_at' => now(),
        ]);

        // Credit reward to referrer's wallet or account
        $referrer = $referral->referrer;
        if ($referrer->wallet) {
            $referrer->wallet->credit($rewardAmount);
        }

        // TODO: Send notification to referrer
    }
}

