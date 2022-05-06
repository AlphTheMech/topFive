<?php

namespace App\Http\Middleware;

use App\Exceptions\ApiException;
use App\Models\WhiteListIP;
use Closure;

class IPAccess
{
    
    /**
     * an_exception
     *
     * @var int
     */
    private $an_exception = 70;
    
    /**
     * whiteListIp
     *
     * @var array
     */
    private $whiteListIp = [
        '85.249.61.36', '94.233.250.210', '127.0.0.1'
    ];
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = auth('sanctum')->user();
        if ($user->id == $this->an_exception || in_array($request->ip(), $this->whiteListIp)) {
            return $next($request);
        }
        if ($request->ip() != trim($user->list->ip_address ?? null)) {
            throw new ApiException(403, 'Отказано в доступе');
        }
        return $next($request);
    }
}
