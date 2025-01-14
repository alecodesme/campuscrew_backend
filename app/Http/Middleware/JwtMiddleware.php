<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class JwtMiddleware
{
    public function handle($request, Closure $next)
    {
        $excludedRoutes = [
            'api/signup',
            'api/login',
            'api/universities',
            'api/students',
        ];

        if ($request->is(...$excludedRoutes)) {
            return $next($request);
        }

        try {
            JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            return response()->json(['error' => 'Token not valid or not logged in.'], 401);
        }

        return $next($request);
    }
}
