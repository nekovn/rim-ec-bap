<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Storage;
use SplFileObject;
use Illuminate\Support\Facades\Lang;
use App\Services\Admin\FileUtils;
use Illuminate\Console\Command;
use App\Enums\ImportStatusDefine;
use App\Repositories\ImportLogsRepository;
use App\Services\Admin\ImportService;

/**
 * CSV取込系バッチBaseクラス
 */
abstract class BaseImportCsvBatch extends Command
{
   
    /** ファイル許容エンコード */
    protected $IN_ENCODE_ARY = "ASCII,SJIS,SJIS-win,UTF-8";

    /** バッチタイプ　importlog.type */
    protected $type;

    /** 読込CSVエンコード */
    protected $encoding = null;
    /** データベースエンコード */
    protected static $DB_ENCODE = "UTF-8";

    // バッチログ
    protected static $LOG_TASK_FILENM = '{logId}_{csvFileName}.log';
    // バッチログのファイル名
    protected $taskLogFileNam;

    protected $batchName;
    
    /**
     * Create a new command instance.
     *
     * @param infileEncode 取込ファイルエンコード（カンマ区切りで）ex:SJIS,UTF-8
     * @return void
     */
    public function __construct(){
        parent::__construct();
        $this->importLogsRepository = new ImportLogsRepository();

        
    }
    /** メイン前処理 */
    protected function beforeMain(){
        
    }

    /** 
     * タスクごとの処理 
     */
    abstract protected function importExec($importLog);

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // タスクＩＤを取得（即実行時はアップロード時のIDを指定）
        $this->param_logId = $this->argument('logId');
        $this->batchName = ImportService::TYPE_SETTING[$this->type]['name'];

        // バッチ開始ログ
        BatchUtils::setLogDriver($this->batchName);
        BatchUtils::putBatchStartLog($this->description);

        try {
            $this->info(date('Y-m-d h:i:s') . " " . $this->description . " Start.");

            $execTaskId = null;

            // 実行対象のタスクがあるかを確認
            $execTaskList = $this->importLogsRepository->getExecutableTask($this->type, $this->param_logId);

            // 取得したタスク分取込を行う
            if (count($execTaskList) > 0) {
                // // 前処理
                $this->beforeMain();

                // 取込処理実行
                foreach ($execTaskList as $importTask) {
                    $execTaskId = $importTask->id;
                    $this->info("- ID=" . $execTaskId . " Import start.");
 
                    try {
                        //--- バッチスタートログ
                        $this->outputBatchLog('task_id = ' . $execTaskId . "：" . Lang::get('batch_start'));
                        // タスクのステータスを実行中に更新
                        $this->importLogsRepository->updateImpotStatus($execTaskId, ImportStatusDefine::EXECUTING);

                        // -- タスクIDの処理 ---------------------
                        $this->importExec($importTask);
                        //--------------------------------------
                    } catch (\Exception $e) {
                        // //異常終了
                        $this->error($e->getMessage());

                        $this->importLogsRepository->updateImpotStatus($execTaskId, ImportStatusDefine::ABORT, Lang::get('batch.batch_abnormal_end'));

                        $this->outputBatchLog('task_id = ' . $execTaskId . "：Exception：" . $e);
                        $this->outputBatchLog('task_id = ' . $execTaskId . "：" . Lang::get('batch.batch_abnormal_end'));
                        return 0;
                        // throw $e;
                    } finally {
                        $this->info("- ID=" . $execTaskId . " Import Successful.");
                    }
                } // タスク管理の foreach end
                $this->info("- All Task End.");
            } else {
                // 対象データなし
                $this->info("- Task Nothing.");
            }
        } catch (\Exception $e) {
            //異常終了
            $this->error($e->getMessage());
            if ($this->param_logId) {
                $this->importLogsRepository->updateImpotStatus($this->param_logId, ImportStatusDefine::ABORT, Lang::get('batch.batch_abnormal_end'));
                
            }
            $this->outputBatchLog("：Exception：" . $e);
            $this->outputBatchLog(Lang::get('batch.batch_abnormal_end'));
            throw $e;
        }
        BatchUtils::putBatchEndLog($this->description);
        $this->info(date('Y-m-d h:i:s') . " " . $this->description . " Batch End.");
        return 0;
    }


    /**
     * importLogのfile_pathのCSVファイルを取得する
     * 
     * ※Linuxの場合はREAD_CSVで行けると思います。
     * 上手く分割できない場合があるのでその場合は、READ_AHEAD,NEW_LINEのみ指定で取得し
     * FileUtils::getAryCsv($line);で分割します。
     */
    protected function getCsvfileByImportLog($importTask, $flags =
        SplFileObject::READ_CSV |
            SplFileObject::READ_AHEAD |
            SplFileObject::SKIP_EMPTY |
            SplFileObject::DROP_NEW_LINE)
    {
        // ＣＳＶよりデータ取込を行う
        $csvFilePath = storage_path('app/'.FileUtils::getUpLoadSaveDirPath($this->batchName, $importTask->id). '/'.$importTask->file_name);
        // ファイル名のみ抽出
        $this->csvFileName = mb_substr(mb_strrchr($csvFilePath, "/"), 1);

        // タスクログファイル名
        $this->taskLogFileName = strtr(self::$LOG_TASK_FILENM, ['{logId}' => $importTask->id, '{csvFileName}' => $this->csvFileName]);

        $data = file_get_contents($csvFilePath);
        //文字コード確認
        $this->encoding = mb_detect_encoding($data, $this->IN_ENCODE_ARY, true);
        if (!$this->encoding ) {
            $topErrorMessage = Lang::get('messages.E.file.encode',['encode'=>$this->IN_ENCODE_ARY]);

            $this->csvImportStatusUpdate($importTask->id, ImportStatusDefine::SOME_ERROR, $topErrorMessage, null);

            return null;
        }
        // CSVファイル行数取得
        $this->csvRowCount = sizeof(file($csvFilePath));

        $data = mb_convert_encoding($data, self::$DB_ENCODE, $this->encoding);
        $temp = tmpfile();
        $meta = stream_get_meta_data($temp);
        fwrite($temp, $data);
        rewind($temp);

        $csvFile = new SplFileObject($meta['uri'], 'rb');
        $csvFile->setFlags($flags);
        return $csvFile;
    }

    /**
     * ファイルに追記する
     */
    protected function putFile($stream, $string, $to = PHP_EOL) {
        $string = FileUtils::convertEOL($string, $to);
        fwrite($stream, $string );  // エラー発生行をCSVへ出力
    }

    /**
     * import_logs　処理結果更新処理
     * isNormalLogFile:true:正常終了でもlog_file_pathをDB出力する
     */
    protected function csvImportStatusUpdate($logId, $status, $importMessage, $stream, $isNormalLogFile = false) {
        if ($status != ImportStatusDefine::SUCCESS) {
            $params = [];
            if ($stream != null) {
                rewind($stream);
                // 文字コードを取込に合わせる
                $tmp = stream_get_contents($stream);
                $errorCsv = mb_convert_encoding($tmp, $this->encoding, 'UTF-8');
                // csvErrファイル保存※エラーがあったCSVを出力する
                $outputLogFileName = "/". $logId . "_error_" . $this->csvFileName;
                Storage::put(FileUtils::getDownLoadSaveDirPath($this->batchName, $logId).$outputLogFileName, $errorCsv);

                $params = [
                 'log_file_name' =>  $this->taskLogFileName,
                //  'output_file_path' => FileUtils::getDownLoadDirPath($this->batchName, $logId) . $outputLogFileName
                ];
            }
            // 対象のタスクのステータスを一部エラーに更新
            $this->importLogsRepository->updateImpotStatus($logId, $status, $importMessage, $params);
         } else {
            $params = [];
            if ($isNormalLogFile ) {//正常終了でstreamあり
                $params = [
                    'log_file_name' => $this->taskLogFileName,
                ];
            }
            $this->importLogsRepository->updateImpotStatus($logId, $status, $importMessage, $params);
        }

        $this->outputBatchLog('task_id = ' . $logId . "：" . Lang::get('batch.batch_normal_end').'['. $importMessage.']');
    }                                

    /**
     * バッチログ出力
     */
    protected function outputBatchLog($message)
    {
        BatchUtils::putBatchLog($this->description, $message);
    }

   
}