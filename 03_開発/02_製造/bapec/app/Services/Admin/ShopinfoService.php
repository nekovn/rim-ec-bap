<?php

namespace App\Services\Admin;

use App\Enums\CodeDefine;
use App\Helpers\Util\SystemHelper;
use App\Repositories\ShopsRepository;

/**
 * ショップ基本情報管理関連の処理をまとめたサービスクラス
 *
 * @category  ショップ基本情報管理
 * @package   App\Services
 * @version   1.0
 */
class ShopinfoService
{
    /**
     * @var ShopsRepository
     */
    private $repository;

    /**
     * コンストラクタ
     *
     * @access public
     * @param ShopsRepository $shopsRepository
     */
    public function __construct(ShopsRepository $shopsRepository)
    {
        $this->repository = $shopsRepository;
    }

    /**
     * ショップ基本情報 を取得する。
     *
     * @return array
     */
    public function find(): array
    {
        $row = $this->repository->find(1);
        return ['data' => $row];
    }

    /**
     * ショップ基本情報 を登録する。
     */
    public function store(array $params)
    {
        $where = ['id' => 1];
        return $this->repository->update($params, $where);
    }

    /**
     * 画面の選択肢を取得する。
     *
     * @return array
     */
    public function getScreenSelections(): array
    {
        // 端数処理区分
        $roundingTypes = SystemHelper::getCodes(CodeDefine::ROUNDING_TYPE);

        return ['roundingTypes' => $roundingTypes];
    }
}
