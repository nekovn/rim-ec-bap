<?php
namespace App\Repositories;

use App\Enums\FlagDefine;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;

/**
 * 取引先関連の処理をまとめたリポジトリクラス
 *
 * @package   App\Repositories
 * @version   1.0
 */
class SuppliersRepository
{
    use BaseRepository;

    /**
     * 利用するModelクラスを取得する。
     */
    protected function getModel()
    {
        return Supplier::where([]);
    }

    /**
     * 「仕入先」としての取引先のプルダウン用配列を返す
     *
     * @return array
     */
    public function getSupplierList(): array
    {
        $ret = [];
        $query = DB::table('suppliers')
            ->select('suppliers.id', 'suppliers.name')
            ->where('suppliers.supplier_kind', '=', '1')
            ->where('suppliers.is_deleted', '<>', FlagDefine::ON)
            ->orderby('suppliers.name');
        $resultCodes = $query->get();
        foreach ($resultCodes as $result) {
            $ret += array($result->id => $result->name);
        }
        return $ret;
    }
}
