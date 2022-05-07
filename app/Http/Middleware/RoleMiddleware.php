<?php

namespace App\Http\Middleware;

use App\Exceptions\ApiException;
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
            throw new ApiException(403, 'Отказано в доступе');
        }
        if ($permission !== null && !auth()->user()->can($permission)) {
            throw new ApiException(403, 'Отказано в доступе');
        }
        return $next($request);
    }
}
