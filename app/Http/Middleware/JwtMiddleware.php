<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\JsonResponse;

class JwtMiddleware
{
    public function handle($request, Closure $next)
    {
        try {
            // Akan me‑throw jika token tak valid / kadaluarsa
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {

            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return new JsonResponse(['error' => 'Token invalid'], 401);
            }
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return new JsonResponse(['error' => 'Token expired'], 401);
            }
            return new JsonResponse(['error' => 'Token not found'], 401);
        }

        // Simpan user ke request (opsional)
        $request->merge(['auth_user' => $user]);

        return $next($request);
    }
}
