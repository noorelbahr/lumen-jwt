<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Illuminate\Http\Request;

class JwtMiddleware
{
    public function handle(Request $request, Closure $next, $guard = null)
    {
        // Get token
        $token = $request->get('token');

        // Check token
        if (!$token) {
            return response()->json([
                'error_message' => 'Token not provided.'
            ], 401);
        }

        try {
            $credentials = JWT::decode($token, env('JWT_SECRET'), ['HS256']);

        } catch (ExpiredException $e) {
            return response()->json([
                'error_message' => 'Provided token is expired.'
            ], 400);

        } catch (Exception $e) {
            return response()->json([
                'error_message' => 'An error while decoding token.'
            ], 400);
        }

        // Get user data and put it in request class
        $user = User::find($credentials->sub);
        $request->auth = $user;

        return $next($request);
    }
}
