<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTokenCanCrud
{
    public function handle(Request $request, Closure $next): Response|JsonResponse
    {
        $user = $request->user();

        if (! $user || ! $user->tokenCan('crud')) {
            return response()->json([
                'message' => 'Deze token heeft geen CRUD-rechten.',
            ], 403);
        }

        return $next($request);
    }
}
