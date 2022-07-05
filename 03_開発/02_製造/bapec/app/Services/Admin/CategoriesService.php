<?php
namespace App\Services\Admin;

use App\Repositories\CategoriesRepository;
use App\Repositories\GoodsCategoriesRepository;
use App\Aspect\Annotation\Transactional;
use App\Helpers\Util\SystemHelper;
use App\Repositories\MakersRepository;
use App\Services\SimpleCrudServiceTrait;

use Arr;

/**
 * カテゴリサービス
 *
 * @package   App\Services
 * @version   1.0
 */
class CategoriesService
{
    use SimpleCrudServiceTrait;

    const PATH_SEPARATOR = '~';//pathのセパレーター

    /**
     * コンストラクタ
     *
     * @access public
     */
    public function __construct(
        CategoriesRepository $categoriesRepository,
        GoodsCategoriesRepository $goodsCategoriesRepository,
        MakersRepository $makersRepository
    ) {
        $this->repository = $categoriesRepository;
        $this->goodsCategoriesRepository = $goodsCategoriesRepository;
        $this->makersRepository = $makersRepository;
    }

    /**
     * 画面のコード選択肢を取得する。
     *
     * @return Collection
     */
    public function getScreenSelections()
    {
        // メーカー
        $makers = $this->makersRepository->getMakerList();

        return [
            'makers' => $makers,
            'limit' => SystemHelper::getAppSettingValue('page.pagination.display-count.selects')[array_key_last(SystemHelper::getAppSettingValue('page.pagination.display-count.selects'))],
        ];
    }

    // カテゴリ情報を取得
    public function getCategory() {
        return $this->repository->getCategory();
    }

    /**
     * jstreeに表示するためのデータを取得する
     * 商品一覧を取得する　
     */
    public function getCategoryTree($formParams=[], $pageParams) {
        $datas = $this->repository->find(['order'=>['hierarchy'=>'asc', 'sequence' => 'asc']]);
        $tree = [];
        $products = [];
        foreach ($datas as $data) {
            //商品
            $formParams['code'] = $data->code;
            $products[$data->code] = $this->find($formParams, $pageParams);
            // return ['total' => $count, 'data' => [], 'message' => $message];
            // foreach ($goods as $good) {
            //     $products[$data->code][] = ['data'=>[
            //         'code' => $good->code,
            //         'name' => $good->name,
            //         'volume' => $good->volume,
            //         'unit_price' => $good->unit_price,
            //     ]
            //     ];
            // }
            if ($data->hierarchy == 1) {
                $tree[] = [
                    'id'=>$data->code,
                    'text' => '[' . $data->code . ']：'. $data->name,
                    'name' => $data->name,
                    'parent' => '#',
                    'type' => '#',
                    'updated_at' => $data->updated_at
                ];
            } else {
                //親をみつける
                $paths = explode(self::PATH_SEPARATOR, $data->path);
                $tree[] = [
                    'id' => $data->code,
                    'text' => '[' . $data->code . ']：' . $data->name,
                    'name' => $data->name,
                    'parent' => $paths[($data->hierarchy) - 2],
                    'type' => 'category',
                    'updated_at' => $data->updated_at
                ];
            }
        }
        return [
            'data' => $tree,
            'goods'=> $products,
        ];
    }

    /**
     * データを更新する。
     *
     * @access public
     * @param number $id 主キー
     * @param array $params パラメーター
     * @return Model
     * @Transactional
     */
    public function updateCategories(array $params)
    {
        //----- categories更新
        $categories = Arr::where($params, function($value, $key) {
            return $value['table'] == 'categories';
        });
        //upsertする

        $codes = [];
        foreach ($categories as $param) {
            $codes[] = $param['code'];
            $this->repository->updateInsert($param, ['code'=>$param['code']]);
        }
        //リストに無いカテゴリを物理削除する
        $this->repository->forceDelete($codes);
        

        //----- goods_categires更新


        // throw new Exception;
    }
    /**
     * カテゴリ商品
     * データを物理削除する。
     *
     * @access public
     * @param number $id
     * @param array $params
     * @Transactional
     */
    public function deleteGoods($id, $params)
    {
        $where = ['goods_id' => $id, 'category_code' => $params['code']];
        $this->goodsCategoriesRepository->delete($where, false);
    }
    /**
     * カテゴリ商品
     * データを登録する。
     *
     * @access public
     * @param number $id
     * @param array $params
     * @Transactional
     */
    public function storeGoods($params)
    {
        foreach ($params['ids'] as $id) {
            $valus[] = [
                'category_code' => $params['code'],
                'goods_id' => $id,
            ];
        }
        $this->goodsCategoriesRepository->bulkInsert($valus);
    }

    /**
     * カテゴリ商品を取得する
     */
    public function selectInitGoodsCategories($formParams, $pageParams)
    {
        return $this->find($formParams, $pageParams);
    }
}
