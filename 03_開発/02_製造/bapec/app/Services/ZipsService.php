<?php

namespace App\Services;

use App\Repositories\ZipsRepository;

/**
 * 郵便番号検索関連の処理をまとめたサービスクラス
 *
 * @package   App\Services\Util
 * @version   1.0
 */
class ZipsService
{
    /**
     * コンストラクタ
     *
     * @access public
     * @param ZipsRepository $zipsRepository 郵便番号
     */
    public function __construct(
        ZipsRepository $zipsRepository
    ) {
        $this->zipsRepository = $zipsRepository;
    }
    /**
     * 郵便番号を基に、住所一覧を取得する。
     *
     * @access public
     * @param string $zipCode 郵便番号
     * @return array
     */
    public function findByZipCode(string $zipCode)
    {
        return $this->zipsRepository->findByZipCode($zipCode);
    }
    /**
     * 住所を基に、郵便番号一覧を取得する。
     *
     * @access public
     * @param string $addr 住所
     * @return array
     */
    public function findByAddr(string $addr)
    {
        return $this->zipsRepository->findByAddr($addr);
    }
}
