<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserIndexRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * The user service instance.
     */
    protected UserService $userService;

    /**
     * Create a new controller instance.
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Render the Manajemen Kader index page with stats, list, and form data.
     */
    public function index(UserIndexRequest $request): View
    {
        $filters = $request->validated();

        $stats         = $this->userService->getStats();
        $kaders        = $this->userService->getKaderList($filters);
        $posyandus     = $this->userService->getPosyanduOptions();

        return view('users.index', compact('stats', 'kaders', 'posyandus', 'filters'));
    }

    /**
     * Store a newly created kader account.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $this->userService->createKader($request->validated());

        return redirect()
            ->route('users.index')
            ->with('success', 'Akun kader berhasil dibuat.');
    }

    /**
     * Return kader data as JSON to populate the edit modal.
     */
    public function edit(User $user): JsonResponse
    {
        abort_unless(auth()->user()->isBidan(), 403);
        abort_unless($user->isKader(), 403, 'Hanya akun kader yang dapat diedit di sini.');

        return response()->json([
            'id'          => $user->id,
            'name'        => $user->name,
            'email'       => $user->email,
            'phone'       => $user->phone,
            'posyandu_id' => $user->posyandu_id,
        ]);
    }

    /**
     * Update an existing kader account.
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        abort_unless($user->isKader(), 403, 'Hanya akun kader yang dapat diedit di sini.');

        $this->userService->updateKader($user, $request->validated());

        return redirect()
            ->route('users.index')
            ->with('success', "Akun {$user->name} berhasil diperbarui.");
    }

    /**
     * Toggle the active/inactive status of a kader account.
     */
    public function toggleActive(User $user): RedirectResponse
    {
        abort_unless(auth()->user()->isBidan(), 403);
        abort_unless($user->isKader(), 403, 'Hanya akun kader yang dapat dikelola di sini.');

        $updated = $this->userService->toggleActive($user);
        $statusLabel = $updated->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()
            ->route('users.index')
            ->with('success', "Akun {$user->name} berhasil {$statusLabel}.");
    }

    /**
     * Permanently delete a kader account.
     */
    public function destroy(User $user): RedirectResponse
    {
        abort_unless(auth()->user()->isBidan(), 403);
        abort_unless($user->isKader(), 403, 'Hanya akun kader yang dapat dihapus di sini.');

        $name = $user->name;
        $this->userService->deleteKader($user);

        return redirect()
            ->route('users.index')
            ->with('success', "Akun {$name} berhasil dihapus dari sistem.");
    }
}
