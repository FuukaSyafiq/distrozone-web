<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];
    public function render($request, Throwable $exception)
    {
        if ($this->isHttpException($exception)) {
            switch ($exception->getStatusCode()) {
                // not found
                case 404:
                    return redirect()->route('index');
                    break;

                // internal error
                case 500:
                    return redirect()->route('index');
                    break;
            }
        } else {
            return parent::render($request, $exception);
        }
    }
    // public function render($request, Throwable $e)
    // {
    //     if ($this->isHttpException($e)) {
    //         switch ($e->getStatusCode()) {
    //             // not found
    //             case 404:
    //                 return redirect()->guest('home');
    //                 break;

    //             // internal error
    //             case '500':
    //                 return redirect()->guest('home');
    //                 break;

    //             default:
    //                 return $this->renderHttpException($e);
    //         }
    //     } else {
    //         return parent::render($request, $e);
    //     }
    // }
    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
