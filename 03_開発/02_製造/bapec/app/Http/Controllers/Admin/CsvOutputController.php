<?php

namespace App\Http\Controllers\Admin;

use App\Services\Admin\CsvOutputService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\Util\SystemHelper;

/**
 * CSV出力
 *
*/
class CsvOutputController extends Controller
{
    /**
     * コンストラクタ
     *
     * @access public
     * @param CsvOutputService $csvOutputService CSV出力サービス
     */
    public function __construct(CsvOutputService $csvOutputService)
    {
        $this->csvOutputService = $csvOutputService;
    }
    /**
     * 一覧を表示する。
     *
     * @access public
     * @return 一覧ページ
     */
    public function index()
    {
        // XMLファイル名一覧を取得
        $fileNameList = $this->csvOutputService->getFileNameList();
        // 文字エンコード種別を取得
        // $encodeList = config('app-settings.lifes.csv-output.encode');
        return view('admin.csv-output', [
            'fileNameList' => $fileNameList,
        ]);
    }
    /**
     * 指定ファイル名の詳細を取得する
     *
     * @access public
     * @param string $file_name ファイル名
     * @return json
     */
    public function edit($file_name)
    {
        $resultData = $this->csvOutputService->getJokenFileDetail($file_name);
        return $resultData;
    }

    /**
     * ダウンロード処理
     *
     * @param Request $request
     * @return void
     */
    public function download(Request $request) {
        $resultData = $this->csvOutputService->downloadCsvFile($request);
        return $resultData;
    }

    /**
     * CSVデータ画面表示 index
     */
    public function general_list()
    {
        return view('admin.general-list');
    }
    /**
     * CSVデータ画面表示 search
     */
    public function general_list_search(Request $request)
    {
        $resultData = $this->csvOutputService->downloadCsvFile($request, false);

        $limit = SystemHelper::getAppSettingValue('page.pagination.display-count.default');
        //ページングがあるため10倍表示可能とする
        $limit = $limit * 10;
        if (count($resultData) > $limit) {
            return ['total' => $limit, 'data' => []];
        }

        return ['total'=>count($resultData), 'data'=>$resultData];
    }
}
