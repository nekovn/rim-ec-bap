<?php
namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

use App\Enums\SortOrderDefine;
use App\Models\Zip;

/**
 * 郵便番号マスタ関連の処理をまとめたリポジトリクラス
 *
 * @package   App\Repositories
 * @version   1.0
 */
class ZipsRepository
{
    use BaseRepository;

    /**
     * 利用するModelクラスを取得する。
     */
    protected function getModel(): Model
    {
        return new Zip();
    }
    /**
     * 郵便番号を基に住所を検索する
     *
     * @param string $zipCode
     * @return 取得結果
     */
    public function findByZipCode($zipCode)
    {
        return Zip::select(['*', 'zip_cd as value'])
                  ->selectRaw("concat('〒',zip_cd,'：',pref_name,municipality_name,town_name) as label")
                  ->where('zip_cd', 'like', $zipCode . '%')
                  ->orderBy('zip_cd', SortOrderDefine::ASC)
                  ->get();
    }
    /**
     * 住所を基に郵便番号を検索する
     *
     * @param string $addr
     * @return 取得結果
     */
    public function findByAddr($addr)
    {
        return Zip::select('*')
                  ->selectRaw("concat(pref_name,municipality_name,town_name) as value")
                  ->selectRaw("concat('〒',zip_cd,'：',pref_name,municipality_name,town_name) as label")
                  ->whereRaw("concat(pref_name,municipality_name,town_name) like '%".$addr."%'")
                  ->orderBy('zip_cd', SortOrderDefine::ASC)
                  ->get();
    }
}
