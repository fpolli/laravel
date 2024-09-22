<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class NoHoney
{
  /**
    * Handle an incoming request.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
    * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
    */
  public function handle(Request $request, Closure $next)
  {
    //check if post request
    if($request->isMethod('post')) {
      //check if honey
      $reqtest = $request->username ?? '';
      //check if has a value
      if($reqtest) {
        //if so, add ip and email address given to blacklist?
        //return 404
        abort(404);
        return;
      }
    }
    return $next($request);
  }
}
