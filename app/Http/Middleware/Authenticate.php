<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Support\Facades\Auth as JWTAuth;

class Authenticate
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $token = $request->bearerToken();

        if (!$token) {
            abort(401, "Token is not valid");
        }

        try {
            $payload = JWTAuth::payload();
        } catch (\Throwable $th) {
            abort(401, $th->getMessage());
        }

        if (!$payload->get('sub')) {
            abort(401, "Token is not valid");
        }

        $user = User::find($payload->get('sub'));

        if (!$user) {
            abort(401, "User does not exist");
        }

        $request->user_id = $user->id;

        return $next($request);
    }
}
