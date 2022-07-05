<?php

namespace App\Console\Commands;

use App\Services\Admin\ImportService;
use App\Enums\ImportStatusDefine;
use SplFileObject;
use Carbon\Carbon;
use App\Services\Admin\FileUtils;
use App\Repositories\ShipsRepository;
use App\Services\ShipsService;
use App\Models\Ship;
use Illuminate\Support\Facades\Lang;

/**
* 出荷実績取込バッチ
*/
class ImportShipsAchieve extends BaseImportCsvBatch
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = ImportService::TYPE_SETTING[ImportService::TYPE_SHIPSACHIEVE]['signature'] . '{logId?}';  // NULL許容
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = ImportService::TYPE_SETTING[ImportService::TYPE_SHIPSACHIEVE]['description'];
    protected $name = ImportService::TYPE_SETTING[ImportService::TYPE_SHIPSACHIEVE]['name'];

    /** バッチタイプ */
    protected $type = ImportService::TYPE_SHIPSACHIEVE;

    /** 処理開始行 begin:1*/
    private $CSV_START_ROW = 1;

    /** 列Index：モール受注番号=出荷.ID */
    private static $COLIDX_SHIPS_ID = 1;
    /** 列Index：問合せ番号 */
    private static $COLIDX_SLIP_NO = 4;
    /** 列Index：出荷日 */
    private static $COLIDX_SHIP_DATE = 5;
    /** 列Index：商品コード */
    private static $COLIDX_GOODS_CD = 12;
    /** 列Index：出荷数 */
    private static $COLIDX_SHIPS_CNT = 14;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ShipsService $shipsService, ShipsRepository $shipsRepository) {
        
        parent::__construct() ;

        $this->shipsRepository = $shipsRepository;
        $this->shipsService = $shipsService;
    }
    /** 
     * メイン処理 
     */
    protected function importExec($importTask)
    {
        // //--- Windows対応
        ini_set('auto_detect_lin_encodings', true);//PHPがLFを改行コードと判断するため。改行がLF、CRLF等混ざる時改行判断させる
        
        //----- CSVファイルを取得する
        $csvFile = parent::getCsvfileByImportLog(
            $importTask,
            SplFileObject::READ_AHEAD |
            SplFileObject::SKIP_EMPTY |
            SplFileObject::DROP_NEW_LINE
        );

        if ($csvFile == null) {
            return; //処理を終える
        }
        $execAt = Carbon::now();
        // ヘッダ行を除いた行数
        $csvDataCount = $this->csvRowCount - ($this->CSV_START_ROW - 1);

        //----- データを取得して処理していく
        $rowNo = 0;
        $stream = fopen('php://temp', 'w');
        try {
            $errorCount = 0;
            $registCount = 0;
            $skipCount = 0;
            $chkShipId = '';
            $isChk = true;
            $ship = null;
            $csvDetails = [];
            foreach ($csvFile as $line) {
                $rowNo += 1;
                if ($rowNo < $this->CSV_START_ROW) { //スタート行まで読み飛ばす
                    continue;
                }
                
                //CSV配列
                // windowsでPHPのCSV作成モジュールは分割がおかしくなるため自作
                $aryCsv = FileUtils::getAryCsv($line);
                $shipsId = $aryCsv[self::$COLIDX_SHIPS_ID];

                //同じ出荷IDで前のデータにエラーがある場合は読み飛ばし
                if ($chkShipId === $shipsId && $isChk === false) {
                    $skipCount += 1;
                    continue;
                }

                if ($chkShipId != $shipsId ) {//ヘッダが変わった or 最終行
                    if ($ship !=null && $isChk) { //チェックエラーが無い
                        $shipDetails = $ship->shipDetails;
                        $messages = $this->chkMeisai($shipDetails, $csvDetails, $rowNo-1);
                        if ($messages) {
                            foreach ($messages as $message) {
                                BatchUtils::putTaskLog($this->batchName, $importTask->id, $this->taskLogFileName, $message);
                            }
                        } else {
                            //----- 出荷確定処理
                            $params = [
                                'ship_date' => $ship->ship_date
                                ,'slip_no'=> $ship->slip_no];
                            $this->shipsService->shipmentConfirmed($chkShipId, $params);
                            $registCount += count($shipDetails);
                        }
                    }
                    $chkShipId = $shipsId;
                    $isChk = true;
                    unset($csvDetails);
                    //----- 出荷番号で検索
                    $ship = Ship::find($shipsId);//idで検索
                    if (is_null($ship)) {
                        BatchUtils::putTaskLog($this->batchName, $importTask->id, $this->taskLogFileName
                            , Lang::get('batch.csv_import.error.check'
                                , ['row' => $rowNo, 'attribute' => "出荷ID",'val'=> $shipsId,'message'=>'出荷テーブルに存在しません。']));
                        $skipCount += 1;
                        $isChk = false;
                        continue;
                    }
                    //----- データ保持
                    $ship->ship_date = $aryCsv[self::$COLIDX_SHIP_DATE];//(Carbon::createFromFormat('Ymd', $aryCsv[self::$COLIDX_SHIP_DATE], 'Asia/Tokyo'));
                    $ship->slip_no = $aryCsv[self::$COLIDX_SLIP_NO];
                    // $ships->status = StatusDefine::SHUKKA_SUMI;
                    // $ships->order()->status = StatusDefine::SHUKKA_ZUMI;
                }
                $csvDetails[$rowNo] = ['goods_code'=> $aryCsv[self::$COLIDX_GOODS_CD],'qty'=> $aryCsv[self::$COLIDX_SHIPS_CNT]];
            }
            //----- 最後の出荷データ処理
            if ($ship != null && $isChk) { //チェックエラーが無い
                $shipDetails = $ship->shipDetails;
                $messages = $this->chkMeisai($shipDetails, $csvDetails, $rowNo);
                if ($messages) {
                    foreach ($messages as $message) {
                        BatchUtils::putTaskLog($this->batchName, $importTask->id, $this->taskLogFileName, $message);
                    }
                } else {
                    //----- 出荷確定処理
                    $params = [
                        'ship_date' => $ship->ship_date, 'slip_no' => $ship->slip_no
                    ];
                    $this->shipsService->shipmentConfirmed($chkShipId, $params);
                    $registCount += count($shipDetails);
                }
            }

            $taskMessage = '処理:' . number_format($csvDataCount) . '件 取込:' . number_format($registCount) . '件';//スキップ:' . number_format($skipCount) . '件 エラー:' . number_format($errorCount) . '件';


            parent::csvImportStatusUpdate(
                $importTask->id,
                $errorCount > 0 ? ImportStatusDefine::SOME_ERROR : ImportStatusDefine::SUCCESS,
                $taskMessage,
                $stream,
                $skipCount > 0
            );

            fclose($stream);
        } catch (\Exception $e) {
            if ($stream) {
                fclose($stream);
            }
            throw $e;
        }
    }
    private function chkMeisai($shipDetails, $csvDetails, $row) {
        $arratMessage = [];
        //明細数
        if (count($shipDetails)!==count($csvDetails)) {
            //明細数が違う
            $arratMessage[] = Lang::get('batch.csv_import.error.check'
                 , ['row' => $row, 'attribute' => "出荷明細数",'val'=> count($shipDetails),'message'=>'出荷明細とCSV明細('. count($csvDetails) . ')の件数が異なります。']);
        }
        foreach ($csvDetails as $rowKey => $csv) {
            $isHit = false;
            foreach ($shipDetails as $detail) {
                if ($detail->goods_code == $csv['goods_code']) {
                    $isHit = true;
                    if ($detail->quantity != $csv['qty']) {
                        //出荷数が違う
                        $arratMessage[] = Lang::get(
                            'batch.csv_import.error.check',
                            ['row' => $rowKey, 'attribute' => "出荷ID:商品コード:数量(bap/csv)", 'val' => $detail->id .':' . $detail->goods_code . ':' . $detail->quantity . '/' . $csv['qty'], 'message' => '出荷数が異なります。']
                        );
                    }
                }
            }
            if (!$isHit) {
                //コードが出荷明細にない
                $arratMessage[] = Lang::get(
                    'batch.csv_import.error.check',
                    ['row' => $rowKey, 'attribute' => "出荷ID:商品コード(bap/csv)", 'val' => $detail->id . ':' . $detail->goods_code . '/' . $csv['goods_code'], 'message' => '商品コードが存在しません。']
                );
            }
        }
        return $arratMessage;
    }
}