<?php

use App\Http\Controllers\BaseApiController;
use App\Http\Middleware\LocalizationMiddleware;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->append(LocalizationMiddleware::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->wantsJson()) {
                return (new BaseApiController)->respondUnauthorized(null, $e->getMessage());
            }
        })->render(function (AccessDeniedHttpException $e, Request $request) {
            if ($request->wantsJson()) {
                return (new BaseApiController)->respondForbidden(null, $e->getMessage());
            }
        })->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->wantsJson()) {
                return (new BaseApiController)->respondNotFound(null, $e->getMessage());
            }
        })->render(function (MethodNotAllowedHttpException $e, Request $request) {
            if ($request->wantsJson()) {
                return (new BaseApiController)->setStatusCode(405)->setStatusMsg('failed')->respondDetail($e->getMessage(), false);
            }
        })->render(function (ValidationException $e, Request $request) {
            if ($request->wantsJson()) {
                return (new BaseApiController)->setStatusMsg('failed')->generateResponse(422, ['errors' => $e->errors()], $e->getMessage(), false);
            }
        })->render(function (InvalidFormatException $e, Request $request) {
            if ($request->wantsJson()) {
                return (new BaseApiController)->setStatusMsg('failed')->generateResponse(400, [], $e->getMessage(), false);
            }
        })->render(function (Throwable $e, Request $request) {
            if ($request->wantsJson()) {
                return (new BaseApiController)->respondInternalError(null, $e->getMessage());
            }
        });
    })->create();
