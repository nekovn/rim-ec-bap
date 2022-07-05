<?php

namespace App\Services\Admin;

use App\Repositories\GoodsImagesRepository;

/**
 * 商品画像管理関連の処理をまとめたサービスクラス
 *
 * @category  商品画像管理
 * @package   App\Services
 * @version   1.0
 */
class GoodsImagesService
{
    /**
     * @var GoodsImagesRepository
     */
    private $repository;

    /**
     * コンストラクタ
     *
     * @access public
     * @param GoodsImagesRepository $goodsImagesRepository
     */
    public function __construct(GoodsImagesRepository $goodsImagesRepository)
    {
        $this->repository = $goodsImagesRepository;
    }

    /**
     * 商品ID を指定して 商品画像 を 表示順(昇順) で取得する。
     *
     * @param $goods_id
     * @return array
     */
    public function findByGoodsId($goods_id): array
    {
        $rows = $this->repository->findByGoodsId($goods_id);
        return ['data' => $rows];
    }

    /**
     * 商品ID を指定して 商品画像 を登録する。
     *
     * @param $all_request
     * @param $goods_id
     */
    public function entryByGoodsId($all_request, $goods_id)
    {
        // $goods_id の商品画像レコードをすべて削除する
        $this->repository->deleteByGoodsId($goods_id);

        // 商品画像の数分、商品画像レコードを登録する
        $maxId = (int)$all_request['maxId'];
        for ($id = 1; $id <= $maxId; $id++) {
            $display_order = 'display_order_' . $id;
            $image = 'image_' . $id;

            if (isset($all_request[$display_order]) && isset($all_request[$image])) {
                $this->repository->create($goods_id, $all_request[$display_order], $all_request[$image]);
            }
        }
    }
}
