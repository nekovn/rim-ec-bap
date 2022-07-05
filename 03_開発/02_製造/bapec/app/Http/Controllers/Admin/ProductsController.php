<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\SimpleCrudControllerTrait;
use App\Services\Sandbox\ProductsService;

class ProductsController extends Controller
{
    use SimpleCrudControllerTrait {
        SimpleCrudControllerTrait::store  as storeTrait;
        SimpleCrudControllerTrait::update as updateTrait;
        SimpleCrudControllerTrait::delete as deleteTrait;
        SimpleCrudControllerTrait::search  as searchTrait;
        SimpleCrudControllerTrait::index  as indexTrait;
    }
    /**
     * コンストラクタ
     *store
     * @access public
     * @param ProductsService $productsService ユーザサービス
     */
    public function __construct(ProductsService $productsService)
    {
        $this->service = $productsService;
    }

    public function index(Request $request, $from_dashboard = false)
    {
        if($from_dashboard) {
        }

        $result['selections'] = $this->service->getScreenSelections();
        $result['from_dashboard'] = !empty($from_dashboard);

        return view($this->getIndexViewFile(), $result);
    }

    /**
     * トップページ表示
     * @return 一覧ページのビューファイル
     */
    protected function getIndexViewFile()
    {
        return 'admin.products';
    }

    public function search(Request $request)
    {
        return $this->searchTrait($request);
    }

    public function update(Request $request, $id)
    {
        $all_request = $request->all();
        if($request->hasFile('upload_image')) {
            $extension = $request->upload_image->getClientOriginalExtension();
            $filename = $request->code.".".$extension;
            $all_request['image'] = $request->upload_image->storeAs('public/images/products', $filename);
        }
        $result = $this->service->update($id, $all_request);
        return response()->json($result);
    }
}
