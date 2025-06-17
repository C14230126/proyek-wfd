<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (NotFoundHttpException $e, $request) {
            // Periksa jika permintaan adalah API (misalnya, dari JavaScript frontend)
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'message' => 'Not Found. ' . ($e->getMessage() ?: 'Resource not found.'),
                    'code' => 404
                ], 404);
            }

            // Jika ini bukan permintaan API (misal, web biasa), lakukan redirect
            // Hanya redirect ke login jika user belum login
            return redirect()->route('login'); 
        });
    })->create();
