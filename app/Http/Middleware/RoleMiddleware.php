<?php

namespace App\Http\Middleware;

use Closure;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$accepted_role)
    {
        $role = strtolower(auth()->user()->role);
        if (!in_array($role, $accepted_role)) 
        {
            return apiResponse(
                [
                    'user_role' => $role,
                    'must_role' => $accepted_role
                ],
                'Unauthorized, Not Accepted Role.',
                false,
                'auth.unauthorized',
                null,
                401
            );
        }
        return $next($request);
    }
}
