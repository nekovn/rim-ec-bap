<?php
namespace App\Repositories;

use App\Enums\CodeDefine;
use App\Enums\FlagDefine;
use App\Enums\SagawaOutputDefine;
use App\Enums\StatusDefine;
use App\Models\Goods;
use DB;

/**
 * 商品管理関連の処理をまとめたリポジトリクラス
 *
 * @package   App\Repositories
 * @version   1.0
 */
class GoodsRepository
{
    use BaseRepository;
    use SimpleCrudRepositoryTrait;

    /**
     * 利用するModelクラスを取得する。
     */
    protected function getModel()
    {
        return Goods::where([]);
    }

    /**
     * クエリーを構築する。
     * @param array $param 検索条件
     * @return query
     */
    protected function getQueryByConditions(array $param)
    {
        $query = $this->getModel()->select(
            'goods.id',
            'goods.code',
            'goods.name',
            'goods.maker_id',
            'goods.is_published',
            'goods.sale_status',
            'makers.name as maker_name'
        );
        $query->selectRaw("case when goods.is_published = ".StatusDefine::KOKAI_ON." then '公開' else '非公開' end as published " );
        if (isset($param['category_code'])) {
            // カテゴリー設定　商品一覧
            $query->addSelect(
                'goods.volume'
            );
        } else {
            $query->addSelect(
                'goods.image'
            );
        }

        // メーカー名用JOIN
        $query->leftJoin('makers', function ($join) {
            $join->on('goods.maker_id', '=', 'makers.id')
            ->where('makers.is_deleted', '<>', FlagDefine::ON);
        });

        // 販売ステータス名用JOIN
        $query->leftJoinCvalueContent('goods.sale_status', CodeDefine::SALE_STATUS);

        // 商品コード
        if (isset($param['code'])) {
            $query->where('goods.code', '=', $param['code']);
        }

        // 商品名
        if (isset($param['name'])) {
            $query->where('goods.name', 'LIKE', '%' . $param['name'] . '%');
        }

        // JANコード
        if (isset($param['jan_code'])) {
            $query->where('goods.jan_code', '=', $param['jan_code']);
        }

        // 大カテゴリ
        if (isset($param['class1_code']) && !isset($param['class2_code'])) {
            $query->whereHas('categories', function($query) use ($param) {
                $query->where('categories.path', 'LIKE', $param['class1_code'] . '~%~')
                ->where('categories.is_deleted', "<>", FlagDefine::ON);
            });
        }

        // 小カテゴリ
        if (isset($param['class2_code'])) {
            $query->whereHas('categories', function($query) use ($param) {
                $query->where('categories.code', '=', $param['class2_code'])
                    ->where('categories.is_deleted', "<>", FlagDefine::ON);
            });
        }

        // メーカー
        if (isset($param['maker_id'])) {
            $query->where('goods.maker_id', '=', $param['maker_id']);
        }

        // 公開状況
        if (isset($param['is_published'])) {
            $query->where('goods.is_published', '=', $param['is_published']);
        }

        // 販売ステータス
        if (isset($param['sale_status'])) {
            $query->where('goods.sale_status', '=', $param['sale_status']);
        }

        // カテゴリ商品にある商品除外（カテゴリ設定商品一覧用）
        if (isset($param['category_code'])) {
            $query->whereNotExists(function($query1) use ($param){
                $query1->select(DB::raw(1))->from('goods_categories')
                ->where('goods_categories.category_code', '=', $param['category_code'])
                ->whereRaw('goods.id = goods_categories.goods_id');
            });

        }

        return $query;
    }

    /**
     * 商品コードの存在チェック
     *
     * @param $checkCode 存在チェックする商品コード
     * @param $excludeCode 検索で除外する商品コード
     * @return $checkCode と同じ商品コードの数
     */
    public function checkDuplicateCode($checkCode, $excludeCode)
    {
        $query = $this->getModel()
            ->select('goods.code')
            ->where('goods.code', '=', $checkCode);
        if (!empty($excludeCode) && $checkCode !== $excludeCode) {
            $query->where('goods.code', '<>', $excludeCode);
        }

        return $query->count();
    }

    /**
     * 佐川連携ファイル出力用の商品マスタ情報を取得する
     *
     * @return 取得結果
     */
    public function getGoodsMasterForSagawa()
    {
        $query = "
            select
                '" . SagawaOutputDefine::OUTPUT_VALUE_CUSTOMER_ID . "' as customer_id
                , goods.code as goods_code
                , goods.name as goods_name
                , '' as goods_short_name
                , goods.jan_code as goods_barcode
                , '' as goods_categories
                , '' as color_name
                , goods.volume as goods_size_name
                , '' as goods_selling_price
                , '' as goods_cost
                , '' as supplier_code
                , '' as supplier_product_code
                , '' as goods_input_number
                , '' as goods_talent
                , '' as goods_weight
                , '' as goods_width
                , '' as goods_depth
                , '' as goods_height
                , '" . SagawaOutputDefine::OUTPUT_VALUE_PACKING_SPLIT_CATEGORY . "' as packing_split_category
                , '' as number_of_packing_boxes_by_compulsory
                , '' as lot_management_flag
                , '' as serial_management_flag
                , '' as preliminary_item_3
                , '' as preliminary_item_4
                , '' as preliminary_item_5
            from
                goods
            where
                goods.is_deleted = " . FlagDefine::OFF;

        return DB::select($query);
    }
}
