<?php

namespace App\Exceptions;

use Throwable;
use PDOException;
use Illuminate\Support\Facades\Lang;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;

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
        CustomException::class,
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

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(
            function (Throwable $e) {
                return true;
            }
        );
    }


    /**
     * Method render
     *
     * @param Illuminate\Http\Request $request
     * @param Throwable               $exception
     *
     * @return JsonResponse|Response|\Symfony\Component\HttpFoundation\Response
     * @throws Throwable
     */
    public function render($request, Throwable $exception)
    {

        $appEnv = config('app.env');
        $message = $exception->getMessage();
        $code = 400;

        // Validation error
        if ($exception instanceof ValidationException) {
            return parent::render($request, $exception);
        }
        // Show custom message for bad request
        if ($exception->getCode() == 0) {
            $message = Lang::get('message.error.exception.server');
        }
        
        // Show custom validation message 
        if ($exception instanceof CustomException) {
            $message = $exception->getMessage();
        }

        // Set message for database error
        if ($exception instanceof PDOException) {
            $message = Lang::get('message.error.exception.server');
        }
        // Set message for database error
        if ($exception instanceof NotFoundHttpException) {
            $code = 404;
            $message = Lang::get('message.error.exception.page_not_found');
        }
        // Set message for authorization exception
        if ($exception instanceof AuthorizationException || $exception instanceof UnauthorizedException) {
            $code = 403;
            $message = Lang::get('message.error.exception.not_allowed');
        }
        // Set message for model not found exception
        if ($exception instanceof ModelNotFoundException) {
            $code = 404;
            $message = Lang::get('message.error.exception.model_not_found');
        }
        // Set message for authorization exception
        if ($exception instanceof AuthenticationException) {
            $code = 401;
            $message = Lang::get('message.error.session_expired');
        }

        // Send response for API and ajax call.
        if ($request->ajax() || $request->wantsJson() || $request->is('api/*')) {
            return response()->json(
                [
                    'success' => false,
                    'data' => [],
                    'message' => $message
                ],
                $code
            );
        } else {
            // Show custom message if app env is production.
            if ($appEnv == "production") {
                if ($exception instanceof NotFoundHttpException) {
                    return response()->view('errors.default', ['errorCode'=>404, 'message'=>Lang::get('message.error.exception.page_not_found')], 404);
                } elseif ($exception instanceof ThrottleRequestsException) {
                    return response()->view('errors.default', ['errorCode'=>429, 'message'=>Lang::get('message.error.exception.too_many_attempts')], 429);
                } elseif ($exception instanceof ServiceUnavailableHttpException) {
                    return response()->view('errors.default', ['errorCode'=>503, 'message'=>Lang::get('message.error.exception.service_temporarily_unavailable')], 503);
                } elseif ($exception instanceof AuthorizationException || $exception instanceof UnauthorizedException) {
                    return response()->view('errors.default', ['errorCode'=>403, 'message'=>Lang::get('message.error.exception.not_allowed')], 403);
                } elseif ($exception instanceof AuthenticationException) {
                    if ($request->is('admin/*')) {
                        return redirect()->route('admin.login');
                    }
                } else {
                    return response()->view('errors.default', ['errorCode'=>500, 'message'=>Lang::get('message.error.exception.server')], 500);
                }
            } else {
                if ($exception instanceof NotFoundHttpException) {
                    return response()->view('errors.default', ['errorCode'=>404, 'message'=>Lang::get('message.error.exception.page_not_found')], 404);
                }
                return parent::render($request, $exception);
            }
        }
    }
}
