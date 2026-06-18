<?php

namespace App\Services;

use App\Models\Prediction;
use App\Models\Intervention;
use App\Models\User;
use App\Models\Posyandu;

class StuntingPredictionService
{
    /**
     * Get prediction list with children and posyandu details and filtering.
     */
    public function getPredictionsList(
        ?string $search = '',
        ?string $filterPosyandu = '',
        ?string $filterStatus = '',
        ?string $startDate = '',
        ?string $endDate = '',
        ?int $userId = null
    ) {
        $query = Prediction::with(['child.user', 'posyandu', 'recorder']);

        if ($userId) {
            $user = User::find($userId);
            if ($user && $user->isKader()) {
                $query->where('posyandu_id', $user->posyandu_id);
            } elseif ($user && $user->isOrangTua()) {
                $query->whereHas('child', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            }
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('child', function ($qc) use ($search) {
                    $qc->where('name', 'like', "%{$search}%")
                       ->orWhere('nik', 'like', "%{$search}%");
                });
            });
        }

        if ($filterPosyandu) {
            $query->whereHas('posyandu', function ($q) use ($filterPosyandu) {
                $q->where('name', $filterPosyandu);
            });
        }

        if ($filterStatus) {
            $query->where('result', $filterStatus);
        }

        if ($startDate) {
            $query->whereDate('examined_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('examined_at', '<=', $endDate);
        }

        $predictions = $query->orderBy('examined_at', 'desc')->get();

        return $predictions->map(function ($pred) {
            return [
                'id' => $pred->id,
                'name' => $pred->child->name ?? '-',
                'nik' => $pred->child->nik ?? '-',
                'posyandu' => $pred->posyandu->name ?? '-',
                'village' => $pred->posyandu->village ?? '-',
                'examined_at' => $pred->examined_at ? $pred->examined_at->format('d/m/Y') : '-',
                'age_months' => $pred->age_months,
                'weight' => floatval($pred->weight),
                'height' => floatval($pred->height),
                'result' => $pred->result,
                'confidence' => floatval($pred->confidence),
                'status' => $this->getValidationStatus($pred->id),
                'recorder' => $pred->recorder->name ?? '-',
            ];
        });
    }

    /**
     * Get statistics summary of prediction metrics.
     */
    public function getPredictionStats(?int $userId = null): array
    {
        $query = Prediction::query();

        if ($userId) {
            $user = User::find($userId);
            if ($user && $user->isKader()) {
                $query->where('posyandu_id', $user->posyandu_id);
            } elseif ($user && $user->isOrangTua()) {
                $query->whereHas('child', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            }
        }

        $totalCount = (clone $query)->count();
        $avgConfidence = $totalCount > 0 ? (clone $query)->avg('confidence') * 100 : 0;

        // Pending verifications count
        $pendingCountQuery = Intervention::where('status', 'pending');
        if ($userId) {
            $user = User::find($userId);
            if ($user && $user->isKader()) {
                $pendingCountQuery->whereHas('prediction', function ($q) use ($user) {
                    $q->where('posyandu_id', $user->posyandu_id);
                });
            } elseif ($user && $user->isOrangTua()) {
                $pendingCountQuery->whereHas('prediction.child', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            }
        }
        $pendingCount = $pendingCountQuery->count();
        $verifiedPercent = $totalCount > 0 ? round((($totalCount - $pendingCount) / $totalCount) * 100, 1) : 100;

        // Stunting cases count and rate
        $stuntedCount = (clone $query)->whereIn('result', ['stunted', 'severely_stunted'])->count();
        $stuntingRate = $totalCount > 0 ? round(($stuntedCount / $totalCount) * 100, 1) : 0;

        return [
            'totalCount' => $totalCount,
            'avgConfidence' => round($avgConfidence, 1),
            'pendingCount' => $pendingCount,
            'verifiedPercent' => $verifiedPercent,
            'stuntedCount' => $stuntedCount,
            'stuntingRate' => $stuntingRate,
        ];
    }

    /**
     * Helper to get validation status.
     */
    protected function getValidationStatus(int $predictionId): string
    {
        $intervention = Intervention::where('prediction_id', $predictionId)->first();
        if ($intervention) {
            return $intervention->status === 'pending' ? 'pending' : 'verified';
        }
        return 'verified';
    }

    /**
     * Get all data required for the predictions index view.
     */
    public function getIndexData(array $filters, User $user): array
    {
        $userId = $user->isBidan() ? null : $user->id;

        $search = $filters['search'] ?? '';
        $filterPosyandu = $filters['filterPosyandu'] ?? '';
        $filterStatus = $filters['filterStatus'] ?? '';
        $startDate = $filters['startDate'] ?? '';
        $endDate = $filters['endDate'] ?? '';

        $logs = $this->getPredictionsList(
            $search,
            $filterPosyandu,
            $filterStatus,
            $startDate,
            $endDate,
            $userId
        );

        $stats = $this->getPredictionStats($userId);
        $posyandus = Posyandu::all();

        return [
            'logs' => $logs,
            'stats' => $stats,
            'posyandus' => $posyandus,
            'search' => $search,
            'filterPosyandu' => $filterPosyandu,
            'filterStatus' => $filterStatus,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ];
    }
}
