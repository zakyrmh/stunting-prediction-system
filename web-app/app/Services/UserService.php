<?php

namespace App\Services;

use App\Models\Posyandu;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    /**
     * Get summary statistics for the kader management panel.
     */
    public function getStats(): array
    {
        $totalKader = User::where('role', 'kader')->count();
        $activeKader = User::where('role', 'kader')->where('is_active', true)->count();
        $inactiveKader = $totalKader - $activeKader;
        $totalPosyandu = Posyandu::count();
        $posyanduWithoutKader = Posyandu::doesntHave('users', 'and', function ($q) {
            $q->where('role', 'kader');
        })->count();

        return [
            'total_kader'             => $totalKader,
            'active_kader'            => $activeKader,
            'inactive_kader'          => $inactiveKader,
            'total_posyandu'          => $totalPosyandu,
            'posyandu_without_kader'  => $posyanduWithoutKader,
        ];
    }

    /**
     * Get paginated, filterable list of kader accounts.
     */
    public function getKaderList(array $filters): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = User::with('posyandu')
            ->where('role', 'kader')
            ->orderBy('created_at', 'desc');

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if (isset($filters['status']) && $filters['status'] !== '') {
            $query->where('is_active', (bool) $filters['status']);
        }

        if (!empty($filters['posyandu_id'])) {
            $query->where('posyandu_id', $filters['posyandu_id']);
        }

        return $query->paginate(10)->withQueryString();
    }

    /**
     * Get all posyandus for the creation form dropdown.
     */
    public function getPosyanduOptions(): \Illuminate\Database\Eloquent\Collection
    {
        return Posyandu::orderBy('name')->get();
    }

    /**
     * Create a new kader account.
     */
    public function createKader(array $data): User
    {
        return User::create([
            'name'        => $data['name'],
            'email'       => $data['email'],
            'phone'       => $data['phone'] ?? null,
            'password'    => Hash::make($data['password']),
            'role'        => 'kader',
            'posyandu_id' => $data['posyandu_id'],
            'is_active'   => true,
        ]);
    }

    /**
     * Toggle the is_active status of a kader.
     */
    public function toggleActive(User $user): User
    {
        $user->update(['is_active' => !$user->is_active]);
        return $user->fresh();
    }

    /**
     * Permanently delete a kader account.
     */
    public function deleteKader(User $user): void
    {
        $user->delete();
    }
}
