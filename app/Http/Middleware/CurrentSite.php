<?php

namespace App\Http\Middleware;

use App\Models\Site;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CurrentSite
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next): Response
  {
    switch (config('app.env')) {
      case 'production':
        $currentSite = Site::where('domain', $request->host)->first();
        break;
      case 'development':
        $currentSite = Site::where('development_url', $request->host)->first();
        break;
      case 'local':
        $currentSite = Site::where('local_url', $request->host)->first();
        break;
    }
    $request->request->add(['currentSite' => $currentSite]);

    return $next($request);
  }
}
