<?php

namespace App\Http\Controllers;

use App\Http\Requests\PredictionIndexRequest;
use App\Services\StuntingPredictionService;

class PredictionController extends Controller
{
    /**
     * The prediction service instance.
     */
    protected StuntingPredictionService $predictionService;

    /**
     * Create a new controller instance.
     */
    public function __construct(StuntingPredictionService $predictionService)
    {
        $this->predictionService = $predictionService;
    }

    /**
     * Render the index page of prediction records history.
     */
    public function index(PredictionIndexRequest $request)
    {
        // Fetch all index data via the service, keeping it thin and SoC-compliant
        $data = $this->predictionService->getIndexData(
            $request->validated(),
            auth()->user()
        );

        return view('prediction.index', $data);
    }
}
