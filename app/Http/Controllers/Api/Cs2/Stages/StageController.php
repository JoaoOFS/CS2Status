<?php

namespace App\Http\Controllers\Api\Cs2\Stages;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Cs2\Stages\ListStagesRequest;
use App\Http\Resources\Api\Cs2\Stages\StageResource;
use App\Services\Cs2\Events\EventService;
use App\Services\Cs2\Stages\StageService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class StageController extends Controller
{
    public function __construct(
        private readonly EventService $events,
        private readonly StageService $stages,
    ) {
    }

    public function index(ListStagesRequest $request, int $event): JsonResponse
    {
        if (! $this->events->find($event)) {
            return response()->json([
                'success' => false,
                'message' => 'Event not found.',
                'data' => null,
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'success' => true,
            'data' => StageResource::collection($this->stages->listByEvent($event, $request->validated())),
        ]);
    }
}
