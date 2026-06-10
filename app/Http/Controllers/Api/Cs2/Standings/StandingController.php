<?php

namespace App\Http\Controllers\Api\Cs2\Standings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Cs2\Standings\ShowStandingRequest;
use App\Http\Resources\Api\Cs2\Standings\StandingResource;
use App\Services\Cs2\Stages\StageService;
use App\Services\Cs2\Standings\StandingService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class StandingController extends Controller
{
    public function __construct(
        private readonly StageService $stages,
        private readonly StandingService $standings,
    ) {
    }

    public function show(ShowStandingRequest $request, int $stage): JsonResponse
    {
        if (! $this->stages->find($stage)) {
            return response()->json([
                'success' => false,
                'message' => 'Stage not found.',
                'data' => null,
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'success' => true,
            'data' => StandingResource::collection($this->standings->showByStage($stage)),
        ]);
    }
}
