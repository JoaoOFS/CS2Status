<?php

namespace App\Http\Controllers\Api\Cs2\Teams;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Cs2\Teams\ListStageTeamsRequest;
use App\Http\Resources\Api\Cs2\Teams\StageTeamResource;
use App\Services\Cs2\Stages\StageService;
use App\Services\Cs2\Teams\TeamService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class TeamController extends Controller
{
    public function __construct(
        private readonly StageService $stages,
        private readonly TeamService $teams,
    ) {
    }

    public function index(ListStageTeamsRequest $request, int $stage): JsonResponse
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
            'data' => StageTeamResource::collection($this->teams->listByStage($stage)),
        ]);
    }
}
