<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
  ->withRouting(
    web: __DIR__.'/../routes/web.php',
    commands: __DIR__.'/../routes/console.php',
    health: '/up',
  )
  // Permission middleware 1
  // ->withMiddleware(function (Middleware $middleware) {
  //   $middleware->alias([
  //     'role' => \App\Http\Middleware\checkPermission::class,
  // ]);
  //   //
  // })
  ->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
      'permission' => \App\Http\Middleware\checkPermission::class,
  ]);
    //
  })
  ->withExceptions(function (Exceptions $exceptions) {
    //
  })->create();
