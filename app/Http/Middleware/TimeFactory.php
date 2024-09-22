<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TimeFactory
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

    if (auth()->check()) {
      session([
        'timezone' => $request->user()->time_zone ?? 'America/Los_Angeles',
        'locale' => $request->user()->locale ?? 'en_US'
      ]);
    } else {
      session([
        'timezone' => 'America/Los_Angeles',
        'locale' => 'en_US'
      ]);
    }
    return $next($request);
  }
}
