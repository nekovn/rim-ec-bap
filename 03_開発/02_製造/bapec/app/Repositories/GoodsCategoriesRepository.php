<?php
namespace App\Repositories;

use App\Models\GoodsCategory;


/**
 * カテゴリ商品の処理をまとめたリポジトリクラス
 *
 * @package   App\Repositories
 * @version   1.0
 */
class GoodsCategoriesRepository
{
    use BaseRepository;

    /**
     * 利用するModelクラスを取得する。
     */
    protected function getModel()
    {
        return GoodsCategory::where([]);
    }
    /**
     * 複数件登録する。
     *
     * @param array $values 登録対象のカラム
     * @return model
     */
    public function bulkInsert(array $values)
    {
        $model = $this->getModel();
        return $model->insert($values);
    }
}