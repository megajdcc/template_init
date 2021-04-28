<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TelefonoFormat
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
       // $type = gettype($request->get('telefono'));
         // dd($request->get('telefono'));

        if($request->__isset('telefono')){
                $request->merge(['telefono' =>  str_replace(['(',')','-',' ','+'],'',$request->get('telefono'))]);
                return $next($request);
        }

        return $next($request);
    }
}
