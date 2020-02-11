<?php

namespace App\Http\Middleware;

use Closure;
use App\Languages;
use Auth;



class Language
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
        
        // $lang_id = Auth::user()->language;
               
        $lang = Languages::where('status',1)->first(); 
        
        $final_lang=json_decode($lang->languagePhrases);
        \Illuminate\Support\Facades\View::share('phrase',$final_lang);
        return $next($request);
    }
}
