<?php
namespace App\Repositories;

use App\Models\Category;
use App\Models\GoodsCategory;
use App\Enums\FlagDefine;
use App\Exceptions\database\OptimisticLockException;
use Arr;
use Auth;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * カテゴリ関連の処理をまとめたリポジトリクラス
 *
 * @package   App\Repositories
 * @version   1.0
 */
class CategoriesRepository
{
    use BaseRepository;
    use SimpleCrudRepositoryTrait;

    /**
     * 利用するModelクラスを取得する。
     */
    protected function getModel()
    {
        return Category::where([]);
    }
    /**
     * クエリーを構築する。商品一覧用
     * @param array $conditions 検索条件
     * @return query
     */
    protected function getQueryByConditions(array $param)
    {
        $query = GoodsCategory::
        select('goods.id','goods.code','goods.name','goods.volume','goods.jan_code','goods.unit_price',
            'makers.name as maker_name')
            ->where('category_code', $param['code'])
            ->join("goods", function ($join) {
                $join->on('goods_categories.goods_id', '=', "goods.id");
                $join->where('goods.is_deleted', FlagDefine::OFF);
            })
            ->leftJoin("makers", function ($join) {
                $join->on('goods.maker_id', '=', "makers.id");
                $join->where('makers.is_deleted', FlagDefine::OFF);
            })
            ;

        return $query;
    }
    /**
     * 更新する。
     * @param array $values 更新カラムの情報。[[key => value], ・・・]
     * @param array $where 更新条件。未指定の場合、全件更新される。
     * @param boolean $checkOptimistickLock 排他制御を行うか
     * @return number 更新件数
     * @exception OptimisticLockException
     */
    public function updateInsert(array $values)
    {
        $model = $this->getModel()->find($values['code']);
        if (!$model) {
            //データが無い場合はInsert
            return $this->create($values);
        } else {
            //他で更新が入っていればエラー
            if ($model->updated_at->toJson() != $values['updated_at']) {
                throw new OptimisticLockException();
            }
            $model->fill(Arr::only($values,['name', 'hierarchy', 'path', 'sequence']));
            $model->updated_by = Auth::user()->id;
            $model->save();

            return $model;
        }
    }
    /**
     * カテゴリー理削除
     * 指定されたCode以外
     */
    public function forceDelete($notInCodes) {
        $this->getModel()->whereNotIn('code', $notInCodes)->forceDelete();
    }
    /**
     * カテゴリのプルダウン用配列を返す
     *
     * @return array
     */
    public function getCategoryList(): array
    {
        $ret = [];
        $query = DB::table('categories')
            ->select('categories.code', 'categories.name', 'categories.hierarchy', 'categories.path')
            ->whereIn('categories.hierarchy', [1, 2])
            ->where('categories.is_deleted', '=', 0)
            ->orderby('categories.sequence');
        $categories = $query->get();

        $class1CodeArray = [];
        foreach ($categories as $category) {
            if ($category->hierarchy === 1) {
                $class1CodeArray[$category->code] = $category->name;
            }
        }

        $class2CodeArray = [];
        foreach ($class1CodeArray as $code => $name) {
            $class2CodeArray[$code] = $this->getCategory2List($categories, $code);
        }

        $ret['class1'] = $class1CodeArray;
        $ret['class2'] = $class2CodeArray;
        return $ret;
    }

    /**
     * 小カテゴリのプルダウン用配列を返す
     *
     * @param $categories Collection カテゴリ検索結果
     * @param $code1 string 大カテゴリコード
     * @return array
     */
    private function getCategory2List(Collection $categories, string $code1): array
    {
        $ret = [];
        $pathHead = $code1 . '~';
        foreach ($categories as $category) {
            if ($category->hierarchy === 2 && strpos($category->path, $pathHead) === 0) {
                $ret[] = array('text'=>$category->name, 'value'=>$category->code);
            }
        }
        return $ret;
    }
}
