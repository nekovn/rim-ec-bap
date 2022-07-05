<?php
namespace App\Services\Admin;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use ZipArchive;
use Lang;

/**
 * CSV出力サービスクラス
 *
 */
class CsvOutputService
{
    // class用変数定義
    private static $outputDir ;     // CSV出力ディレクトリ
    /**
     * コンストラクタ
     *
     * @access public
     */
    public function __construct() {
        CsvOutputService::$outputDir = "csv-output";//config('app-settings.lifes.csv-output.directory');
        // $this->encodeList = config('app-settings.lifes.csv-output.encode');
    }

    /**
     * ディレクトリ内に格納されているファイル名を取得する
     *
     * @access public
     * @return array
     */
    public function getFileNameList()
    {
        // 条件ファイル(XML)配置ディレクトリ取得
        $wk_dir = storage_path().'/app/public/'.CsvOutputService::$outputDir;
        // XMLファイルのファイル名のみ取得（拡張子無し）
        $fileNameList = [];
        foreach (glob($wk_dir.'/*.xml') as $fileName) {
            $fileName = explode("/",$fileName);     // フルパスを分割
            $fileName = end($fileName);             // ファイル名のみ
            $fileName = explode('.',$fileName);     // 拡張子を分割
            $fileNameList[] = $fileName[0];         // 名称部分のみ
        }
        return $fileNameList;
    }
    /**
     * 指定ファイル名の詳細を取得する
     *
     * @param string $file_name
     * @access public
     * @return array
     */
    public function getJokenFileDetail($file_name)
    {
        // 条件ファイル(XML)配置ディレクトリ取得
        $wk_dir = storage_path().'/app/public/'.CsvOutputService::$outputDir;
        $download_path = '/storage/'.CsvOutputService::$outputDir;
        $download_file = $download_path.'/'.$file_name.'.xml';
        // 対象ファイル
        $xml_target_file = $wk_dir.'/'.$file_name.'.xml';
        // ファイル内容を取得
        $xml_get_contents = file_get_contents($xml_target_file);
        libxml_use_internal_errors(true);   // XMLファイルエラーハンドリング
        // 取得内容をオブジェクト型式とする
        $xml_data = simplexml_load_string($xml_get_contents);
        // 取得データを配列に変換
        $xml_data = $this->xml_to_array($xml_data);
        // SQL部分の削除
        unset($xml_data['SQLSTR']);
        if($xml_data === false) {
            // 条件ファイルを削除
            unlink($xml_target_file);
            foreach(libxml_get_errors() as $error) {
                $xml_error = $error->message;
            }
            $resultData = [
                'data' => $xml_data,
                'download_path' => $download_file,
                'errorMessage' => Lang::get(
                    'messages.E.file.format'),//CsvOutputDefine::ERROR_XML_FORMAT,
                'errDetail' => $xml_error,
            ];
            return $resultData;
        }

        $resultData = [
            'data' => $xml_data,
            'download_path' => $download_file,
            'errorMessage' => '',
        ];
        return $resultData;
    }

    /**
     * CSVファイルのダウンロードを行う
     *
     * @param CsvOutputDownloadRequest $request
     * @access public
     * @return array
     */
    public function downloadCsvFile($request, $isCsv = true)
    {
        $file_name = $request['joken_file_name'];
        //出力エンコード
        // $encode = $this->encodeList[$request['char_code']];
        $encode = $request['char_code'];

        // 条件ファイル(XML)配置ディレクトリ取得
        $wk_dir = storage_path().'/app/public/'.CsvOutputService::$outputDir;
        // 対象ファイルよりSQLを取得
        $xml_target_file = $wk_dir.'/'.$file_name.'.xml';
        $xml_data = simplexml_load_file($xml_target_file);
        $sql = $xml_data->SQLSTR;
        $fileName = $xml_data->FILENAME;

        // 変数定義（変数に画面入力値を設定）
        $sqlParamList = $request['sql_param'];
        $sqlParams = json_decode(json_encode($xml_data), true);
        if (isset($sqlParams['SQLPARAM'])) {
            // SQLPARAM要素の記述あり
            $sqlParameters = [];
            if (isset($sqlParams['SQLPARAM']['id'])) {
                // SQLPARAM要素が１つの場合
                $sqlParameters[] = $sqlParams['SQLPARAM'];
            } else {
                // SQLPARAM要素が複数ある場合
                $sqlParameters = $sqlParams['SQLPARAM'];
            }
            // パラメータの設定
            foreach($sqlParameters as $sqlParameter) {
                $id = $sqlParameter['id'];
                $paramquery = $sqlParameter['paramquery'];
                $inputValue = $sqlParamList[$id];
                $sqlParam = str_replace($id, '\''. $inputValue . '\'', $paramquery);
                DB::statement(DB::raw($sqlParam));
            }
        }

        // ファイル名を作成する
        if (substr_count($fileName,'@') > 0) {
            // @xxx@の記述がある場合は置き換える

            // @SQLPARAMのid@ ⇒ 入力値に置き換える
            if (isset($sqlParameters['id'])) {
                // SQLPARAMが１つ
                $id = $sqlParameters['id'];
                $inputValue = $sqlParamList[$id];
                $fileName = str_replace($id, $inputValue, $fileName);
            } else {
                // SQLPARAMが複数
                foreach($sqlParameters as $sqlParameter) {
                    $id = $sqlParameter['id'];
                    $inputValue = $sqlParamList[$id];
                    $fileName = str_replace($id, $inputValue, $fileName);
                }
            }

            // @timestamp@ ⇒ 日時(yyyymmddhhii形式)に置き換える
            $timestamp = date('YmdHi');
            $tempFileName = str_replace('@timestamp@', $timestamp, $fileName);
        }
        $tempFileName .= '.csv';

        DB::statement(DB::raw('set @rownum = 0'));    // 行番号付与用変数(連番生成)
        // SQLを実行しデータを取得
        $result = DB::select(DB::raw($sql));     // XMLより抽出したSQLを実行
        if ($isCsv === false) {
            return $result;
        }
        // 対象データが０件の場合
        if(!$result) {
            $resultData = [
                'errorMessage' => Lang::get('messages.E.targetdata.notfound')
            ];
            return $resultData;
        }
        // ** CSVファイル作成 **
        // 作業用ディレクトリ
        $wk_dir = storage_path().'/app/public/'.CsvOutputService::$outputDir.'/tmp/';
        // テンポラリディレクトリを初期化（全削除）
        array_map('unlink', glob($wk_dir.'*.*'));
        // ダウンロードパスディレクトリ
        $temp_file_dir = '/storage/'.CsvOutputService::$outputDir.'/tmp/';
        // 作業用ディレクトリがない場合は作成する
        if (!file_exists($wk_dir)) {
            mkdir($wk_dir,0777);
        }
        // 取得データを配列へ stdClassで取得されるため変換
        $queryResult = [];
        foreach ($result as $stdClass) {
            $queryResult[] = get_object_vars($stdClass);
        }

        // CSV作成
        $this->outputCsv($queryResult, $request['midasi_flag'], $encode, $wk_dir . $tempFileName);

        $csvFileName = $wk_dir . $tempFileName;
        $outputCsvFileName = $temp_file_dir . $tempFileName;
        // CSVのみ時レスポンス
        $resultData = [
            'outputFileName' => $csvFileName,
            'fileName' => $tempFileName,
            'downloadPath' => $outputCsvFileName,
            'errorMessage' => '',   // 正常終了
        ];

        return $resultData;
    }
    /**
     * CSV作成
     */
    private function outputCsv($queryResult, $isMidashi, $encode, $fileName) {
        $stream = tmpfile();
        if ($stream) {
            // 見出しフラグありの場合はタイトル行を入れる
            if ($isMidashi) {
                fputcsv($stream, array_keys($queryResult[0]));
            }
            // 取得データの書き込み
            foreach ($queryResult as $rowData) {
                fputcsv($stream, $rowData);
            }
            rewind($stream);
            $csv = preg_replace("/\r\n|\r|\n/", "\r\n", stream_get_contents($stream));
            $csv = mb_convert_encoding($csv, $encode);

            $resource_id = fopen($fileName, 'w');
            fwrite($resource_id, $csv);
            // CSVファイルを閉じる
            fclose($stream);
            fclose($resource_id);
        }
    }

    /**
     * XMLファイル内のオブジェクトを配列として設定
     *
     * @param $xml
     * @access private
     * @return array
     */
    private function xml_to_array($xml) {
        $xml_array = [];
        $_ary = is_object($xml) ? get_object_vars($xml) : $xml;
        foreach ($_ary as $key => $value) {
            if (is_object($value)) {
                $array_count = 0;
                $xml_array[$key][$array_count] = $value;
            } else {
                $xml_array[$key] = $value;
            }
        }
        return $xml_array;
    }
}
