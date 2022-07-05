<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Lang;
use \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Illuminate\Support\Arr;
/**
 * アプリケーション例外ハンドラー
 *
 * @category  システム共通
 * @package   App\Exceptions
 * @version   1.0
 */
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
        'password',
        'password_confirmation',
    ];

    /**
     * Render the given HttpException.
     *
     * @param  \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface  $e
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function renderHttpException(HttpExceptionInterface $e)
    {
        $this->registerErrorViewPaths();
        $status = $e->getStatusCode();
        if( strpos(\Request::path(),'admin/')===0 ){
            // 管理機能の場合
            if (view()->exists($view = "admin.errors.common")) {
                if ($status != Response::HTTP_NOT_FOUND) {
                    $blade ="admin.errors.common";
                } else {
                    $blade = $this->getHttpExceptionView($e);
                }

                if (view()->exists($view = $blade)) {
                    return response()->view($view, [
                    'exception' => $e,
                    'message' => config('app.debug') ? $e->getMessage() : Lang::get('messages.E.systemerr'),
                    'status_code' => $status,
                ], $status, $e->getHeaders());
                }
            }
        } else {
            // 会員機能の場合
            if (view()->exists($view = "member.errors.common")) {
                return response()->view($view, [
                    'exception' => $e,
                    'message' => config('app.debug') ? $e->getMessage() : Lang::get('messages.E.systemerr'),
                    'status_code' => $status,
                ], $status, $e->getHeaders());
                }
        }

        return $this->convertExceptionToResponse($e);
    }
    /**
     * Convert the given exception to an array.
     *
     * @param  \Throwable  $e
     * @return array
     */
    protected function convertExceptionToArray(Throwable $e)
    {
        return config('app.debug') ? [
            'message' => $e->getMessage(),
            'exception' => get_class($e),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => collect($e->getTrace())->map(function ($trace) {
                return Arr::except($trace, ['args']);
            })->all(),
        ] : [
            'message' => Lang::get('messages.E.systemerr'),
        ];
    }
}
