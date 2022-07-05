<?php

namespace App\Http\Controllers\Admin;

use App\Services\Admin\SagawaOutputService;
use App\Http\Controllers\Controller;

/**
 * 佐川連携ファイル出力
 *
 */
class SagawaOutputController extends Controller
{
    /**
     * コンストラクタ
     *
     * @access public
     * @param SagawaOutputService $SagawaOutputService 佐川連携ファイル出力サービス
     */
    public function __construct(SagawaOutputService $sagawaOutputService)
    {
        $this->sagawaOutputService = $sagawaOutputService;
    }
    /**
     * 画面を表示する。
     *
     * @access public
     * @return ページ
     */
    public function index()
    {
        return view('admin.sagawa-output');
    }
    /**
     * 商品マスタダウンロード処理
     *
     * @return stream CSVファイル
     */
    public function goods() {
        return $this->sagawaOutputService->downloadGoodsCsv();
    }
    /**
     * 出荷指示ダウンロード処理
     *
     * @return stream CSVファイル
     */
    public function ships() {
        $response = $this->sagawaOutputService->downloadShipsCsv();

        if ($response) {
            return $response;
        } else {
            return redirect('/admin/sagawa-output')
                    ->with('download-ships-message', \Lang::get('messages.E.nodata.shipping.instructions'));
        }
    }
}
