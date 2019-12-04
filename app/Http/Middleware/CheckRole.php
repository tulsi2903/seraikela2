<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use App\Users;



class CheckRole
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
        $authID = Users::select('user_id')->get();
        Session::put('logID',$authID);
       // echo(Session::get('logID'));
        
        
        return $next($request);
    }
}
