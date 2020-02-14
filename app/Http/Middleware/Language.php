<?php

namespace App\Http\Middleware;

use Closure;
use App\Languages;
use App\User;
use Illuminate\Support\Facades\Auth;



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
        
        @$lang_id = Auth::user()->language;
        if($lang_id!=1&&$lang_id!=2){
            $lang_id = 1;
        }
               
        $lang = Languages::find(@$lang_id); 
        $final_lang=json_decode($lang->languagePhrases);
        \Illuminate\Support\Facades\View::share('phrase',@$final_lang);
        return $next($request);
    }
}
