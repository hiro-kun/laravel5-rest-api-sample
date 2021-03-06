<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        $isDebug = false;
        $request = $request->all();

        // Keyが設定されていない場合は初期化
        $request['debug'] = $request['debug'] ?? '';

        // debug判定
        if (\App::environment() === 'local' && $request['debug'] === '1') {
            $isDebug = true;
        }

        try {
            throw $exception;
        } catch (\App\Exceptions\ApplicationException $e) {
            return response()->json(
                $this->errorResponse(
                    $e->getMessage(),
                    $e->getErrorField(),
                    $e->getCode(),
                    $request['uuid']
                ),
                $e->getHttpStatus()
            );
        } catch (\PDOException $e) {
            if ($isDebug === true) {
                var_dump($e);
                exit;
            }

            \App\Library\Log\ApplicationLog::makeErrorLog($e);

            return response()->json(
                $this->errorResponse(
                    'DB Error.',
                    '',
                    \App\Library\Constant\ApplicationErrorCode::DB_ERROR,
                    $request['uuid']
                ),
                400
            );
        } catch (\Throwable $e) {
            if ($isDebug === true) {
                var_dump($e);
                exit;
            }

            \App\Library\Log\ApplicationLog::makeErrorLog($e);

            return response()->json(
                $this->errorResponse(
                    'System Error.',
                    '',
                    \App\Library\Constant\ApplicationErrorCode::SYSTEM_ERROR,
                    $request['uuid']
                ),
                500
            );
        }

        return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest(route('login'));
    }

    private function errorResponse($message, $field, $code, $requestId)
    {
        $errorResponse["request_id"]      = $requestId;
        $errorResponse["message"]         = $message;
        $errorResponse["errors"]["field"] = $field;
        $errorResponse["errors"]["code"]  = $code;

        return $errorResponse;
    }
}
