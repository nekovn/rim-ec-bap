<?php
namespace App\Repositories;

use App\Enums\FlagDefine;
use App\Models\Maker;
use Illuminate\Support\Facades\DB;

/**
 * メーカー関連の処理をまとめたリポジトリクラス
 *
 * @package   App\Repositories
 * @version   1.0
 */
class MakersRepository
{
    use BaseRepository;

    /**
     * 利用するModelクラスを取得する。
     */
    protected function getModel()
    {
        return Maker::where([]);
    }

    /**
     * メーカーのプルダウン用配列を返す
     *
     * @return array
     */
    public function getMakerList(): array
    {
        $ret = [];
        $resultCodes = [];
        $query = DB::table('makers')
            ->select('makers.id', 'makers.name')
            ->where('makers.is_deleted', '<>', FlagDefine::ON)
            ->orderby('makers.name_kana');
        $resultCodes = $query->get();
        foreach ($resultCodes as $result) {
            $ret += array($result->id => $result->name);
        }
        return $ret;
    }
}
