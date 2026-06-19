<?php

namespace App\Http\Controllers;

use App\Http\Requests\DashboardRequest;
use App\Services\DashboardService;

class DashboardController extends Controller
{
    /**
     * The dashboard service instance.
     */
    protected DashboardService $dashboardService;

    /**
     * Create a new controller instance.
     */
    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Render the role-based dashboard view.
     */
    public function index(DashboardRequest $request)
    {
        $user = auth()->user();
        $bidanData = [];
        $kaderData = [];
        $parentData = [];

        if ($user->isBidan()) {
            $bidanData = $this->dashboardService->getBidanData();
        } elseif ($user->isKader()) {
            if (!is_null($user->posyandu_id)) {
                $kaderData = $this->dashboardService->getKaderData((int) $user->posyandu_id);
            }
        } elseif ($user->isOrangTua()) {
            $parentData = $this->dashboardService->getOrangTuaData($user, $request->input('child_id'));
        }

        return view('dashboard', compact('bidanData', 'kaderData', 'parentData'));
    }
}
