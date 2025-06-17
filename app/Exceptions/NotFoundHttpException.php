<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException; // Import this
use Illuminate\Support\Facades\Auth;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Throwable $exception)
    {
       $this->reportable(function (Throwable $e) {
            // Ini untuk logging atau melaporkan pengecualian
        });

        // ✅ INI ADALAH CARA ANDA MEREGISTER LOGIKA CUSTOM HANDLER UNTUK LARAVEL 10+
        $this->renderable(function (NotFoundHttpException $e, $request) {
            // Periksa jika permintaan adalah API (misalnya, dari JavaScript frontend)

            dd('Masuk ke NotFoundHttpException handler');

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
    }
}