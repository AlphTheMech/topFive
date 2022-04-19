<?php

namespace App\Http\Middleware;

// use App\Ip\IpWhiteListItem;
use App\Models\WhiteListIP;
use Closure;

class IPAccess
{    
    /**
     * an_exception
     *
     * @var undefined
     */
    public $an_exception= 23; 
    
    /**
     * whiteListIp
     *
     * @var array
     */
    public $whiteListIp=[
        '91.202.252.234'
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
         $list= WhiteListIP::get();
        if (auth()->user()->id == $this->an_exception){
            return $next($request);
        } 
        if(!in_array($request->ip() , $list->where('employee_id', auth()->user()->id)->firstOrFail()->toArray()))
        {
            abort(403, 'Access denided');
        }
        return $next($request);
    }
}
