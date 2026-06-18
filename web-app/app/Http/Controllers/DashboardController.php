<?php

namespace App\Http\Controllers;

use App\Models\Children;
use App\Models\Prediction;
use App\Models\Intervention;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $bidanData = [];

        if ($user->isBidan()) {
            $totalChildren = Children::count();
            
            $newChildrenCount = Children::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();

            // Stunted Children (result is stunted or severely_stunted in the latest prediction of each child)
            $stuntedCount = Prediction::whereIn('id', function ($query) {
                $query->selectRaw('max(id)')
                    ->from('predictions')
                    ->groupBy('child_id');
            })->whereIn('result', ['stunted', 'severely_stunted'])->count();

            $stuntedPercentage = $totalChildren > 0 ? round(($stuntedCount / $totalChildren) * 100, 1) : 0;

            // Growth Faltering (2T) Calculation:
            // Fetch children with all predictions sorted by date (limiting inside eager loading restricts total results, not per-child results)
            $growthFalteringCount = 0;
            $childrenWithHistory = Children::with(['predictions' => function ($q) {
                $q->orderBy('examined_at', 'desc');
            }])->get();

            foreach ($childrenWithHistory as $child) {
                $preds = $child->predictions->take(3);
                if ($preds->count() >= 3) {
                    $w1 = floatval($preds[0]->weight);
                    $w2 = floatval($preds[1]->weight);
                    $w3 = floatval($preds[2]->weight);
                    // Check if weight didn't rise twice (w1 <= w2 AND w2 <= w3)
                    if ($w1 <= $w2 && $w2 <= $w3) {
                        $growthFalteringCount++;
                    }
                }
            }

            $growthFalteringPercentage = $totalChildren > 0 ? round(($growthFalteringCount / $totalChildren) * 100, 1) : 0;

            // Certainty Factor Verification Queue (interventions where status is pending)
            $pendingVerificationsCount = Intervention::where('status', 'pending')->count();
            $pendingVerifications = Intervention::with(['prediction.child'])
                ->where('status', 'pending')
                ->latest()
                ->take(5)
                ->get();

            $bidanData = [
                'totalChildren' => $totalChildren,
                'newChildrenCount' => $newChildrenCount,
                'stuntedCount' => $stuntedCount,
                'stuntedPercentage' => $stuntedPercentage,
                'growthFalteringCount' => $growthFalteringCount,
                'growthFalteringPercentage' => $growthFalteringPercentage,
                'pendingVerificationsCount' => $pendingVerificationsCount,
                'pendingVerifications' => $pendingVerifications,
            ];
        }

        return view('dashboard', compact('bidanData'));
    }
}
