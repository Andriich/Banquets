<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'api_token',
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        if (Route::getCurrentRoute() === null || str_ends_with(Route::getCurrentRoute()->getPrefix(), 'admin')) {
            return;
        }

        $this->renderable(function (HttpException $httpException) {
            return response([
                'success' => false,
                'message' => $httpException->getMessage(),
            ], $httpException->getStatusCode(), $httpException->getHeaders());
        });

        $this->renderable(function (ValidationException $validationException) {
            $statusCode = $validationException->status;
            if (!in_array($statusCode, array_keys(Response::$statusTexts))) {
                $statusCode = 400; // bad request
            }

            $message = $validationException->getMessage() ?? Response::$statusTexts[$statusCode];
            $errors = $validationException->errors();

            return response([
                'success' => false,
                'message' => $message,
                'errors' => $errors,
            ], $statusCode);
        });
    }
}
