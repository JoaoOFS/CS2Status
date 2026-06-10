<?php

namespace App\Http\Controllers\Api\Cs2\Events;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Cs2\Events\ListEventsRequest;
use App\Http\Requests\Api\Cs2\Events\ShowEventRequest;
use App\Http\Resources\Api\Cs2\Events\EventResource;
use App\Services\Cs2\Events\EventService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class EventController extends Controller
{
    public function __construct(private readonly EventService $events)
    {
    }

    public function index(ListEventsRequest $request): JsonResponse
    {
        $events = $this->events->paginate(
            $request->safe()->except('per_page'),
            (int) $request->integer('per_page', 15),
        );

        return response()->json([
            'success' => true,
            'data' => EventResource::collection($events),
        ]);
    }

    public function show(ShowEventRequest $request, int $event): JsonResponse
    {
        $eventModel = $this->events->find($event);

        if (! $eventModel) {
            return response()->json([
                'success' => false,
                'message' => 'Event not found.',
                'data' => null,
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'success' => true,
            'data' => new EventResource($eventModel),
        ]);
    }
}
