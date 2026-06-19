<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChildrenIndexRequest;
use App\Http\Requests\ChildrenOverrideRequest;
use App\Http\Requests\StoreBalitaRequest;
use App\Services\ChildrenService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ChildrenController extends Controller
{
    /**
     * The children service instance.
     */
    protected ChildrenService $childrenService;

    /**
     * Create a new controller instance.
     */
    public function __construct(ChildrenService $childrenService)
    {
        $this->childrenService = $childrenService;
    }

    /**
     * Render the index page with children records and filters.
     */
    public function index(ChildrenIndexRequest $request): View
    {
        // Delegate index data gathering entirely to the service
        $data = $this->childrenService->getIndexData(
            $request->validated(),
            auth()->user()
        );

        return view('balita.index', $data);
    }

    /**
     * Render the form to register a new balita (child).
     */
    public function create(): View
    {
        $user = auth()->user();
        abort_unless($user->isBidan() || $user->isKader(), 403);

        $formData = $this->childrenService->getFormData($user);

        return view('balita.form', $formData);
    }

    /**
     * Store a newly registered balita and redirect to their detail page.
     */
    public function store(StoreBalitaRequest $request): RedirectResponse
    {
        $child = $this->childrenService->createBalita(
            $request->validated(),
            auth()->user()
        );

        return redirect()
            ->route('balita.index', ['selectedChildId' => $child->id])
            ->with('success', "Data balita \"{$child->name}\" berhasil didaftarkan.");
    }

    /**
     * Override/verify the diagnostic status of a child.
     */
    public function overrideStatus(ChildrenOverrideRequest $request, $id): RedirectResponse
    {
        $validated = $request->validated();

        // Perform status override/verification logic via the service
        $this->childrenService->overrideChildStatus($id, $validated['status']);

        // Redirect back, preserving current search/filter parameters
        return redirect()->route('balita.index', [
            'selectedChildId' => $id,
            'search'          => $validated['search'] ?? null,
            'filterPosyandu'  => $validated['filterPosyandu'] ?? null,
            'filterStatus'    => $validated['filterStatus'] ?? null,
        ])->with('success', "Status gizi balita berhasil diverifikasi & diperbarui.");
    }
}
