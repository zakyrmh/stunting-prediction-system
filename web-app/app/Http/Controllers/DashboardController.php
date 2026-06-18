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
        $kaderData = [];
        $parentData = [];

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
        } elseif ($user->isKader()) {
            $posyanduId = $user->posyandu_id;

            // 1. Balita Ditimbang Hari Ini (pemeriksaan di posyandu kader hari ini)
            $weighedToday = Prediction::where('posyandu_id', $posyanduId)
                ->whereDate('examined_at', today())
                ->count();

            // 2. Belum Hadir Bulan Ini (balita terdaftar di posyandu kader yang belum diukur bulan ini)
            $notWeighedThisMonth = Children::where('posyandu_id', $posyanduId)
                ->whereDoesntHave('predictions', function ($q) {
                    $q->whereMonth('examined_at', now()->month)
                      ->whereYear('examined_at', now()->year);
                })->count();

            // 3. Peringatan Dini: Gagal Tumbuh (2T) di posyandu kader bulan ini
            $growthFalteringCount = 0;
            $childrenWithHistory = Children::where('posyandu_id', $posyanduId)
                ->with(['predictions' => function ($q) {
                    $q->orderBy('examined_at', 'desc');
                }])->get();

            foreach ($childrenWithHistory as $child) {
                $preds = $child->predictions->take(3);
                if ($preds->count() >= 3) {
                    $w1 = floatval($preds[0]->weight);
                    $w2 = floatval($preds[1]->weight);
                    $w3 = floatval($preds[2]->weight);
                    if ($w1 <= $w2 && $w2 <= $w3) {
                        $growthFalteringCount++;
                    }
                }
            }

            // 4. Tabel Aktivitas Kunjungan Terakhir (10 entri pengukuran hari ini di posyandu kader)
            $todayEntries = Prediction::with('child')
                ->where('posyandu_id', $posyanduId)
                ->whereDate('examined_at', today())
                ->latest()
                ->take(10)
                ->get();

            $kaderData = [
                'weighedToday' => $weighedToday,
                'notWeighedThisMonth' => $notWeighedThisMonth,
                'growthFalteringCount' => $growthFalteringCount,
                'todayEntriesCount' => $todayEntries->count(),
                'todayEntries' => $todayEntries,
            ];
        } elseif ($user->isOrangTua()) {
            $children = Children::where('user_id', $user->id)->get();
            $selectedChild = null;
            $latestPrediction = null;
            $latestIntervention = null;
            $growthPoints = collect();
            $pathD = '';

            if ($children->isNotEmpty()) {
                $selectedChildId = $request->query('child_id', $children->first()->id);
                $selectedChild = Children::with(['predictions' => function ($q) {
                    $q->orderBy('examined_at', 'asc');
                }])->find($selectedChildId);
            }

            if ($selectedChild) {
                $latestPrediction = $selectedChild->predictions->last(); // Order is asc, so last is newest
                if ($latestPrediction) {
                    $latestIntervention = Intervention::where('prediction_id', $latestPrediction->id)->first();
                }

                $growthHistory = $selectedChild->predictions->take(-5); // Last 5 measurements in asc order
                $count = $growthHistory->count();

                foreach ($growthHistory as $index => $pred) {
                    $h = floatval($pred->height);
                    $age = $pred->age_months;

                    // Calculate X coordinate
                    if ($count > 1) {
                        $x = 40 + $index * (340 / ($count - 1));
                    } else {
                        $x = 210; // Center if only 1 point
                    }

                    // Calculate Y coordinate based on non-linear scale:
                    // 70 cm -> 200
                    // 75 cm -> 160
                    // 78 cm -> 110
                    // 85 cm -> 25
                    if ($h >= 78) {
                        $y = 110 - (($h - 78) / 7) * 85;
                    } elseif ($h >= 75) {
                        $y = 160 - (($h - 75) / 3) * 50;
                    } else {
                        $y = 200 - (($h - 70) / 5) * 40;
                    }

                    // Clamp Y to graph bounds
                    $y = max(20, min(200, $y));

                    $growthPoints->push([
                        'x' => round($x, 1),
                        'y' => round($y, 1),
                        'age' => $age,
                        'height' => $h,
                        'weight' => floatval($pred->weight),
                        'result' => $pred->result,
                    ]);
                }

                // Build path string
                foreach ($growthPoints as $index => $point) {
                    if ($index === 0) {
                        $pathD .= "M {$point['x']} {$point['y']}";
                    } else {
                        $pathD .= " L {$point['x']} {$point['y']}";
                    }
                }
            }

            $parentData = [
                'children' => $children,
                'selectedChild' => $selectedChild,
                'latestPrediction' => $latestPrediction,
                'latestIntervention' => $latestIntervention,
                'growthPoints' => $growthPoints,
                'pathD' => $pathD,
            ];
        }

        return view('dashboard', compact('bidanData', 'kaderData', 'parentData'));
    }
}
