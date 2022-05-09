<?php

namespace App\Exceptions;

use     Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * render
     *
     * @param  mixed $request
     * @param  mixed $e
     * @return void
     */
    public function render($request, Throwable $e)
    {
        if ($e instanceof ModelNotFoundException) {
            throw new ApiException(Response::HTTP_NOT_FOUND, 'Ресурс не найден');
        }
        if ($e instanceof QueryException) {
            throw new ApiException(Response::HTTP_UNPROCESSABLE_ENTITY, 'Запись уже существует');
        }
        if ($e instanceof  RouteNotFoundException) {
            throw new ApiException(Response::HTTP_UNAUTHORIZED, 'Пользователь не авторизирован');
        }
        return parent::render($request, $e);
    }
}
