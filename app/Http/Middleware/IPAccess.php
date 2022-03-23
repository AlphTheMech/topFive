<?php

namespace App\Http\Middleware;

use App\Models\WhiteListIp;
use Closure;

class IPAccess
{
    public $an_exception = 23;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $employeeId = $request->user()->employee->id ?? null;

        if ($employeeId === $this->an_exception) {
            return $next($request);
        }

        $ip = $request->ip();

        if (WhiteListIp::IpEquals($ip)->first()) {
            return $next($request);
            
        }

        abort(403, 'Access denided');
    }
}
