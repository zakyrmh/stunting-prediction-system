<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChildrenIndexRequest;
use App\Http\Requests\ChildrenOverrideRequest;
use App\Services\ChildrenService;

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
    public function index(ChildrenIndexRequest $request)
    {
        // Delegate index data gathering entirely to the service
        $data = $this->childrenService->getIndexData(
            $request->validated(),
            auth()->user()
        );

        return view('balita.index', $data);
    }

    /**
     * Override/verify the diagnostic status of a child.
     */
    public function overrideStatus(ChildrenOverrideRequest $request, $id)
    {
        $validated = $request->validated();

        // Perform status override/verification logic via the service
        $this->childrenService->overrideChildStatus($id, $validated['status']);

        // Redirect back, preserving current search/filter parameters
        return redirect()->route('balita.index', [
            'selectedChildId' => $id,
            'search' => $validated['search'] ?? null,
            'filterPosyandu' => $validated['filterPosyandu'] ?? null,
            'filterStatus' => $validated['filterStatus'] ?? null,
        ])->with('success', "Status gizi balita berhasil diverifikasi & diperbarui.");
    }
}
