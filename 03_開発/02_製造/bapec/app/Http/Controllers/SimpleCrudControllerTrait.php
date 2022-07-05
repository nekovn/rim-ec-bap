<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Enums\SortOrderDefine;
use App\Helpers\Util\SystemHelper;

/**
 * シンプルなCRUDを処理のリクエストを受け付けるコントローラートレイト
 *
 * @category  システム共通
 * @package   App\Http\Controllers\Template
 * @copyright 2020 elseif.jp All Rights Reserved.
 * @version   1.0
 */
trait SimpleCrudControllerTrait
{
    /**
     * 一覧ページのViewを指定する。
     * @return 一覧ページのビューファイル
     */
    abstract protected function getIndexViewFile();
    /**
     * 一覧を表示する。
     *
     * @access public
     * @param Request $request リクエスト
     * @return 一覧ページ
     */
    public function index(Request $request)
    {
        $result['selections'] = $this->service->getScreenSelections();

        return view($this->getIndexViewFile(), $result);
    }
    /**
     * 一覧を検索する。
     *
     * @access public
     * @param Request $request リクエスト
     * @param any $param パラメータ
     * @return array レスポンス
     */
    public function search(Request $request, $param = null)
    {
        $result = $this->doSearch($request, $param);
        return response()->json($result);
    }
    /**
     * データを取得する。
     *
     * @access public
     * @param number $id 主キー
     * @return json
     */
    public function edit($id)
    {
        $result = $this->service->getData($id);
        return response()->json($result);
    }
    /**
     * データを登録する。
     *
     * @access public
     * @param Request $request リクエスト
     * @return json
     */
    public function store(Request $request)
    {
        $result = $this->service->store($request->all());
        return response()->json($result);
    }
    /**
     * データを更新する。
     *
     * @access public
     * @param Request $request リクエスト
     * @param number $id 主キー
     * @return json
     */
    public function update(Request $request, $id)
    {
        $result = $this->service->update($id, $request->all());
        return response()->json($result);
    }
    /**
     * データを削除する。
     *
     * @access public
     * @param Request $request リクエスト
     * @param number $id 主キー
     * @return json
     */
    public function delete(Request $request, $id)
    {
        $this->service->delete($id, $request->all());
        return response()->json([]);
    }

    /**
     * 検索処理のパラメーターを返す。
     *
     * @param Request $request
     * @param any $param パラメータ
     * @return array リクエストパラメータ
     */
    protected function getSearchParameter(Request $request, $param = null)
    {
        return $request->get('form');
    }
    /**
     * 検索処理を行う。
     *
     * @access private
     * @param Request $request リクエスト
     * @param any $param パラメータ
     * @return array
     */
    private function doSearch(Request $request, $param = null)
    {
        $formParams = $this->getSearchParameter($request, $param = null);
        // 取得件数の指定がシステム設定にある場合、取得件数の上書きを行う。
        $limit = SystemHelper::getAppSettingValue('page.search-limit');
        if ($limit > 0) {
            $pageParams['count'] = $limit;
        } elseif (SystemHelper::getAppSettingValue('page.pagination')) {
            $limit = SystemHelper::getAppSettingValue('page.pagination.display-count.default');
            $limit = $request->input('page.count', $limit);
        }

        $pageParams = [
            'count'     => $limit,
            'page'      => $request->input('page.page', 1),
            'sortItem'  => $request->input('page.sortItem', 'sortkey'),
            'sortOrder' => $request->input('page.sortOrder', SortOrderDefine::ASC)
        ];

        $resultData = $this->service->find($formParams, $pageParams);

        return $resultData;
    }
}
