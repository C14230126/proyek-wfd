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
        // Check if the exception is a 404 (NotFoundHttpException)
        if ($exception instanceof NotFoundHttpException) {
            return redirect()->route('login'); // Assuming your login route is named 'login'
        }

        // For all other exceptions, or if the user is authenticated,
        // let the default handler process the response (e.g., show 404 page for authenticated users)
        return parent::render($request, $exception);
    }
}