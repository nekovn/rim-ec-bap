<?php

namespace App\Exceptions;

use Throwable;
use RuntimeException;

/**
 * アプリケーション例外
 *
 * @category  システム共通
 * @package   App\Exceptions\database
 * @version   1.0
 */
class ApplicationException extends RuntimeException
{
    /**
     * エラーコード
     * @var string|null
     */
    protected $errorCode;

    /**
     * コンストラクタ
     *
     * @param string $message 簡易エラーメッセージ
     * @param int $statusCode ステータスコード
     * @param Throwable $exception 発生例外
     */
    public function __construct(string $message = '', int $statusCode = 500, Throwable $exception = NULL)
    {
        parent::__construct($message, $statusCode, $exception);
    }
}
