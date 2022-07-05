<?php
namespace App\Repositories;

use Illuminate\Support\Facades\Auth;

use App\Enums\FlagDefine;
use App\Enums\SortOrderDefine;
use App\Helpers\Util\SystemHelper;
use App\Models\Code;

/**
 * コードマスタ関連の処理をまとめたリポジトリクラス
 *
 * @package   App\Repositories
 * @version   1.0
 */
class CodesRepository
{
    /**
     * 利用するModelクラスを取得する。
     */
    protected function getModel()
    {
        return Code::where([]);
    }

    /**
     * 有効なデータをコード値マスタを含めて全て取得する。
     *
     * @return array Code
     */
    public function findByActiveWithCodeValues()
    {
        return Code::select([
                    'codes.pg_key as code_pg_key',
                    'codes.code_name',
                    'code_values.code',
                    'code_values.key',
                    'code_values.value',
                    'code_values.description',
                    'code_values.attr_1_description',
                    'code_values.attr_1',
                    'code_values.attr_2_description',
                    'code_values.attr_2',
                    'code_values.attr_3_description',
                    'code_values.attr_3',
                    'code_values.attr_4_description',
                    'code_values.attr_4',
                    'code_values.attr_5_description',
                    'code_values.attr_5',
                ])
                ->join('code_values', function ($query) {
                    $query->on('code_values.code', 'codes.code');
                    if (SystemHelper::getAppSettingValue('entity.is_deleted') === 'flag') {
                        $query->where('code_values.is_deleted', FlagDefine::OFF);
                    } else {
                        $query->whereNull('code_values.deleted_at');
                    }
                    $query->where('se_only', FlagDefine::ON);
                })
                ->orderBy('code_values.sequence', SortOrderDefine::ASC)
                ->get();
    }
    
    /**
     * 画面の選択肢を取得する。
     *
     * @return Collection
     */
    public function getScreenSelections()
    {
		$query = Code::selectRaw('code, CONCAT(code, "：", code_name) as code_name')->where('se_only', 0)->orderByRaw('CAST(code as SIGNED)');
        return $query->get();
    }
}
