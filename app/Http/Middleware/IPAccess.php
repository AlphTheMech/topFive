<?php

namespace App\Http\Middleware;

// use App\Ip\IpWhiteListItem;

use App\Exceptions\ApiException;
use App\Models\WhiteListIP;
use Closure;

class IPAccess
{
    /**
     * an_exception
     *
     * @var undefined
     */
    public $an_exception = 70;

    /**
     * whiteListIp
     *
     * @var array
     */
    public $whiteListIp = [
        '85.249.61.36', '94.233.250.210'
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
        $list = WhiteListIP::get();
        if (auth()->user()->id == $this->an_exception || in_array($request->ip(), $this->whiteListIp)) {
            return $next($request);
        }
        if (!in_array($request->ip(), $list->where('user_id', auth()->user()->id)->firstOrFail()->toArray())) {
            throw new ApiException(403, 'Доступ запрещен');
        }
        return $next($request);
    }
}
