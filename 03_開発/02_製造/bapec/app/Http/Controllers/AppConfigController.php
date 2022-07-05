<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;

use App\Http\Controllers\Controller;
use App\Services\AppConfigService;

/**
 * システム設定のリクエストを受付、実行結果を返す。
 */
class AppConfigController extends Controller
{
    /**
     * コンストラクタ
     *
     * @access public
     * @param AppConfigService コンフィグサービス
     */
    public function __construct(AppConfigService $appConfigService)
    {
        $this->service = $appConfigService;
    }
    /**
     * システム設定情報をwindowオブジェクトに設定する。
     */
    public function index()
    {
        $config = $this->service->getData();

        header('Content-Type: text/javascript');
        echo('window.fw = ' . json_encode($config) . ';');
        exit();
    }
}
