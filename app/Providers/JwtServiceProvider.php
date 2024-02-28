<?php

namespace App\Providers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\ServiceProvider;

class JwtServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }

    public static function generateToken(array $payload): string
    {
        $expirySeconds = env('JWT_EXPIRES', 86400);
        $payload['iat'] = time();
        $payload['exp'] = time() + $expirySeconds;
        return JWT::encode($payload, env('JWT_SECRET'), 'HS256');
    }

    /**
     * Verify and decode a JWT token.
     *
     * @param string $token
     * @return mixed
     */
    public static function verifyToken(string $token)
    {
        return JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
    }
}
