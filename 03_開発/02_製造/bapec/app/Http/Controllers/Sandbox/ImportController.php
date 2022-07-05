<?php

namespace App\Http\Controllers\Sandbox;

use App\Http\Controllers\Controller;
use App\Models\ImportLog;
use App\Services\Sandbox\ImportService;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    use ImportControllerTrait;

    /**
     * コンストラクタ
     *
     * @access public
     * @param ImportService $importService ユーザサービス
     */
    public function __construct(ImportService $importService)
    {
        $this->service = $importService;
    }

    /**
     * 得意先CSV取込画面初期表示
     *
     * @param Request $request
     * @return void
     */
    public function clients(Request $request) {
        return view('admin.import_clients');
    }

    /**
     * 商品CSV取込画面初期表示
     *
     * @param Request $request
     * @return void
     */
    public function products(Request $request) {
        return view('admin.import_products');
    }

    /**
     * カテゴリCSV取込画面初期表示
     *
     * @param Request $request
     * @return void
     */
    public function category(Request $request) {
        return view('admin.import_categories');
    }

    /**
     * 得意先CSVのアップロードを行う
     *
     * @param Request $request
     * @return void
     */
    public function clientsImport(Request $request)
    {
        $this->service->clientsImport($request);
        return [];
    }

    /**
     * 商品CSVのアップロードを行う
     *
     * @param Request $request
     * @return void
     */
    public function productsImport(Request $request)
    {
        $this->service->productsImport($request);
        return [];
    }

    /**
     * カテゴリCSVのアップロードを行う
     *
     * @param Request $request
     * @return void
     */
    public function categoryImport(Request $request)
    {
        $this->service->categoryImport($request);
        return [];
    }

    /**
     * カテゴリCSVのダウンロードを行う
     *
     * @param Request $request
     * @return void
     */
    public function downloadCategoryCSV(Request $request)
    {
        return $this->service->downloadCategoryCSV();
    }

    /**
     * 得意先CSV取込ログを取得する
     *
     * @param Request $request
     * @return json
     */
    public function getClientImportLogs(Request $request)
    {
        $logs = ImportLog::orderBy('id', 'DESC')->where(['type' => ImportService::CLIENTS])->take(5)->get();
        return ['data' => $logs];
    }

    /**
     * 商品CSV取込ログを取得する
     *
     * @param Request $request
     * @return json
     */
    public function getProductImportLogs(Request $request)
    {
        $logs = ImportLog::orderBy('id', 'DESC')->where(['type' => ImportService::PRODUCTS])->take(5)->get();
        return ['data' => $logs];
    }

    /**
     * カテゴリCSV取込ログを取得する
     *
     * @param Request $request
     * @return json
     */
    public function getCategoryImportLogs(Request $request)
    {
        $logs = ImportLog::orderBy('id', 'DESC')->where(['type' => ImportService::CATEGORY])->take(5)->get();
        return ['data' => $logs];
    }
}
