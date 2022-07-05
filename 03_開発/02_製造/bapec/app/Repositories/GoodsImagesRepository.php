<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use App\Models\GoodsImage;

/**
 * 商品画像管理関連の処理をまとめたリポジトリクラス
 *
 * @package   App\Repositories
 * @version   1.0
 */
class GoodsImagesRepository
{
    /**
     * 商品ID を指定して 商品画像 を 表示順(昇順) で取得する。
     *
     * @param $goods_id
     * @return Builder[]|Collection
     */
    public function findByGoodsId($goods_id)
    {
        return GoodsImage::query()
            ->where('goods_id', '=', $goods_id)
            ->orderBy('display_order')
            ->get();
    }

    /**
     * 商品画像 を登録する。
     *
     * @param $goods_id
     * @param $display_order
     * @param $image
     */
    public function create($goods_id, $display_order, $image)
    {
        $goodsImage = new GoodsImage;
        $goodsImage->goods_id = $goods_id;
        $goodsImage->display_order = $display_order;
        $goodsImage->image = $image;
        $goodsImage->created_by = Auth::user()->id;
        $goodsImage->updated_by = Auth::user()->id;
        $goodsImage->save();
    }

    /**
     * 商品ID を指定して 商品画像 を削除する。
     *
     * @param $goods_id
     */
    public function deleteByGoodsId($goods_id)
    {
        GoodsImage::query()
            ->where('goods_id', '=', $goods_id)
            ->forceDelete();
    }
}
