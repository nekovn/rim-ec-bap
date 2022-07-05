<?php

namespace App\Console\Commands;

use App\Services\Admin\ImportService;
use App\Enums\ImportStatusDefine;
use SplFileObject;
use Carbon\Carbon;
use App\Services\Admin\FileUtils;
use App\Repositories\GoodsRepository;
use App\Repositories\GoodsStocksRepository;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\DB;
use App\Models\Goods;

/**
* 在庫取込バッチ
*/
class ImportStocks extends BaseImportCsvBatch
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = ImportService::TYPE_SETTING[ImportService::TYPE_STOCKS]['signature'] . '{logId?}';  // NULL許容
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = ImportService::TYPE_SETTING[ImportService::TYPE_STOCKS]['description'];
    protected $name = ImportService::TYPE_SETTING[ImportService::TYPE_STOCKS]['name'];

    /** バッチタイプ */
    protected $type = ImportService::TYPE_STOCKS;

    /** 処理開始行 begin:1*/
    private $CSV_START_ROW = 1;
    /** 一気に何件Commitするか mysqlタイムアウト対応 */
    private static $MAX_INS_CNT = 1000;

    /** 列Index：商品コード -------- */
    private static $COLIDX_SHOHIN_CD = 1;
    /** 列Index：良品区分 */
    private static $COLIDX_RYOHIN_KBN = 2;
    /** 列Index：在庫数 */
    private static $COLIDX_ZAIKO_SU = 3;
    /** 列Index：引当数 */
    private static $COLIDX_HIKIATE_SU = 4;
    //------------------------------
    /** 良品区分：0：良品 */
    private static $RYOHIN_JBN_GOOD = 0;
    /** 良品区分：1:不良品 */
    private static $RYOHIN_JBN_BAD = 1;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(GoodsRepository $goodsRepository, GoodsStocksRepository $goodsStocksRepository) {
        
        parent::__construct() ;

        $this->goodsRepository = $goodsRepository;
        $this->goodsStocksRepository = $goodsStocksRepository;
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
        $registData = []; //登録対象データ（チェックOKデータ）
        $stream = fopen('php://temp', 'w');
        try {
            $errorCount = 0;
            $registCount = 0;
            $skipCount = 0;

            foreach ($csvFile as $line) {
                $rowNo = $rowNo + 1;
                if ($rowNo < $this->CSV_START_ROW) { //スタート行まで読み飛ばす
                    continue;
                }

                //CSV配列
                // windowsでPHPのCSV作成モジュールは分割がおかしくなるため自作
                $aryCsv = FileUtils::getAryCsv($line);

                //----- 商品コード存在
                $goodsCd = $aryCsv[self::$COLIDX_SHOHIN_CD];
                $goods = Goods::where('code', $goodsCd)->first();
                if (!$goods) {
                    BatchUtils::putTaskLog($this->batchName, $importTask->id, $this->taskLogFileName
                        , Lang::get('batch.csv_import.error.check'
                            , ['row' => $rowNo, 'attribute' => "商品コード",'val'=> $goodsCd,'message'=>'商品マスタに存在しません。']));
                    $errorCount++;
                    continue;
                }
                //----- 在庫テーブル更新
                $valuse = [
                    'goods_id' => $goods->id,
                    'warehouse_id' => 1,//固定
                    'assigned_quantity' => $aryCsv[self::$COLIDX_HIKIATE_SU]
                ];
                //数
                if ($aryCsv[self::$COLIDX_RYOHIN_KBN] == self::$RYOHIN_JBN_GOOD ) {
                    $valuse['quantity'] = $aryCsv[self::$COLIDX_ZAIKO_SU];
                } else if ($aryCsv[self::$COLIDX_RYOHIN_KBN] == self::$RYOHIN_JBN_BAD) {
                    $valuse['b_grade_quantity'] = $aryCsv[self::$COLIDX_ZAIKO_SU];
                } else {
                    BatchUtils::putTaskLog($this->batchName, $importTask->id, $this->taskLogFileName
                        , Lang::get('batch.csv_import.error.check'
                            , ['row' => $rowNo, 'attribute' => "良品区分",'val'=> $goodsCd,'message'=>'良品区分が正しくありません。']));
                    $errorCount++;
                    continue;
                }
                $registData[] =  $valuse;

                if (count($registData) >=  self::$MAX_INS_CNT || ($rowNo == $this->csvRowCount)) {
                    $this->import($registData);
                    $registCount = $registCount + count($registData);
                    $registData = [];
                } // 分割登録処理 end
            }

            $taskMessage = '処理:' . number_format($csvDataCount) . '件 取込:' . number_format($registCount) . '件 スキップ:' . number_format($skipCount) . '件 エラー:' . number_format($errorCount) . '件';


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
    /**
     * 登録処理
     */
    private function import($datas)
    {
        DB::transaction(function () use ($datas) { //エラー時は自動でrollback
            foreach ($datas as $data) {
                $this->goodsStocksRepository->upsert($data);
            }
        });
    }
}