<?php

namespace App\Services\Admin;

use App\Enums\ImportStatusDefine;
use App\Services\Admin\FileUtils;
use Illuminate\Http\Request;
use App\Models\ImportLog;
use Symfony\Component\Process\Process;
use Carbon\Carbon;

/**
 * ファイル取込処理の処理をまとめたサービスクラス
 *
 * @category  商品管理
 * @package   App\Services
 * @version   1.0
 */
class ImportService {
    
    //import_log.type
    const TYPE_SHIPSACHIEVE = 1; //出荷実績
    const TYPE_STOCKS = 2; //在庫
    const TYPE_SETTING = [
        ImportService::TYPE_SHIPSACHIEVE => ['description' => '出荷実績取込', 'name' => 'import-shipsachieve'
        , 'signature' => 'Batch:ImportShipsAchieve','timming'=>ImportService::ASYNC],
        ImportService::TYPE_STOCKS => ['description' => '在庫取込', 'name' => 'import-stocks'
        , 'signature' =>'Batch:ImportStocks', 'timming' => ImportService::ASYNC]
    ];
    /** 同期 */
    const SYNC = 1;
    /** 非同期　*/
    const ASYNC = 2;

   /**
     * コンストラクタ
     *
     * @access public
     **/
    public function __construct()    {
        
    }
    /**
     * ファイル取込処理
     *
     * @param ImportRequest $request
     * @return array レスポンス
     */
    public function import(Request $request, $type)
    {
        // アップロードされたファイルを取得する
        $fileUpload = $request->hasFile('upload_file') ? $request->file('upload_file') : null;

        // 文字コードが UTF-8 以外は対象外のため登録せずに終了
        //--- file -i はWindowsサポート外。とりあえず運用でカバー
        // $command = "file -i " . $fileUpload;
        // $output = [];
        // $status = "";
        // exec($command, $output, $status);
        // preg_match("/charset=(.*)/", $output[0], $charset);
        // if(!in_array("utf-8", $charset)) {
        //     // UTF-8 以外のファイルが指定された場合はエラー
        //     return $resultData;
        // }
        //------------------------------
        // タスク管理に登録
        $params = [
            'type'  => $type,
            'upload_date'   => Carbon::now(),
        ];
        //登録＆upload
        $importLogId = $this->store($params, $fileUpload, ImportService::TYPE_SETTING[$type]['name']);

        //バッチ名
        $signature = ImportService::TYPE_SETTING[$type]['signature'];
        //同期非同期
        $timming = ImportService::TYPE_SETTING[$type]['timming'];
        // 同期実行（即時実行）の場合バッチ実行）
        if ($timming == ImportService::SYNC) {
            $process = new Process(['php', 'artisan', $signature, $importLogId], base_path());
            $process->start();
            while ($process->isRunning()) {
                // 実行後の戻りをcatchする
            }
        } else {

            // 実行区分が全件で即実行の場合はバックグラウンドにて実行※閉じたWinServer2016でLinux版ロジック動かないためロジック分ける
            if (strpos(PHP_OS, 'WIN') !== false) {
                // Windows
                $process = new Process(['start', 'php', 'artisan', $signature, $importLogId], base_path());
                $process->start();
            } else {
                // Linux
                $command = 'php "' . base_path('artisan') . '" ' . $signature . ' ' . $importLogId . ' > /dev/null &';
                exec($command);
            }
            sleep(3);           // 取込処理の実行開始を待つ

        }

        // 登録後または即時バッチ実行後の一覧を再取得
        $logs = $this->getImportLogList($type);
        $resultData = [
            'data' => $logs,
        ];

        return $resultData;
    }
    /**
     * ImportLogより直近５件取得
     */
    public function getImportLogList($type) {
        return ImportLog::orderBy('id', 'DESC')->where(['type' => $type])->take(5)->get();
    }

    /**
     * タスク管理を登録する。
     *
     * @access public
     * @
     * @param array $params パラメーター
     * @param file $uploadFile
     * @param string $propId
     * @return importlog.id
     * @Transactional()
     */
    private function store(array $params, $uploadFile, $propId)
    {
        $fileName   = $uploadFile ? $uploadFile->getClientOriginalName() : '';

        $params  += ['status' => ImportStatusDefine::WAIT_EXEC];
        $params  += ['file_name'     => $fileName];
        //--- import log 登録
        $importLog = ImportLog::create($params);
        // ファイルをアップロード
        $uploadFile->storeAs(FileUtils::getUpLoadSaveDirPath($propId, $importLog->id), $fileName);

        //開始時間
        // $strdt = Carbon::now();
        // if ($params['exec_mode'] != ImportService::CSV_IMPORT_IMMEDIATE) { //即時以外
        //     $now_5 = $strdt->addMinutes(5); //現在の５分後
        //     $strdt = new Carbon($now_5->format('Y-m-d') . config('app.batch_exec_time'));
        //     if ($strdt->lt($now_5)) { //現在時間より小さい
        //         $strdt = $strdt->addDays(1);
        //     }
        // } else {
        //     $strdt = null;
        // }

        // 対象のimportlogのステータスを実行待ちに更新
        // $params['file_name'] = FileUtils::getUpLoadDirPath($propId, $importLog->id) . '/' . $fileName;
        // ImportLog::where('id', $importLog->id)->update($params);

        return $importLog->id;
    }
    

}