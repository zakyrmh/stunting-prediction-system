<?php

namespace App\Http\Controllers;

use App\Http\Requests\PosyanduIndexRequest;
use App\Services\PosyanduService;

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
    public function index(PosyanduIndexRequest $request)
    {
        // Get all structured dashboard data via the service, keeping logic separated
        $data = $this->posyanduService->getIndexData($request->validated());

        return view('posyandu.index', $data);
    }
}
