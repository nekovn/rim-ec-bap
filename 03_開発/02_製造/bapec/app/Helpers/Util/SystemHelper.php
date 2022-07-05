<?php
namespace App\Helpers\Util;

use Lang;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

use App\Services\AppConfigService;

/**
 * システム共通ヘルパークラス
 *
 * @category  システム共通
 * @package   App\Helpers\Util
 * @version   1.0
 */
class SystemHelper
{
    /**
     * 有効なGuard名を取得する。
     * @return string guard名
     */
    public static function getActiveGuardName() {
        return Auth::check() ? Auth::user()->activeGuard() : '';
    }
    /**
     * システム設定ファイル名を取得する。
     *
     * @return string システム設定ファイル名
     */
    public static function getAppSettingsName() {
        $guard = self::getActiveGuardName();
        $configName = "app-config";
        if ($guard) {
            $configName .= "-{$guard}";
        }
        return $configName;
    }
    /**
     * システム設定値を取得する。
     *
     * @param string 設定ファイルキー
     * @param any デフォルト値
     * @return 設定値
     */
    public static function getAppSettingValue($key)
    {
        $appSettingName = 'app-settings';
        if (Auth::check()) {
            $guard = self::getActiveGuardName();
            $appSettingName .= "-{$guard}";
        }
        return config("{$appSettingName}.{$key}");
    }

    /**
     * メッセージに動的パラメータを適用した文字列を取得する。
     *
     * @param string $messageCd　メッセージコード
     * @param array $parameters　バインドパラメータ
     * @return バインドパラメータを適用したメッセージ
     */
    public static function getMessage(string $messgeCd, array $parameters = []): string
    {
        return StringHelper::bindParameter(Lang::get($messgeCd), $parameters);
    }
    /**
     * 件数超過メッセージを取得する。
     *
     * @param number $target 検査対象値
     * @return 件数超過メッセージ
     */
    public static function getCountLimitOverMessage($target): string
    {
        $limit = self::getAppSettingValue('page.search-limit');
        if ($limit > 0 && $target > $limit) {
            return self::getMessage('messages.W.search.count.limit.over', ['count' => $limit]);
        }

        return '';
    }
    /**
     * コードマスタのコードに対するコード値定義を返す。
     *
     * @param string $code コードマスタのcode
     * @param string $key コードマスタのkey
     * @return string コード値定義
     */
    public static function getCodeValue($code, $key)
    {
        $codes = self::getCodes($code);
        // return data_get($codes, "{$code}.{$key}", null);

        if (array_key_exists($key, $codes)) {
            return $codes[$key];
        }
        return "";
    }
    /**
     * コードマスタのコードに対するコード値定義を返す。
     *
     * @param string $code コードマスタのcode
     * @return array コード値定義の配列
     */
    public static function getCodes($code = null)
    {
        $appConfigService = app()->make('AppConfigService');
        $codes = $appConfigService->getCodes();
        if (!Arr::has($codes, $code)) {
            return $codes;
        }

        return $codes[$code]['codes'];
    }
    /**
     * コードマスタのコードに対する属性値の定義を返す。
     *
     * @param string $code コードマスタのcode
     * @return array 属性値定義の配列
     */
    public static function getCodeAttrs($code)
    {
        $codes = self::getCodes();
        return data_get($codes, "{$code}.attrs", null);
    }

    // /**
    //  * コードマスタのキーに対するコード値定義を返す。
    //  *
    //  * @param string $pgKey コードマスタのpg_key
    //  * @return string コード値定義
    //  */
    // public static function getCodeValue($pgKey)
    // {
    //     $codes = self::getCodes();
    //     return data_get($codes, "{$pgKey}.value", null);
    // }
    // /**
    //  * コードマスタのキーに対するコード値定義を返す。
    //  *
    //  * @param string $pgKey コードマスタのpg_key
    //  * @return array コード値定義の配列
    //  */
    // public static function getCodes($pgKey = null)
    // {
    //     $appConfigService = app()->make('AppConfigService');
    //     $codes = $appConfigService->getCodes();
    //     if (!Arr::has($codes, $pgKey)) {
    //         return $codes;
    //     }

    //     return $codes[$pgKey]['codes'];
    // }
    // /**
    //  * コードマスタのキーに対する属性値の定義を返す。
    //  *
    //  * @param string $pgKey コードマスタのpg_key
    //  * @return array 属性値定義の配列
    //  */
    // public static function getCodeAttrs($pgKey)
    // {
    //     $codes = self::getCodes();
    //     return data_get($codes, "{$pgKey}.attrs", null);
    // }
}
