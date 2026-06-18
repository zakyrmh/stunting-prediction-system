<?php

namespace App\Http\Controllers;

use App\Http\Requests\PosyanduIndexRequest;
use App\Http\Requests\StorePosyanduRequest;
use App\Services\PosyanduService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PosyanduController extends Controller
{
    /**
     * The posyandu service instance.
     */
    protected PosyanduService $posyanduService;

    /**
     * Create a new controller instance.
     */
    public function __construct(PosyanduService $posyanduService)
    {
        $this->posyanduService = $posyanduService;
    }

    /**
     * Render the master posyandu dashboard index page.
     */
    public function index(PosyanduIndexRequest $request): View
    {
        // Get all structured dashboard data via the service, keeping logic separated
        $data = $this->posyanduService->getIndexData($request->validated());

        return view('posyandu.index', $data);
    }

    /**
     * Render the form to register a new Posyandu.
     */
    public function create(): View
    {
        abort_unless(auth()->user()->isBidan(), 403);

        return view('posyandu.form');
    }

    /**
     * Store a newly registered Posyandu and redirect.
     */
    public function store(StorePosyanduRequest $request): RedirectResponse
    {
        $posyandu = $this->posyanduService->createPosyandu($request->validated());

        return redirect()
            ->route('posyandu.index', ['selectedPosyanduId' => $posyandu->id])
            ->with('success', "Posyandu \"{$posyandu->name}\" berhasil didaftarkan ke sistem.");
    }
}
