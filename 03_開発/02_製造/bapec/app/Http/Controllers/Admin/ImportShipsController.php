<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ImportRequest;
use App\Services\Admin\ImportService;
use Illuminate\Http\Request;

/**
 * 出荷実績CSVインポート
 */
class ImportShipsController extends Controller
{
    use ImportControllerTrait;

    /**
     * コンストラクタ
     *
     * @access public
     * @param ImportService $importService 
     */
    public function __construct(ImportService $importService)
    {
        $this->service = $importService;
    }

    /**
     * 出荷実績CSV取込画面初期表示
     *
     * @return void
     */
    public function index()
    {
        return view('admin.import_shipsachieve');
    }

    /**
     * 一覧のデータを返す
     *
     * @return void
     */
    public function search()
    {
        $logs = $this->service->getImportLogList(ImportService::TYPE_SHIPSACHIEVE);
        return ['data' => $logs];
    }

    /**
     * CSVのアップロードを行う
     *
     * @param Request $request
     * @return void
     */
    public function import(ImportRequest $request)
    {
        $this->service->import($request, ImportService::TYPE_SHIPSACHIEVE);
        return [];
    }
}
