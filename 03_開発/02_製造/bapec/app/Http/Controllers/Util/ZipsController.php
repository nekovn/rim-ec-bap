<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Services\ZipsService;

/**
 * 郵便番号検索のリクエストを受付、実行結果を返す。
 *
 * @category  システム共通
 * @package   App\Http\Controllers\Util
 * @version   1.0
 */
class ZipsController extends Controller
{
    /**
     * コンストラクタ
     *
     * @access public
     * @param ZipsService $zipsService 郵便番号検索サービス
     */
    public function __construct(ZipsService $zipsService)
    {
        $this->zipsService = $zipsService;
    }
    /**
     * 郵便番号から住所を検索する。
     *
     * @param string $zipCode 郵便番号
     * @return 住所検索結果
     */
    public function searchAddr($zipCode)
    {
        // 郵便番号に-（ハイフン）が含まれていれば削除
        $zipCode = str_replace('-', '', $zipCode);
        return $this->zipsService->findByZipCode($zipCode);
    }
    /**
     * 住所から郵便番号を検索する。
     *
     * @param string $addr 住所
     * @return 取得結果
     */
    public function searchZip($addr)
    {
        return $this->zipsService->findByAddr($addr);
    }
}
