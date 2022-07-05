<?php

namespace App\Http\Controllers\Admin;

use App\Enums\FlagDefine;
use App\Http\Controllers\Controller;
use App\Http\Controllers\SimpleCrudControllerTrait;
use App\Models\Goods;
use App\Services\Admin\GoodsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * 商品マスタメンテコントローラー
 */
class GoodsController extends Controller
{
    public static $SESSION_KEY = 'goodsCondition';

    use SimpleCrudControllerTrait;

    /**
     * コンストラクタ
     *
     * @access public
     * @param GoodsService $goodsService 商品サービス
     */
    public function __construct(GoodsService $goodsService)
    {
        $this->service = $goodsService;
    }

    public function index(Request $request)
    {
        if (session()->hasOldInput()) {
            $result['isBack'] = FlagDefine::ON;
        } else {
            $result['isBack'] = FlagDefine::OFF;
            // メニューからの時はセッション削除
            $request->session()->forget(GoodsController::$SESSION_KEY);
        }

        $result['selections'] = $this->service->getScreenSelections();

        return view($this->getIndexViewFile(), $result);
    }

    /**
     * トップページ表示
     * @return string 一覧ページのビューファイル
     */
    protected function getIndexViewFile(): string
    {
        return 'admin.goods';
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

        // 検索条件保持 form の input名 に合わせておく
        $sessionVal = [];
        foreach ($formParams as $key => $value) {
            $sessionVal['search-' . $key] = $value;
        };
        $sessionVal += ['page' => $request->get('page')];
        $request->session()->put(GoodsController::$SESSION_KEY, $sessionVal);

        return $formParams;
    }

    public function store(Request $request)
    {
        $request->id = Goods::getNextId();
        $all_request = $this->uploadImages($request);
        $result = $this->service->store($all_request);
        return response()->json($result);
    }

    public function update(Request $request, $id)
    {
        $all_request = $this->uploadImages($request);
        $result = $this->service->update($id, $all_request);
        return response()->json($result);
    }

    protected function uploadImages(Request $request)
    {
        $all_request = $request->all();
        if($request->hasFile('upload_image')) {
            $extension = $request->upload_image->getClientOriginalExtension();
            $filename = "main" . "." . $extension;
            if (config('app.goods_image_filesystem_driver') === 'local') {
                $path = $request->upload_image->storeAs('public/images/goods/' . $request->id, $filename);
                $path = str_replace('public/', '', $path);
            } else {
                $path = $request->upload_image->storeAs($request->id, $filename, ['disk' => 's3', 'visibility' => 'public']);
                $path = config('filesystems.disks.s3.bucket') . '/' . $path;
            }
            $all_request['image'] = $path;
        }

        return $all_request;
    }

    /**
     * 商品コードの存在チェック
     */
    public function codes($value, Request $request)
    {
        // $checkCode = $request->input("checkCode");
        // $excludeCode = $request->input("excludeCode");
        // if ($checkCode === $excludeCode) {
        //     return response(null, 200);
        // }

        $result = $this->service->checkDuplicateCode( $value, null);
        if ($result > 0) {
            // 既存の商品コードが存在する
            return response(null, 409);
        } else {
            // 既存の商品コードが存在しない
            return response(null, 200);
        }
    }
}
