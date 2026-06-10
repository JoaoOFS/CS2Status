<?php

namespace App\Http\Middleware\Api\Cs2;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureValidApiConsumer
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = config('services.cs2.consumer_token');

        if ($token && ! hash_equals($token, (string) $request->bearerToken())) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid API consumer.',
                'data' => null,
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
