<?php

namespace App\Exceptions;

use App\Http\Controllers\Traits\ApiResponse;
use Exception;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponse;
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param Throwable $exception
     * @return void
     *
     * @throws Exception|Throwable
     */
    public function report(Throwable $exception)
    {
        $method = app('request')->getMethod();
        if ($exception instanceof HttpException) {
            Log::warning(get_class($exception) . ': (Code:' . $exception->getStatusCode() . ') ' . $exception->getMessage() . ' at ' . $exception->getFile() . ':' . $exception->getLine() . '([' . $method . '] Request: ' . URL::full() . ')');
            return;
        } else if ($exception instanceof ConnectException) {
            Log::alert('Call uri: ' . $exception->getRequest()->getUri() . ' get time out with exception: ' . $exception->getTraceAsString());
            return;
        }

        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     * @param Throwable $exception
     * @return Response|JsonResponse
     *
     * @throws Throwable
     */
    public function render($request, Throwable $exception): Response|JsonResponse
    {
        if ($exception instanceof MethodNotAllowedHttpException) {
            return $this->fail($exception->getMessage(), ResponseAlias::HTTP_METHOD_NOT_ALLOWED);
        } else if ($exception instanceof ValidationException) {
            $errors = $exception->errors();
            $error = collect($errors)->first();
            $errorStr = $error ? $error[0] : '';
            return $this->fail($errorStr, ResponseAlias::HTTP_BAD_REQUEST);
        } else if ($exception instanceof NotFoundHttpException) {
            return $this->fail('Request not found', ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
        Log::info('[RENDER EXCEPTION] - '.$exception->getMessage());
        return $this->fail($exception->getMessage());
    }
}
