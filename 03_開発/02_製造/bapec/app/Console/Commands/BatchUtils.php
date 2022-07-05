<?php

namespace App\Console\Commands;

use Lang;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Services\Admin\FileUtils;

class BatchUtils
{

    //=================================================================
    // ログ出力
    //=================================================================
    /**
     * ログドライバー指定
     */
    public static function setLogDriver($logDriverNm)
    {
        Log::setDefaultDriver($logDriverNm);
    }

    /**
     * バッチ開始ログ
     */
    public static function putBatchStartLog( $batchName) {
        BatchUtils::putBatchLog( $batchName,  Lang::get('batch.batch_start'));
    }
    /**
     * バッチ終了ログ
     */
    public static function putBatchEndLog( $batchName) {
        BatchUtils::putBatchLog($batchName, Lang::get('batch.batch_end'));
    }

    /**
     * タスクログ出力
     * @param propId バッチIDログ出力用フォルダ名
     * @param taskId タスクID
     * @param logFileName ログファイル名
     * @param message 出力メッセージ
     */
    public static function putTaskLog($propId, $taskId, $logFileName, $message) {
        // 現在日時取得
        // $nowDate = Carbon::now()->format('Y/m/d');
        // $nowTime = Carbon::now()->format('H:i:s');
        // ＣＳＶチェック結果にてメッセージ編集
        // if ($checkResult == BatchUtilsDefine::CSV_OK){
        //     $message = "チェックOK";
        // } else {
        //     $message = "エラー [" . $msg . "]";
        // }
        // ログメッセージ生成
        $logMsg  = $message;

        $filePath = FileUtils::getDownLoadSaveDirPath($propId,$taskId)."/".$logFileName; 
        BatchUtils::outputLog($filePath, $logMsg);
    }

    /**
     * バッチログ出力（バッチ実行状態を記録）
     */
    public static function putBatchLog($batchName,$message) {
      // ログメッセージ生成
      $logMsg  = ' ['.$batchName.'] '. $message;

    //   $filePath =  "public/" . $propId.'.log';  // ログファイル名生成

    //   BatchUtils::outputLog($filePath, $logMsg);
        Log::info($logMsg);
    }

    private static function outputLog($filePath, $message) {
        // ログメッセージ生成
        $message  = '[' . Carbon::now()->toDateTimeString() . '] '.$message;

        if (Storage::exists($filePath)) {
            Storage::append($filePath, $message);
        } else {
            Storage::put($filePath, $message);
        }
    }
}
