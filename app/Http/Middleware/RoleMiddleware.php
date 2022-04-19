<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{

    /**
     * Handle an incoming request.
     * @param $request
     * @param Closure $next
     * @param $role
     * @param null $permission
     * @return mixed
     */
    public function handle($request, Closure $next, $role, $permission = null)
    {
        if (!auth()->user()->hasRole($role)) {
            return response()->json([
                'error' => [
                    'code' => 403,
                    'message' => "Access is denied"
                ]
            ], 403);
        }
        if ($permission !== null && !auth()->user()->can($permission)) {
            return response()->json([
                'error' => [
                    'code' => 403,
                    'message' => "Access is denied21"
                ]
            ], 403);
        }
        return $next($request);
    }
}
