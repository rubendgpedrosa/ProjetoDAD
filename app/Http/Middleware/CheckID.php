<?php

namespace App\Http\Middleware;

use Closure;

class CheckID
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($request->user()->id == $request->route('id')){
            return $next($request);
        }
    }
}
