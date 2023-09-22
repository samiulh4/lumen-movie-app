<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtMiddleware extends BaseMiddleware
{
    public function handle($request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof TokenInvalidException) {
                return response()->json(['message' => 'Token is invalid !']);
            } else if ($e instanceof TokenExpiredException) {
                return response()->json(['message' => 'Token has expired !']);
            } else {
                return response()->json(['message' => 'Authorization token not found !']);
            }
        }
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        return $next($request);
    }// end -:- handle()
}// end -:- JwtMiddleware()
