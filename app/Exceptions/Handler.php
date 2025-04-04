<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $levels = [];

    protected $dontReport = [];

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return response()->json([
            'error' => 'Unauthorized',
            'message' => 'Your token is missing or invalid.'
        ], 401);
    }

    protected function invalidJson($request, ValidationException $exception)
    {
        return response()->json([
            'error' => 'Validation Failed',
            'messages' => $exception->errors(),
        ], $exception->status);
    }
}
