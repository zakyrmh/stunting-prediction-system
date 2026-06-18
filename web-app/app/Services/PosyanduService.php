<?php

namespace App\Services;

use App\Models\Posyandu;
use App\Models\Prediction;
use App\Models\Intervention;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PosyanduService
{
    /**
     * Get list of posyandus with aggregated metrics.
     */
    public function getPosyandusList(): array
    {
        $posyandus = Posyandu::all();

        return $posyandus->map(function ($p) {
            $totalChildren = $p->children()->count();
            $totalKader = $p->users()->where('role', 'kader')->count();

            // Calculate stunting cases
            $childrenIds = $p->children()->pluck('id');
            $stuntedCount = Prediction::whereIn('id', function ($query) use ($childrenIds) {
                $query->selectRaw('max(id)')
                    ->from('predictions')
                    ->whereIn('child_id', $childrenIds)
                    ->groupBy('child_id');
            })->whereIn('result', ['stunted', 'severely_stunted'])->count();

            $stuntingRate = $totalChildren > 0 
                ? round(($stuntedCount / $totalChildren) * 100, 1) 
                : 0;

            return [
                'id' => $p->id,
                'name' => $p->name,
                'address' => $p->address,
                'village' => $p->village,
                'district' => $p->district,
                'city' => $p->city,
                'total_kader' => $totalKader,
                'total_children' => $totalChildren,
                'stunting_rate' => $stuntingRate,
                'stunted_cases' => $stuntedCount,
            ];
        })->toArray();
    }

    /**
     * Get details analysis for a single posyandu.
     */
    public function getPosyanduDetails(int $id): ?array
    {
        $p = Posyandu::find($id);
        if (!$p) {
            return null;
        }

        $totalChildren = $p->children()->count();
        $totalKader = $p->users()->where('role', 'kader')->count();

        // Stunting count & rate
        $childrenIds = $p->children()->pluck('id');
        $stuntedCount = Prediction::whereIn('id', function ($query) use ($childrenIds) {
            $query->selectRaw('max(id)')
                ->from('predictions')
                ->whereIn('child_id', $childrenIds)
                ->groupBy('child_id');
        })->whereIn('result', ['stunted', 'severely_stunted'])->count();

        $stuntingRate = $totalChildren > 0 
            ? round(($stuntedCount / $totalChildren) * 100, 1) 
            : 0;

        // Average AI confidence score
        $avgConfidence = $p->predictions()->avg('confidence') * 100;
        $avgConfidence = $avgConfidence > 0 ? round($avgConfidence, 1) : 0;

        // Count of scheduled/ongoing/completed sessions
        $sessionsCount = DB::table('posyandu_sessions')->where('posyandu_id', $p->id)->count();

        // Last session date
        $lastPrediction = $p->predictions()->latest('examined_at')->first();
        $lastSession = $lastPrediction ? $lastPrediction->examined_at->format('d/m/Y') : '-';

        // Get list of active kaders
        $kaderList = $p->users()->where('role', 'kader')->pluck('name')->toArray();
        if (empty($kaderList)) {
            $kaderList = ['Belum ada kader terdaftar'];
        }

        // Get latest intervention recommendation as area action plan
        $latestIntervention = Intervention::whereHas('prediction', function ($q) use ($p) {
            $q->where('posyandu_id', $p->id);
        })->latest()->first();

        $topRecommendations = $latestIntervention 
            ? $latestIntervention->recommendation 
            : 'Belum ada rencana intervensi khusus untuk wilayah ini. Lakukan pemantauan bulanan secara berkala.';

        return [
            'id' => $p->id,
            'name' => $p->name,
            'address' => $p->address,
            'village' => $p->village,
            'district' => $p->district,
            'city' => $p->city,
            'total_kader' => $totalKader,
            'total_children' => $totalChildren,
            'stunting_rate' => $stuntingRate,
            'stunted_cases' => $stuntedCount,
            'avg_confidence' => $avgConfidence,
            'sessions_count' => $sessionsCount,
            'last_session' => $lastSession,
            'top_recommendations' => $topRecommendations,
            'kader_list' => $kaderList,
        ];
    }

    /**
     * Get all structured index data package.
     */
    public function getIndexData(array $filters): array
    {
        $posyandus = $this->getPosyandusList();
        
        $selectedPosyanduId = $filters['selectedPosyanduId'] ?? null;
        if (empty($selectedPosyanduId) && !empty($posyandus)) {
            $selectedPosyanduId = $posyandus[0]['id'];
        }

        $selectedPosyandu = $selectedPosyanduId 
            ? $this->getPosyanduDetails($selectedPosyanduId) 
            : null;

        return [
            'posyandus' => $posyandus,
            'selectedPosyandu' => $selectedPosyandu,
            'selectedPosyanduId' => $selectedPosyanduId,
        ];
    }
}
