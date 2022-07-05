<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Admin\CategoriesService;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * カテゴリ設定
 */
class CategoriesController extends Controller
{
    /**
     * コンストラクタ
     *
     * @access public
     * @param CategoriesService $categoriesService コードサービス
     */
    public function __construct(CategoriesService $categoriesService)
    {
        $this->service = $categoriesService;
    }
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

        return view('admin.categories', $result);
    }

    /**
     * カテゴリツリー検索
     */
    public function search(Request $request) {
        $result = $this->doSearch($request);
        return response()->json($result);
    }
    /**
     * カテゴリツリー検索処理を行う。
     *
     * @access private
     * @param Request $request リクエスト
     * @param any $param パラメータ
     * @return array
     */
    private function doSearch(Request $request, $param = null)
    {
        $formParams = $request->get('form');
        $pageParams = $request->get('page');

        if ($request->input('form.code')) {
            $result = $this->service->selectInitGoodsCategories($formParams, $pageParams);
        } else {
            $result = $this->service->getCategoryTree($formParams, $pageParams);
        }
        return ['type'=> $request->input('form.code') ? 'grid' : 'tree'
            , 'content'=>$result
        ];
    }
    /**
     * カテゴリデータを更新する。
     *
     * @access public
     * @param Request $request リクエスト
     * @param number $id 主キー
     * @return json
     */
    public function update( Request $request)
    {
        //カテゴリー更新
        $this->service->updateCategories($request->all());

        $result = $this->service->getCategoryTree([], []);
        $returnData = [
            'type' => 'tree',
            'content' => $result
        ];
        return response()->json($returnData);
       
        return new NotFoundHttpException();
    }
    /**
     * カテゴリ商品データを削除する。
     *
     * @access public
     * @param Request $request リクエスト
     * @param number $id 主キー
     * @return json
     */
    public function deleteGoods(Request $request, $id)
    {
        $this->service->deleteGoods($id, $request->all());

        $result = $this->service->selectInitGoodsCategories($request->get('form'), $request->get('page'));

        return response()->json($result);
    }

    /**
     * カテゴリ商品データを登録する。
     *
     * @access public
     * @
     * @param array $params パラメーター
     * @return Model
     */
    public function storeGoods(Request $request)
    {
        $this->service->storeGoods($request->all());

        $result = $this->service->selectInitGoodsCategories($request->get('form'), $request->get('page'));

        return response()->json($result);
    }
}
