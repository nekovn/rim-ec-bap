<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\SimpleCrudControllerTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\CodesEntryRequest;
use App\Services\CodesService;

/**
 * コードマスタ画面のリクエストを受付、実行結果を返す。
 *
 * @category  管理サイト
 * @package   App\Http\Controllers
 * @version   1.0
 */
class CodesController extends Controller
{
    use SimpleCrudControllerTrait {
        SimpleCrudControllerTrait::store as storeTrait;
        SimpleCrudControllerTrait::update as updateTrait;
    }
    
    /**
     * コンストラクタ
     *
     * @access public
     * @param CodeService $codeService コードサービス
     */
    public function __construct(CodesService $codeService) {
        $this->service = $codeService;
    }
    /**
     * トップページ表示
     * @return 一覧ページのビューファイル
     */
    protected function getIndexViewFile()
    {
        return 'admin.codes';
    }
    /**
     * 検索処理のパラメーターを返す。
     *
     * @param Request $request
     * @return void
     */
    protected function getSearchParameter(Request $request)
    {
        $formParams = $request->get('form');
        $formParams['code'] = $formParams['select-code'];
        return $formParams;
    }
    /**
     * データを登録する。
     *
     * @access public
     * @param CodeEntryRequest $request リクエスト
     * @return json
     */
    public function store(CodesEntryRequest $request)
    {
        return $this->storeTrait($request);
    }
    /**
     * データを更新する。
     *
     * @access public
     * @param CodeEntryRequest $request リクエスト
     * @param number $id 主キー
     * @return json
     */
    public function update(CodesEntryRequest $request, $id)
    {
        // codeのパラメータは現状編集画面からは変更させず、CodesEntryRequest内でしか使用ないが、除外処理は入れないものとする
        return $this->updateTrait($request, $id);
    }
}
