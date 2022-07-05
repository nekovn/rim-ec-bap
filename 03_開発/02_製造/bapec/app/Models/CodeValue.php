<?php

namespace App\Models;

//use App\Helpers\Util\SystemHelper;
use App\Enums\CodeDefine;
use App\Enums\CodeValueDefine;
use App\Helpers\Util\SystemHelper;
use Illuminate\Database\Eloquent\Model;

/**
 * コード値マスタ　Model
 */
class CodeValue extends Model
{
    use BaseTrait;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $hidden = ['created_at', 'updated_at'];

    protected $dates = ['deleted_at'];


    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        self::creating(function($codeValue) {
            $key = self::getNextKey($codeValue->code);
            $codeValue->key = $key + 1;
        });
    }

    /**
     * キーの最大値を取得
     * @code  コード
     * @return キーの最大値
     */
    public static function getNextKey($code)
    {
        $max = CodeValue::where('code', $code)->max('key');
        return $max;
    }

    /**
     * コード値を取得
     *
     * @return  コード値
     */
    public function getCodeValue($code)
    {
        return CodeValue::where('code','=',$code)
                            ->get();
    }

    /**
     * 都道府県名を取得
     * @key  都道府県コード
     * @return 都道府県名
     */
    public function getPrefName($key)
    {
//        $tmp_code = SystemHelper::getCodes(CodeDefine::PREF_CD);
        $tmp_code = '101';

        return CodeValue::SELECT('value')
                    ->where('code', '=', $tmp_code)
                    ->where('key','=', $key)
                    ->first(); 
    }

    /**
     * お届け時間を取得
     * @param $query
     * @param 配送時間帯コード
     * @return CodeValue
     */

    public function getDeliveryTime($query, $deliveryTime)
    {
        return $query->where('code', $deliveryTime)->orderBy('sequence')->get(); //orderBy('sequence', 'asc');
    }

    /**
     * お届け時間を取得
     * @param $query
     * @param  配送時間帯コード
     * @param  配送時間帯キー
     * @return CodeValue
     */

    public function getDeliveryTimeByKey($query, $code, $key)
    {
        return $query->where([['code', "=", $code], [ 'key', '=', $key]])->first();
    }
}
