<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use App\Models\User;
use App\Models\XpTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GamificationController extends Controller
{
    public function leaderboard()
    {
        $leaderboard = User::where('role', 'student')
            ->orderByDesc('xp_points')
            ->orderByDesc('level')
            ->withCount('badges')
            ->limit(100)
            ->get();

        $currentUser = auth()->user();
        $userRank = null;
        if ($currentUser && $currentUser->isStudent()) {
            $userRank = User::where('role', 'student')
                ->where(function($q) use ($currentUser) {
                    $q->where('xp_points', '>', $currentUser->xp_points)
                      ->orWhere(function($q2) use ($currentUser) {
                          $q2->where('xp_points', $currentUser->xp_points)
                             ->where('level', '>', $currentUser->level);
                      });
                })
                ->count() + 1;
        }

        return view('gamification.leaderboard', compact('leaderboard', 'userRank'));
    }

    public function badges()
    {
        $badges = Badge::where('is_active', true)
            ->withCount('users')
            ->orderBy('required_xp')
            ->get();

        $userBadges = auth()->user()->badges ?? collect();

        return view('gamification.badges', compact('badges', 'userBadges'));
    }

    public function myProgress()
    {
        $user = auth()->user();
        
        $stats = [
            'xp_points' => $user->xp_points ?? 0,
            'level' => $user->level ?? 1,
            'badges_count' => $user->badges()->count(),
            'next_level_xp' => $this->getXpForLevel(($user->level ?? 1) + 1),
            'current_level_xp' => $this->getXpForLevel($user->level ?? 1),
        ];

        $xpProgress = $stats['next_level_xp'] > 0 
            ? (($user->xp_points - $stats['current_level_xp']) / ($stats['next_level_xp'] - $stats['current_level_xp'])) * 100 
            : 0;

        $recentTransactions = $user->xpTransactions()
            ->latest()
            ->limit(20)
            ->get();

        $badges = $user->badges()->latest('user_badges.earned_at')->get();

        // XP breakdown by source
        $xpBySource = XpTransaction::where('user_id', $user->id)
            ->where('type', 'earned')
            ->select('source_type', DB::raw('SUM(points) as total'))
            ->groupBy('source_type')
            ->get();

        return view('gamification.my-progress', compact('stats', 'xpProgress', 'recentTransactions', 'badges', 'xpBySource'));
    }

    private function getXpForLevel($level)
    {
        // Formula: XP required = 100 * level * (level + 1) / 2
        return 100 * $level * ($level + 1) / 2;
    }

    public function awardXp($user, $points, $source = null, $description = null)
    {
        $transaction = XpTransaction::create([
            'user_id' => $user->id,
            'points' => $points,
            'type' => 'earned',
            'source_type' => $source ? get_class($source) : null,
            'source_id' => $source ? $source->id : null,
            'description' => $description,
        ]);

        $user->increment('xp_points', $points);

        // Check level up
        $this->checkLevelUp($user);

        // Check badge eligibility
        $this->checkBadgeEligibility($user, $source);

        return $transaction;
    }

    private function checkLevelUp($user)
    {
        $currentLevel = $user->level ?? 1;
        $nextLevelXp = $this->getXpForLevel($currentLevel + 1);

        if ($user->xp_points >= $nextLevelXp) {
            $user->increment('level');
            // TODO: Send notification for level up
        }
    }

    private function checkBadgeEligibility($user, $source = null)
    {
        $badges = Badge::where('is_active', true)
            ->where('required_xp', '<=', $user->xp_points)
            ->whereDoesntHave('users', function($q) use ($user) {
                $q->where('users.id', $user->id);
            })
            ->get();

        foreach ($badges as $badge) {
            if ($this->meetsBadgeCriteria($user, $badge, $source)) {
                $user->badges()->attach($badge->id, [
                    'course_id' => $source && method_exists($source, 'course_id') ? $source->course_id : null,
                    'earned_at' => now(),
                ]);
                // TODO: Send notification for badge earned
            }
        }
    }

    private function meetsBadgeCriteria($user, $badge, $source = null)
    {
        if ($badge->required_xp && $user->xp_points < $badge->required_xp) {
            return false;
        }

        if ($badge->criteria) {
            $criteria = $badge->criteria;
            
            // Check course completion criteria
            if (isset($criteria['courses_completed'])) {
                $completed = $user->courses()->wherePivotNotNull('completed_at')->count();
                if ($completed < $criteria['courses_completed']) {
                    return false;
                }
            }

            // Check quiz attempts criteria
            if (isset($criteria['quizzes_passed'])) {
                $passed = $user->attempts()->where('status', 'completed')->count();
                if ($passed < $criteria['quizzes_passed']) {
                    return false;
                }
            }

            // Check specific course completion
            if (isset($criteria['course_id']) && $source) {
                if (!method_exists($source, 'course_id') || $source->course_id != $criteria['course_id']) {
                    return false;
                }
            }
        }

        return true;
    }
}

