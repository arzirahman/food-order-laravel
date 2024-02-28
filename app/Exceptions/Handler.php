<?php

namespace App\Exceptions;

use App\Http\Resources\MessageResource;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

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
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof HttpResponseException) {
            return parent::render($request, $exception);
        }

        if ($exception instanceof ValidationException) {
            return MessageResource::error(400, 'Validation Failed', $exception->getMessage());
        }

        if ($exception instanceof NotFoundHttpException) {
            return MessageResource::error(404, 'Request Failed', $exception->getMessage());
        }

        return MessageResource::error(500, 'Request Failed', $exception->getMessage());
    }

}