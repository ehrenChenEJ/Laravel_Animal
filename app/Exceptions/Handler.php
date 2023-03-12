<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use App\Traits\ApiResponseTrait; // 引用特徵

class Handler extends ExceptionHandler
{
    use ApiResponseTrait; // 使用特徵，類似將Trait撰寫的方法貼到這個類別中

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
        if ($request->expectsJson()) { // 如果使用者請求伺服器回傳json格式
            if ($exception instanceof ModelNotFoundException) { // 攔截到的錯誤是否屬於這個類別
                return response()->json(
                    [
                        'error' => '找不到資源'
                    ],
                    Response::HTTP_NOT_FOUND
                );
            }
        }

        if ($request->expectsJson()) {
            // Model上找不到資源
            if ($exception instanceof ModelNotFoundException) {

                // call errorResponse
                return $this->errorResponse('找不到資源', Response::HTTP_NOT_FOUND);
            }

            // 輸入網址錯誤
            if ($exception instanceof NotFoundHttpException) {
                return $this->errorResponse('無法找出此網址', Response::HTTP_NOT_FOUND);
            }

            // 網址不允許
            if ($exception instanceof MethodNotAllowedHttpException) {
                return $this->errorResponse($exception->getMessage(), Response::HTTP_METHOD_NOT_ALLOWED);
            }
        }

        // 執行父類別render的程式
        return parent::render($request, $exception);
    }
}
