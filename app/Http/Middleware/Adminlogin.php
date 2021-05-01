<?php

namespace App\Http\Middleware;

use Closure;
use Session;

class Adminlogin
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
        if(empty(Session::has('adminSession')))
        {
            return redirect('/admin')->with('flash_error_message', 'Session Expired, Please login to Continue');
        }
        return $next($request);
    }
}
