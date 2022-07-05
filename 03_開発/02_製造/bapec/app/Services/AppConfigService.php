<?php
namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use App\Helpers\Util\SystemHelper;
use App\Repositories\CodesRepository;

/**
 * システム設定関連の処理をまとめたサービスクラス
 *
 * @package   App\Services
 * @version   1.0
 */
class AppConfigService
{
    /**
     * コンストラクタ
     *
     * @access public
     * @param CodesRepository $codesRepository コードマスタリポジトリ
     */
    public function __construct(
        CodesRepository $codesRepository
    ) {
        $this->codesRepository = $codesRepository;
    }
    /**
     * 設定データを取得する。
     *
     * @access public
     * @return array
     */
    public function getData()
    {
        $cacheName = SystemHelper::getAppSettingsName();
        return Cache::rememberForever($cacheName, function () {
            return $this->loadConfig();
        });
    }
    /**
     * 各種設定を読み込む。
     *
     * @return array 設定情報
     */
    private function loadConfig() {
        // システム設定
        $config['config'] = $this->getSystemConfig();
        // メッセージ
        $config['i18n'] = $this->getMessages();
        // 定数
        $config['define'] = $this->getDefines();
        // コード値 SE_ONLYのみ
        $config['code'] = $this->getCodes();

        return $config;
    }
    /**
     * システム設定の定義体を取得する。
     *
     * @return array
     */
    private function getSystemConfig(): array
    {
        return [
            'app_url'        => config('app.url'),
            'timezone'       => config('app.timezone'),
            'locale'         => config('app.locale'),
            'fallbackLocale' => config('app.fallback_locale'),
            'fakerLocale'    => config('app.faker_locale'),
            'timeout'        => config('session.lifetime'),
            'page' => [
                'validation' => SystemHelper::getAppSettingValue('page.validation'),
                'supportParts' => [
                    'date'     => SystemHelper::getAppSettingValue('page.support-parts.date'),
                    'calendar' => SystemHelper::getAppSettingValue('page.support-parts.calendar'),
                ]
            ],
            'simpleTemplate' => SystemHelper::getAppSettingValue('simple-template')
        ];
    }
    /**
     * メッセージの定義帯を取得する。
     *
     * @return array
     */
    private function getMessages(): array
    {
        $lang = config('app.locale');
        $files = glob(resource_path('lang/' . $lang . '/*.php'));

        $messages = [];
        foreach ($files as $file) {
            $name = basename($file, '.php');
            $messages[$name] = require $file;
        }
        return $messages;
    }
    /**
     * 定数の定義帯を取得する。
     *
     * @return array
     */
    private function getDefines(): array
    {
        $files = glob(app_path('Enums/*Define.php'));

        $defines = [];
        foreach ($files as $file) {
            $name = basename($file, '.php');
            $class = new \ReflectionClass("\\App\\Enums\\".$name);
            if (!$class->hasMethod('getKeyValues')) {
                continue;
            }
            $method = $class->getMethod('getKeyValues');
            $keyValues = $method->invoke($class);
            $defines[$name]['keyValues'] = $keyValues;
            foreach ($keyValues as $key => $value) {
                $defines[$name][$key] = $value;
            }
            $method = $class->getMethod('getMethods');
            $defines[$name] = array_merge($defines[$name], $method->invoke($class));
        }
        return $defines;
    }
    /**
     * コード値を取得する。
     *
     * @return array
     */
    public function getCodes(): array
    {
        return Cache::rememberForever('app-codes', function () {
            $codes = [];
            $codeRows = $this->codesRepository->findByActiveWithCodeValues();
            foreach ($codeRows as $code) {
                if (!Arr::has($codes, $code->code)) {
                    $codes[$code->code] = ['name' => $code->code_name];
                }
                $attr = [
                    'attr1_description' => $code->attr_1_description,
                    'attr1' => $code->attr_1,
                    'attr2_description' => $code->attr_2_description,
                    'attr2' => $code->attr_2,
                    'attr3_description' => $code->attr_3_description,
                    'attr3' => $code->attr_3,
                    'attr4_description' => $code->attr_4_description,
                    'attr4' => $code->attr_4,
                    'attr5_description' => $code->attr_5_description,
                    'attr5' => $code->attr_5,
                ];
                // $codes[$code->code][] = [
                //                                                         'value' => $code->value,
                //                                                         'attr'  => $attr
                //                                                        ];
                $codes[$code->code]['codes'][$code->key] = $code->value;
                $codes[$code->code]['attrs'][$code->key] = $attr;
            }
            return $codes;
        });
    }
}
