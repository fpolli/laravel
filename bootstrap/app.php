<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\NoHoney;
use App\Http\Middleware\TimeFactory;
use App\Http\Middleware\CurrentSite;

return Application::configure(basePath: dirname(__DIR__))
  ->withRouting(
    web: __DIR__ . '/../routes/web.php',
    commands: __DIR__ . '/../routes/console.php',
    health: '/up',
  )
  ->withMiddleware(function (Middleware $middleware) {
    //
    $middleware->web(prepend: [
      CurrentSite::class,
    ]);
    $middleware->web(replace: [
      Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class =>
      App\Http\Middleware\CheckCsrf::class
    ]);
    $middleware->web(append: [
      NoHoney::class,
      TimeFactory::class,
    ]);
  })
  ->withExceptions(function (Exceptions $exceptions) {
    //
  })->create();
