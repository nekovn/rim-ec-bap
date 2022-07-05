<?php
namespace App\Repositories;

use App\Exceptions\database\OptimisticLockException;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

/**
 * ユーザ管理関連の処理をまとめたリポジトリクラス
 *
 * @package   App\Repositories
 * @version   1.0
 */
class ProductsRepository
{
    use BaseRepository;
    use SimpleCrudRepositoryTrait;

    /**
     * 利用するModelクラスを取得する。
     */
    protected function getModel()
    {
        return Product::where([]);
    }

    /**
     * クエリーを構築する。
     * @param array $conditions 検索条件
     * @return query
     */
    protected function getQueryByConditions(array $param)
    {
        // 商品テーブル
        $query = Product::leftjoin('categories', 'products.category_code', '=', 'categories.category_code')
            ->selectRaw('products.*, ifnull(categories.class1_name,\'\') class1_name, ifnull(categories.class2_name,\'\') class2_name, \'\' category');

        // 商品コード
        if (isset($param['code'])) {
            $query->where('products.code', 'LIKE', $param['code'] . '%');
        }
        // 商品名
        if (isset($param['name'])) {
            $query->where(function($query) use($param){
                $query->where('products.name', 'LIKE', '%'. $param['name'] . '%')
                ->orWhere('products.name_kana', 'LIKE', '%'. $param['name'] . '%');
            });
        }
        // JANコード
        if (isset($param['jan_code'])) {
            $query->where('products.jan_code', 'LIKE', $param['jan_code'] . '%');
        }
        // 小カテゴリコード
        if (isset($param['class1_code'])) {
            $query->where('categories.class1_code', '=', $param['class1_code']);
        }
        // 細カテゴリコード
        if (isset($param['class2_code'])) {
            $query->where('categories.class2_code', '=', $param['class2_code']);
        }

        // メーカー名
        if (isset($param['brand_name'])) {
            $query->where('products.brand_name', 'LIKE', '%'. $param['brand_name'] . '%');
        }
        // 定番区分
        if (isset($param['class_div']) && $param['class_div'] != 0) {
            $query->where('products.class_div', '=', $param['class_div']);
        }

        // 画面更新日付
        if ((isset($param['update_from']))) {
            $updateFrom = (new Carbon($param['update_from']))->toDateTimeString();
            $query->where('products.edited_at', '>=', $updateFrom);
        }
        if ((isset($param['update_to']))) {
            $updateTo = (new Carbon($param['update_to'] .' 23:59:59'))->toDateTimeString();
            $nextDate = (new Carbon($param['update_to']))->addDay()->toDateTimeString();
            $query->where(function($query) use ($updateTo, $nextDate) {
                $query
                    ->where('products.edited_at', '<=', $updateTo)
                    ->orWhere('products.edited_at', '<', $nextDate);
            });
        }
        // 未処理
        if (isset($param['unedited']) && $param['unedited'] != 0) {
            $query->where('products.unedited', '=', $param['unedited']);
        }

        return $query;
    }

    /**
     * 登録する。
     *
     * @param array $values 登録対象のカラム
     * @return model
     */
    public function create(array $values)
    {
        $values['updated_by'] = Auth::user()->id;
        return $this->getModel()->create($values);
    }

    /**
     * 更新する。
     * @param array $values 更新カラムの情報。[[key => value], ・・・]
     * @param array $where 更新条件。未指定の場合、全件更新される。
     * @param boolean $checkOptimistickLock 排他制御を行うか
     * @return number 更新件数
     * @exception OptimisticLockException
     */
    public function update(array $values, array $where = [], $checkOptimistickLock = true)
    {
        $values['updated_by'] = Auth::user()->id;
        $values['edited_at'] = date("Y-m-d H:i:s");

        // 排他制御しない
        if (!$checkOptimistickLock) {
            unset($where['updated_at']);
        }
        $model = $this->findByPkey($where);
        if (!$model) {
            throw new OptimisticLockException();
        }

        $model->fill($values);
        $model->unedited = 0;
        $model->save();

        return $model;
    }

    public function updateOrCreate($key, $values)
    {
        Product::updateOrCreate($key, $values);
    }

    public function getProducts($key)
    {
        return Product::where($key)->first();;
    }

    /**
     * 商品id情報を取得
     *
     * @param array $codes 商品コード
     * @return array 該当データのリスト
     */
    public function getProductIds($codes)
    {
        $query = DB::table('products')
        ->select('products.id')
        ->whereIn('products.code', $codes);
        return $query->get();
    }

    /**
     * 商品一覧情報を取得
     *
     * @param array $param 検索条件
     * @return array 該当データのリスト
     */
    public function getProductList($param)
    {
        $query = Product::leftjoin('categories', 'products.category_code', '=', 'categories.category_code')
        ->selectRaw('products.*, ifnull(categories.class2_name,\'\') class2_name');

        // キーワード（商品名、カナ）、商品コード、JANコード、メーカー名
        if (isset($param['keyword'])) {
            for ($i=0; count($param['keyword']) > $i; $i++) {
                $query->whereRaw('CONCAT(LCASE(products.name), LCASE(ifnull(products.name_kana,"")), LCASE(products.code), LCASE(ifnull(products.jan_code,"")), LCASE(ifnull(products.brand_name,""))) '
                . ' LIKE CONCAT(\'%\', ?,\'%\')', [$this->escapeLikeQuery($param['keyword'][$i])]);
            }
        }

        // カテゴリコード
        if (isset($param['category_code'])) {
            $query->where('categories.category_code', '=', $param['category_code']);
        }
        // 小カテゴリコード
        if (isset($param['class1_code'])) {
            $query->where('categories.class1_code', '=', $param['class1_code']);
        }
        // 細カテゴリコード
        if (isset($param['class2_code'])) {
            $query->where('categories.class2_code', '=', $param['class2_code']);
        }

        // ソート
        if ((isset($param['sort']) && $param['sort'] == 'code')) {
            // code
            $query->orderby('products.code', 'asc');
        } else if (isset($param['sort']) && $param['sort'] == 'new') {
            // new
            $query->orderby('products.updated_at', 'desc');
        } else if (isset($param['sort']) && $param['sort'] == 'maker') {
            // maker
            $query->orderby('products.brand_name', 'asc');
        } else {
            $query->orderBy('products.class_div', 'asc')->orderBy('products.code', 'asc');
        }

        return $query->paginate(20);
    }
}
