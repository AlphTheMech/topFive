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
        if ($permission !== null && !auth()->user()->can($permission)) {
            // abort(404);
            return response()->json([
                'error' => [
                    'code' => 403,
                    'message' => "Access is denied21"
                ]
            ], 403);
        }
        if (!auth()->user()->hasRole($role)) {
            // abort(404);
            return response()->json([
                'error' => [
                    'code' => 403,
                    'message' => "Access is denied12"
                ]
            ], 403);
        }
        
        return $next($request);
    }
}
