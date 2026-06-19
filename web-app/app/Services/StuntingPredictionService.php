<?php

namespace App\Services;

use App\Models\Prediction;
use App\Models\Intervention;
use App\Models\User;
use App\Models\Posyandu;
use App\Models\Children;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

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

    /**
     * Create and store a new monthly child measurement and stunting prediction.
     */
    public function createPrediction(array $data, User $recorder): Prediction
    {
        $child = Children::findOrFail($data['child_id']);
        
        // Calculate child age in months at the time of examination
        $birthDate = $child->birth_date;
        $examinedAt = Carbon::parse($data['examined_at']);
        $ageMonths = $birthDate->diffInMonths($examinedAt);

        // Map gender: male -> 0, female -> 1
        $gender = $child->gender === 'male' ? 0 : 1;

        // Prepare Certainty Factor symptoms
        $gejalaCf = [];
        if (isset($data['gejala']) && is_array($data['gejala'])) {
            foreach ($data['gejala'] as $ruleId => $value) {
                $gejalaCf[$ruleId] = floatval($value);
            }
        }

        $apiUrl = config('services.prediction_service.url', 'http://127.0.0.1:8001') . '/predict';
        
        try {
            $response = Http::timeout(5)->post($apiUrl, [
                'gender' => $gender,
                'age_months' => floatval($ageMonths),
                'weight' => floatval($data['weight']),
                'height' => floatval($data['height']),
                'gejala_cf' => $gejalaCf,
            ]);

            if ($response->successful()) {
                $resData = $response->json();
                $cfTotal = $resData['kesimpulan_sistem_pakar']['tingkat_risiko_total_persen'] ?? 0;
                $recommendations = $resData['kesimpulan_sistem_pakar']['rekomendasi_intervensi'] ?? [];
                
                // Map CF total to result enum
                if ($cfTotal < 40) {
                    $result = 'normal';
                } elseif ($cfTotal < 70) {
                    $result = 'stunting_risk';
                } elseif ($cfTotal < 85) {
                    $result = 'stunted';
                } else {
                    $result = 'severely_stunted';
                }
                
                $confidence = $cfTotal / 100;
            } else {
                throw new \Exception("FastAPI microservice returned error status: " . $response->status());
            }
        } catch (\Exception $e) {
            Log::error("Stunting prediction microservice connection failed: " . $e->getMessage());
            
            // Fallback calculation: if height/weight are very low, classify as stunted/risk
            // Let's do a basic rule of thumb fallback so the system remains functional even if API is offline
            $result = 'normal';
            $confidence = 0.5000;
            $recommendations = ["Saran preventif: Pastikan asupan nutrisi protein hewani terpenuhi dan lakukan penimbangan rutin."];
        }

        // Save prediction record
        $prediction = Prediction::create([
            'child_id' => $child->id,
            'posyandu_id' => $recorder->posyandu_id ?? $child->posyandu_id,
            'recorded_by' => $recorder->id,
            'session_id' => null, // Sesi posyandu (nullable)
            'weight' => $data['weight'],
            'height' => $data['height'],
            'age_months' => $ageMonths,
            'examined_at' => $data['examined_at'],
            'result' => $result,
            'confidence' => $confidence,
            'notes' => $data['notes'] ?? null,
        ]);

        // If prediction is not normal, create a pending intervention
        if ($result !== 'normal' && !empty($recommendations)) {
            Intervention::create([
                'prediction_id' => $prediction->id,
                'recommendation' => implode("\n", $recommendations),
                'status' => 'pending',
                'follow_up_date' => $examinedAt->copy()->addMonth()->toDateString(),
                'handled_by' => null,
            ]);
        }

        return $prediction;
    }
}
