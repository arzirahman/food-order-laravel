<?php

namespace App\Http\Middleware;

use App\Http\Resources\MessageResource;
use App\Providers\JwtServiceProvider;
use Closure;
use Exception;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    public function handle($request, Closure $next, ...$guards)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return MessageResource::error(401, 'Request Failed', null);
        }

        try {
            $user = JwtServiceProvider::verifyToken($token);
            $request->merge(['user' => $user]);
        } catch (Exception $e) {
            return MessageResource::error(403, 'Request Failed', null);
        }

        return $next($request);
    }
}
