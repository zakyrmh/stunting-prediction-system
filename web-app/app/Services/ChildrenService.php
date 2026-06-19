<?php

namespace App\Services;

use App\Models\Children;
use App\Models\Prediction;
use App\Models\Intervention;
use App\Models\User;
use App\Models\Posyandu;

class ChildrenService
{
    /**
     * Get children list with latest measurement details and filtering.
     */
    public function getChildrenList(?string $search = '', ?string $filterPosyandu = '', ?string $filterStatus = '', ?int $userId = null)
    {
        $query = Children::with(['posyandu', 'user', 'predictions' => function ($q) {
            $q->orderBy('examined_at', 'desc');
        }]);

        // If not bidan (e.g. kader), restrict to user's posyandu
        if ($userId) {
            $user = User::find($userId);
            if ($user && $user->isKader()) {
                $query->where('posyandu_id', $user->posyandu_id);
            } elseif ($user && $user->isOrangTua()) {
                $query->where('user_id', $user->id);
            }
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($qu) use ($search) {
                      $qu->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($filterPosyandu) {
            $query->whereHas('posyandu', function ($q) use ($filterPosyandu) {
                $q->where('name', $filterPosyandu);
            });
        }

        $children = $query->latest()->get();

        // Map latest measurements and filter by status if needed
        $childrenData = $children->map(function ($child) {
            $latestPrediction = $child->predictions->first(); // Sorted desc, so first is latest
            
            return [
                'id' => $child->id,
                'name' => $child->name,
                'nik' => $child->nik ?? '-',
                'gender' => $child->gender,
                'age_months' => $child->birth_date ? $child->birth_date->diffInMonths(now()) : 0,
                'parent_name' => $child->user->name ?? '-',
                'posyandu' => $child->posyandu->name ?? '-',
                'latest_bb' => $latestPrediction ? floatval($latestPrediction->weight) : null,
                'latest_tb' => $latestPrediction ? floatval($latestPrediction->height) : null,
                'cf_result' => $latestPrediction ? $latestPrediction->result : 'normal',
                'validation_status' => $latestPrediction ? $this->getValidationStatus($latestPrediction->id) : 'verified',
            ];
        });

        if ($filterStatus) {
            $childrenData = $childrenData->filter(function ($c) use ($filterStatus) {
                return $c['cf_result'] === $filterStatus;
            });
        }

        return $childrenData;
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
     * Get detailed medical record and chart data for a child.
     */
    public function getChildDetails(int $childId): ?array
    {
        $child = Children::with(['posyandu', 'user', 'predictions' => function ($q) {
            $q->orderBy('examined_at', 'asc');
        }])->find($childId);

        if (!$child) {
            return null;
        }

        $latestPrediction = $child->predictions->last(); // Order is asc, so last is newest
        $latestIntervention = $latestPrediction ? Intervention::where('prediction_id', $latestPrediction->id)->first() : null;

        $growthHistory = $child->predictions->take(-5); // Last 5 measurements
        $count = $growthHistory->count();
        $growthPoints = collect();
        $pathD = '';

        foreach ($growthHistory as $index => $pred) {
            $h = floatval($pred->height);
            $age = $pred->age_months;

            // X coordinate
            if ($count > 1) {
                $x = 40 + $index * (340 / ($count - 1));
            } else {
                $x = 210;
            }

            // Y coordinate non-linear mapping
            if ($h >= 78) {
                $y = 110 - (($h - 78) / 7) * 85;
            } elseif ($h >= 75) {
                $y = 160 - (($h - 75) / 3) * 50;
            } else {
                $y = 200 - (($h - 70) / 5) * 40;
            }

            $y = max(20, min(200, $y));

            $growthPoints->push([
                'x' => round($x, 1),
                'y' => round($y, 1),
                'age' => $age,
                'height' => $h,
                'weight' => floatval($pred->weight),
                'status' => $pred->result,
                'date' => $pred->examined_at ? $pred->examined_at->format('d/m/Y') : '',
            ]);
        }

        foreach ($growthPoints as $index => $point) {
            if ($index === 0) {
                $pathD .= "M {$point['x']} {$point['y']}";
            } else {
                $pathD .= " L {$point['x']} {$point['y']}";
            }
        }

        return [
            'id' => $child->id,
            'name' => $child->name,
            'nik' => $child->nik ?? '-',
            'gender' => $child->gender,
            'age_months' => $child->birth_date ? $child->birth_date->diffInMonths(now()) : 0,
            'parent_name' => $child->user->name ?? '-',
            'posyandu' => $child->posyandu->name ?? '-',
            'latest_bb' => $latestPrediction ? floatval($latestPrediction->weight) : null,
            'latest_tb' => $latestPrediction ? floatval($latestPrediction->height) : null,
            'ml_screening' => $latestPrediction ? $this->getMlScreeningText($latestPrediction) : 'Normal',
            'ml_result' => $latestPrediction ? $latestPrediction->result : 'normal',
            'cf_risk' => $latestPrediction ? $this->getCfRiskText($latestPrediction) : 'Hijau / Normal',
            'cf_result' => $latestPrediction ? $latestPrediction->result : 'normal',
            'validation_status' => $latestPrediction ? $this->getValidationStatus($latestPrediction->id) : 'verified',
            'recommendations' => $latestIntervention ? $latestIntervention->recommendation : 'Tidak ada catatan intervensi khusus.',
            'history' => $growthPoints,
            'pathD' => $pathD,
            'latest_prediction_id' => $latestPrediction ? $latestPrediction->id : null,
        ];
    }

    protected function getMlScreeningText(Prediction $pred): string
    {
        $statusMap = [
            'normal' => 'Normal',
            'stunting_risk' => 'Risiko Stunting',
            'stunted' => 'Pendek (Stunting)',
            'severely_stunted' => 'Sangat Pendek',
        ];
        $text = $statusMap[$pred->result] ?? 'Normal';
        $pct = number_format($pred->confidence * 100, 2);
        return "{$text} (ML: {$pct}%)";
    }

    protected function getCfRiskText(Prediction $pred): string
    {
        $statusMap = [
            'normal' => 'Hijau / Normal',
            'stunting_risk' => 'Amber / Risiko Sedang',
            'stunted' => 'Merah / Risiko Tinggi',
            'severely_stunted' => 'Merah / Risiko Tinggi',
        ];
        $text = $statusMap[$pred->result] ?? 'Hijau / Normal';
        $pct = number_format($pred->confidence * 100, 1);
        return "{$text} (CF: {$pct}%)";
    }

    /**
     * Override/verify child growth status diagnosis.
     */
    public function overrideChildStatus(int $childId, string $status): void
    {
        $child = Children::with(['predictions' => function ($q) {
            $q->orderBy('examined_at', 'desc');
        }])->find($childId);

        if (!$child) {
            return;
        }

        $latestPrediction = $child->predictions->first();
        if ($latestPrediction) {
            // Update prediction result if status is a valid enum value
            if (in_array($status, ['normal', 'stunting_risk', 'stunted', 'severely_stunted'])) {
                $latestPrediction->update(['result' => $status]);
            }

            // Update intervention status if it exists to done/verified
            $intervention = Intervention::where('prediction_id', $latestPrediction->id)->first();
            if ($intervention) {
                $intervention->update(['status' => 'done']);
            }
        }
    }

    /**
     * Get all data required for the children index dashboard.
     */
    public function getIndexData(array $filters, User $user): array
    {
        $userId = $user->isBidan() ? null : $user->id;

        $search = $filters['search'] ?? '';
        $filterPosyandu = $filters['filterPosyandu'] ?? '';
        $filterStatus = $filters['filterStatus'] ?? '';
        $selectedChildId = $filters['selectedChildId'] ?? null;

        // Fetch filtered children list
        $children = $this->getChildrenList(
            $search,
            $filterPosyandu,
            $filterStatus,
            $userId
        );

        // Auto-select first child if none selected or if selected is not in the current list
        if ($children->isNotEmpty()) {
            if (!$selectedChildId || !$children->pluck('id')->contains($selectedChildId)) {
                $selectedChildId = $children->first()['id'];
            }
        } else {
            $selectedChildId = null;
        }

        // Fetch detailed record of selected child
        $selectedChild = $selectedChildId 
            ? $this->getChildDetails($selectedChildId) 
            : null;

        // Fetch all posyandus for the dropdown filter
        $posyandus = Posyandu::all();

        return [
            'children' => $children,
            'selectedChild' => $selectedChild,
            'posyandus' => $posyandus,
            'search' => $search,
            'filterPosyandu' => $filterPosyandu,
            'filterStatus' => $filterStatus,
            'selectedChildId' => $selectedChildId,
        ];
    }

    /**
     * Get all data required to render the balita registration form.
     * Returns posyandu options and orang_tua accounts for dropdowns.
     */
    public function getFormData(User $user): array
    {
        // Kader hanya bisa mendaftarkan ke posyandu-nya sendiri
        if ($user->isKader()) {
            $posyandus = Posyandu::where('id', $user->posyandu_id)->get();
        } else {
            $posyandus = Posyandu::orderBy('name')->get();
        }

        // Daftar akun orang tua untuk linked-account (opsional)
        $orangTuaList = User::where('role', 'orang_tua')
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return [
            'posyandus'    => $posyandus,
            'orangTuaList' => $orangTuaList,
        ];
    }

    /**
     * Create and persist a new Children (balita) record.
     */
    public function createBalita(array $data, User $createdBy): Children
    {
        // Kader selalu menggunakan posyandu miliknya
        $posyanduId = $createdBy->isKader()
            ? $createdBy->posyandu_id
            : ($data['posyandu_id'] ?? null);

        return Children::create([
            'name'        => $data['name'],
            'nik'         => $data['nik'] ?? null,
            'birth_date'  => $data['birth_date'],
            'birth_place' => $data['birth_place'],
            'gender'      => $data['gender'],
            'address'     => $data['address'],
            'posyandu_id' => $posyanduId,
            'user_id'     => $data['user_id'] ?? null,
        ]);
    }
}
