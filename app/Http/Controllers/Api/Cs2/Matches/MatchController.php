<?php

namespace App\Http\Controllers\Api\Cs2\Matches;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Cs2\Matches\ListMatchesRequest;
use App\Http\Requests\Api\Cs2\Matches\ShowMatchRequest;
use App\Http\Resources\Api\Cs2\Matches\MatchResource;
use App\Services\Cs2\Matches\MatchService;
use App\Services\Cs2\Stages\StageService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class MatchController extends Controller
{
    public function __construct(
        private readonly StageService $stages,
        private readonly MatchService $matches,
    ) {
    }

    public function index(ListMatchesRequest $request, int $stage): JsonResponse
    {
        if (! $this->stages->find($stage)) {
            return response()->json([
                'success' => false,
                'message' => 'Stage not found.',
                'data' => null,
            ], Response::HTTP_NOT_FOUND);
        }

        $matches = $this->matches->paginateByStage(
            $stage,
            $request->safe()->except('per_page'),
            (int) $request->integer('per_page', 25),
        );

        return response()->json([
            'success' => true,
            'data' => MatchResource::collection($matches),
        ]);
    }

    public function show(ShowMatchRequest $request, int $match): JsonResponse
    {
        $matchModel = $this->matches->find($match);

        if (! $matchModel) {
            return response()->json([
                'success' => false,
                'message' => 'Match not found.',
                'data' => null,
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'success' => true,
            'data' => new MatchResource($matchModel),
        ]);
    }
}
