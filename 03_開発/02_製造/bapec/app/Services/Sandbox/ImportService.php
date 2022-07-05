<?php
namespace App\Services\Sandbox;

use App\Enums\ClassDivDefine;
use App\Enums\UneditedDefine;
use App\Models\Categorie;
use App\Models\ImportLog;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use SplFileObject;

/**
 * ユーザ管理関連の処理をまとめたサービスクラス
 *
 * @package   App\Services
 * @version   1.0
 */
class ImportService
{
    const SUCCESS = 1;
    const ERROR = 2;

    const CLIENTS = 1;
    const PRODUCTS = 2;
    const CATEGORY = 3;

    const PATH_NAMES = [
        self::CLIENTS => 'clients',
        self::PRODUCTS => 'products',
        self::CATEGORY => 'categories',
    ];

    // 得意先CSVから取り込む項目とDBのカラム名紐付け
    const CLIENTS_COLUMN = [
        '得意先ｺｰﾄﾞ'     =>  'code',
        '得意先名1'      =>  'name',
        '電話番号'       =>  'tel',
        '営業担当ｺｰﾄﾞ1'  =>  'sales_in_chage_code',
        '業種ｺｰﾄﾞ'       =>  'industry_code'
    ];

    // 商品CSVから取り込む項目とDBのカラム名紐付け
    const PRODUCTS_COLUMN = [
        '商品ｺｰﾄﾞ'          =>  'code',
        '商品名'            =>  'name',
        '商品名ｶﾅ'          =>  'name_kana',
        'JANｺｰﾄﾞ'           =>  'jan_code',
        '小分類ｺｰﾄﾞ'        =>  'class1_code',
        '細分類ｺｰﾄﾞ'        =>  'class2_code',
        'ﾌﾞﾗﾝﾄﾞ名称'        =>  'brand_name',
        '入数1'             =>  'case_qty',
        '入数2'             =>  'inbox_qty',
        '入数3'             =>  'fraction_qty',
        '商_受注ﾛｯﾄ単位区分'  =>  'order_lot_type',
        '商_受注ﾛｯﾄ数'      =>  'order_lot',
        '定番区分'          =>  'class_div',
    ];

    // カテゴリCSVから取り込む項目とDBのカラム名紐付け
    const CATEGORIES_COLUMN = [
        'カテゴリコード'    => 'category_code',
        '小カテゴリコード'  => 'class1_code',
        '小カテゴリ名'      => 'class1_name',
        '細カテゴリコード'  => 'class2_code',
        '細カテゴリ名'      => 'class2_name',
    ];
            
    /**
     * コンストラクタ
     *
     * @access public
     * @param ImportRepository $importRepository ユーザリポジトリ
     */
    public function __construct() {

    }

    /**
     * 得意先マスタの更新を行う
     *
     * @param string $key
     * @param array $values
     * @return void
     */
    protected function clientsUpdateOrCreate($key, $values)
    {
        $result = 'ok';
        $values['is_trial'] = 0; //0固定
        $values['created_by'] = 0; //0固定
        $values['updated_by'] = 0; //0固定
        try {
            $this->clientsRepository->updateOrCreate($key, $values);
        } catch(\Exception $e) {
            $result = 'ng';
        }
        return $result;
    }

    /**
     * 商品マスタの更新を行う
     *
     * @param string $key
     * @param array $values
     * @return void
     */
    protected function productsUpdateOrCreate($key, $values)
    {
        $result = 'ok';
        $values['created_by'] = 0; //0固定
        $values['updated_by'] = 0; //0固定
        try {
            $this->productsRepository->updateOrCreate($key, $values);
        } catch(\Exception $e) {
            $result = 'ng';
        }
        return $result;
    }

    /**
     * 商品マスタの削除を行う
     *
     * @param string $key
     * @param array $values
     * @return void
     */
    protected function productsDelete($model)
    {
        $deleteResult = 'ok';
        try {
            $model->updated_by = 0;
            $model->save();
            $model = $model->delete();
        } catch(\Exception $e) {
            $deleteResult = 'ng';
        }
        return $deleteResult;
    }

    /**
     * カテゴリマスタの登録を行う
     *
     * @param array $values
     * @return void
     */
    protected function categoryCreate($values)
    {
        $result = '';
        $values['created_by'] = 0; //0固定
        $values['updated_by'] = 0; //0固定
        try {
            Categorie::create($values);
        } catch (\PDOException $pdoe) {
            $result = 'カテゴリコードが重複しています';
        } catch(\Exception $e) {
            $result = '更新に失敗しました';
        }
        return $result;
    }

    /**
     * キーを元に得意先マスタを検索する
     *
     * @param string $key
     * @return void
     */
    protected function getClientInfo($key)
    {
        return $this->clientsRepository->getClients($key);
    }

    /**
     * キーを元に商品マスタを検索する
     *
     * @param string $key
     * @return void
     */
    protected function getProductInfo($key)
    {
        return $this->productsRepository->getProducts($key);
    }

    /**
     * キーを元にカテゴリマスタを検索する
     *
     * @param string $key
     * @return void
     */
    protected function getCategoryInfo($key)
    {
        return Categorie::where($key)->first();
    }

    /**
     * カテゴリマスタCSVをダウンロードする
     *
     * @return void
     */
    public function downloadCategoryCSV()
    {
        $date = Carbon::now()->format('Y-m-d');
        return response()->streamDownload(function () {
            $categories = Categorie::select(['category_code', 'class1_code', 'class1_name', 'class2_code', 'class2_name'])->get()->toArray();
            $stream = fopen('php://temp', 'r+b');
            fputcsv($stream, ['カテゴリコード', '小カテゴリコード', '小カテゴリ名', '細カテゴリコード', '細カテゴリ名']);
            foreach ($categories as $category) {
                fputcsv($stream, $category);
            }
            rewind($stream);
            $csv = str_replace(PHP_EOL, "\r\n", stream_get_contents($stream));
            fclose($stream);
            $csv = mb_convert_encoding($csv, 'MS932', 'UTF-8');
            echo $csv;
        }, 'カテゴリマスタ_'.$date.'.csv');
    }

    /**
     * CSVファイルを保存する
     *
     * @param [type] $request
     * @param [type] $type
     * @return void
     */
    protected function saveCSVandLog($request, $type)
    {
        // DBログ保存
        $log_data = [
            'type'          => $type,
            'upload_date'   => Carbon::now(),
            'status'        => self::ERROR,
            'file_name'     => '',
            'log_file_name' => '',
            'message'       => '',
        ];
        $recode = ImportLog::create($log_data);

        $fileName = '';
        $csvFile = null;
        if($request->hasFile('upload_file')) {
            $fileUpload = $request->hasFile('upload_file') ? $request->file('upload_file') : null;
            $fileName = $fileUpload->getClientOriginalName();
            $save_directory = 'uploads/'.self::PATH_NAMES[$type].'/'.$recode->id.'/csv';
            $path = $request->upload_file->storeAs($save_directory, $fileName);

            // 一括エンコードのために一時ファイル使用
            $data = File::get(storage_path('app/' . $path));
            $data = mb_convert_encoding($data, 'UTF-8', 'MS932');
            $temp = tmpfile();
            $meta = stream_get_meta_data($temp);
            fwrite($temp, $data);
            rewind($temp);

            $csvFile = new SplFileObject($meta['uri'],'rb');
            $csvFile->setFlags(
                    SplFileObject::READ_CSV |
                    SplFileObject::READ_AHEAD |
                    SplFileObject::SKIP_EMPTY |
                    SplFileObject::DROP_NEW_LINE
            );
        } else {
            // エラー
            $fileName = '';
            $log_data['message'] = 'ファイルの保存に失敗しました。';
            $recode->update($log_data);
        }

        return [$csvFile, $fileName, $recode];
    }

    /**
     * ログファイルを保存する
     *
     * @param string $baseFileName
     * @param model $recode
     * @param string $log_message
     * @param string $type
     * @return void
     */
    protected function saveLogFile($baseFileName, $recode, $log_message, $type)
    {
        $logFileName = 'エラーログ.txt';
        $save_directory = 'uploads/'.self::PATH_NAMES[$type].'/'.$recode->id.'/logs/';
        Storage::makeDirectory($save_directory);
        Storage::put($save_directory . $logFileName, $log_message);
        return $logFileName;
    }

    /**
     * 得意先の取り込みを行う
     * 
     * @param Request $request
     * @return void
     */    
    public function clientsImport(Request $request) {

        // CSVから取り込む項目とDBのカラム名紐付け
        $column_relation = self::CLIENTS_COLUMN;
        $now = Carbon::now();
        $log = null;
        $status = '';
        $message = null;
        $logFileName = null;
        $index = 1;
        $column_names = [];
        $successCount = 0;
        $errorCount = 0;
        $skipCount = 0;
        $total_log_message = '';

        //ユーザーマスタ取得
        $users = User::select(['id', 'code'])->get()->all();

        // CSVファイルのアップロードと初期ログテーブル登録
        list($csvFile, $fileName, $log) = $this->saveCSVandLog($request, ImportService::CLIENTS);

        if (!$fileName) {
            return;
        }

        foreach($csvFile as $row) {

            // 最初の行は項目名とカラム名を紐付けてcontinue
            if($index == 1) {
                foreach($row as $k => $v) {
                    if(isset($column_relation[$v])) {
                        $column_names[$k] = $column_relation[$v];
                    }
                }
                $index++;
                continue;
            }

            // ２行目以降はkeyとvalueまとめてudpateOrCreate
            $key = [];
            $values = [];
            $result = 'ok';
            $log_message = '';
            $is_skip = false;
            foreach($row as $k => $v) {
                if (isset($column_names[$k])) {
                    // 項目が電話番号、かつ入力値が空の場合はスキップ
                    if(!(empty(trim($v)) && $column_names[$k] == 'tel')){
                        if (empty(trim($v))) {
                            $result = 'ng';
                            $log_message = $index . '行目：[' . array_search($column_names[$k], self::CLIENTS_COLUMN) . '] は必須項目です';
                        } else {
                            // codeに対応するカラムだったらkeyとして扱う
    
                            if ($column_names[$k] == 'code') {
                                $key['code'] = trim($v);
                            }
                            else if($column_names[$k] == 'name'){
                                if(0 === substr_compare($v, '★', 0, 1) ){
                                    $result = 'ng';
                                    $is_skip = true;
                                    break;
                                }
                                // 半角カナは全角に変換
                                $values[$column_names[$k]] = mb_convert_kana($v, 'KV', 'UTF-8');
                            }
                            else if($column_names[$k] == 'sales_in_chage_code'){
                                //営業担当コード１がユーザーマスタに存在する場合、営業担当者idを設定
                                foreach($users as $user) {
                                    if($v === $user->code) {
                                        $values['sales_in_chage_id'] = $user->id;
                                        break;
                                    }
                                }
                            }
                            else {
                                $values[$column_names[$k]] = trim($v);
                            }
    
                            // パスワードは電話番号の下４桁をハッシュ化したものとする
                            if($column_names[$k] == 'tel') {
                                if (strlen(trim($v)) < 4) {
                                    $log_message = $index . '行目：[' . array_search($column_names[$k], self::CLIENTS_COLUMN) . '] の桁数が不正です';
                                } else {
                                    $values['password'] = substr(trim($v), -4);
                                }
                            }
                        }
                    } else {
                        $result = 'ng';
                        $is_skip = true;
                        break;
                    }
                }
            }

            if ($result == 'ok') {
                // 変更の有無チェック
                $model = $this->getClientInfo($key);
                $is_different = false;

                if ($model === null) {
                    $is_different = true;
                } else {
                    foreach ($values as $k => $v) {
                        if ($k === 'password') {
                            // パスワードは対象外
                            continue;
                        }
                        if ($v != $model[$k]) {
                            $is_different = true;
                            break;
                        }
                    }
                }

                // 変更があった場合のみDB更新
                if ($is_different) {
                    // upsertする（一括でやりたい）
                    $result = $this->clientsUpdateOrCreate($key, $values);

                    if ($result == 'ok') {
                        $successCount++;
                    } else {
                        $log_message = $index . '行目：更新に失敗しました';
                        $errorCount++;
                    }
                } else {
                    $skipCount++;
                }
            } else if ($result == 'ng' && $is_skip) {
                $skipCount++;
            } else {
                $total_log_message .= $log_message."\n";
                $errorCount++;
            }

            $index++;
        }

        if($errorCount > 0) {
            // ログファイル保存（エラーが有ったときのみ）
            $status = ImportService::ERROR;
            $logFileName = $this->saveLogFile($fileName, $log, $total_log_message, ImportService::CLIENTS);
        } else {
            $status = ImportService::SUCCESS;
        }
        
        // 取り込み結果メッセージ
        $message = "取込：$successCount 件、スキップ：$skipCount 件、エラー：$errorCount 件";

        // DBログ保存
        $log_data = [
            'upload_date' => $now,
            'status' => $status,
            'file_name' => $fileName,
            'log_file_name' => $logFileName,
            'message' => $message,
        ];
        $log->update($log_data);
    }

    /**
     * 商品の取り込みを行う
     *
     * @param Request $request
     * @return void
     */
    public function productsImport(Request $request) {

        // CSVから取り込む項目とDBのカラム名紐付け
        $column_relation = self::PRODUCTS_COLUMN;
        $now = Carbon::now();
        $log = null;
        $status = '';
        $message = null;
        $logFileName = null;
        $index = 1;
        $column_names = [];
        $successCount = 0;
        $errorCount = 0;
        $skipCount = 0;
        $deleteCount = 0;
        $total_log_message = '';

        // CSVファイルのアップロードと初期ログテーブル登録
        list($csvFile, $fileName, $log) = $this->saveCSVandLog($request, ImportService::PRODUCTS);

        if (!$fileName) {
            return;
        }

        // カテゴリマスタ取得
        $categories = Categorie::get()->all();

        foreach($csvFile as $row) {

            // 最初の行は項目名とカラム名を紐付けてcontinue
            if($index == 1) {
                foreach($row as $k => $v) {
                    if(isset($column_relation[$v])) {
                        $column_names[$k] = $column_relation[$v];
                    }
                }
                $index++;
                continue;
            }

            // ２行目以降はkeyとvalueまとめてudpateOrCreate
            $key = [];
            $values = [];
            $category_code = '';
            $result = 'ok';
            $is_skip = false;
            $log_message = '';
            $deleteResult = 'ok';
            foreach($row as $k => $v) {
                if (isset($column_names[$k])) {
                    if((is_null($v) || trim($v) === '') 
                        && ($column_names[$k] != 'name_kana' && $column_names[$k] != 'brand_name' && $column_names[$k] != 'jan_code')) {
                        $result = 'ng';
                        $log_message = $index . '行目：[' . array_search($column_names[$k], self::PRODUCTS_COLUMN) . '] は必須項目です';
                    } else {
                        // codeに対応するカラムだったらkeyとして扱う
                        if ($column_names[$k] == 'code') {
                            $key['code'] = $v;
                        }
                        else if($column_names[$k] == 'name' || $column_names[$k] == 'name_kana' || $column_names[$k] == 'brand_name'){
                            // 半角カナは全角に変換
                            $values[$column_names[$k]] = mb_convert_kana($v, 'KV', 'UTF-8');
                        }
                        else if($column_names[$k] == 'class1_code' || $column_names[$k] == 'class2_code') {
                            $values[$column_names[$k]] = str_pad($v, 2, 0, STR_PAD_LEFT);
                            $category_code .= $values[$column_names[$k]];
                        }
                        else if($column_names[$k] == 'class_div') {
                            if($v == 'A' || $v == 'D' || $v == 'H') {
                                // 定番品
                                $values[$column_names[$k]] = ClassDivDefine::STANDARD;
                            }
                            else if($v == 'C') {
                                // 季節品商品
                                $values[$column_names[$k]] = ClassDivDefine::SEASONAL;
                            }
                            else if($v == 'E') {
                                // 取り寄せ品
                                $values[$column_names[$k]] = ClassDivDefine::BACK_ORDER;
                            }
                            else {
                                // スキップ
                                $result = 'ng';
                                $is_skip = true;
                            }
                        }
                        else {
                            $values[$column_names[$k]] = trim($v);
                        }
                    }
                }
            }

            // 初期値、その他>その他を設定
            $values['category_code'] = '9909';

            // カテゴリマスタに存在するカテゴリコードの場合、csvから値を設定
            foreach ($categories as $category) {
                if ($category->category_code == $category_code) {
                    $values['category_code'] = $category_code;
                    break;
                }
            }

            // カテゴリマスタに存在しないカテゴリコードの場合、小カテゴリ、細カテゴリを再設定
            if ($values['category_code'] != $category_code) {
                $values['class1_code'] = '99';
                $values['class2_code'] = '09';
            }
            
            if ($result == 'ok') {
                $model = $this->getProductInfo($key);
                $is_different = false;
                $is_unedited = false;
                if(is_null($model)) {
                    $is_different = true;
                    $is_unedited = true;
                } else {
                    foreach($values as $k => $v) {
                        if($v != $model[$k]) {
                            $is_different = true;
                            if ($k == 'name' || $k == 'jan_code') {
                                // 商品名かJANコードが変わっている場合、未編集フラグを立てる
                                $is_unedited = true;
                                break;
                            }
                        }
                    }
                }

                // 内容に変更があるレコードのみ更新を行う
                if($is_different) {
                    // 未編集フラグを設定
                    if ($is_unedited || $model['unedited'] == 1) {
                        $values['unedited'] = UneditedDefine::UNEDITED; // 未編集とする
                    } else {
                        $values['unedited'] = 0;
                    }
                    $values['import_at'] = $now;    // 取込日時

                    $result = $this->productsUpdateOrCreate($key, $values);

                    if ($result == 'ok') {
                        $successCount++;
                    } else {
                        $log_message = $index . '行目：更新に失敗しました';
                        $errorCount++;
                    }
                } else {
                    $skipCount++;
                }
            } elseif ($result == 'ng' && $is_skip) {
                $model = $this->getProductInfo($key);
                //既にデータが存在する場合、論理削除
                if($model){
                    $deleteResult = $this->productsDelete($model);
                    if ($deleteResult == 'ok') {
                        $deleteCount++;
                    } else {
                        $log_message = $index . '行目：削除に失敗しました';
                        $total_log_message .= $log_message."\n";
                        $errorCount++;
                    }
                } else {
                    $skipCount++;
                }
            } else {
                $total_log_message .= $log_message."\n";
                $errorCount++;
            }

            $index++;
        }

        // ログファイル保存（エラーが有ったときのみ）
        if($errorCount > 0) {
            $status = ImportService::ERROR;
            $logFileName = $this->saveLogFile($fileName, $log, $total_log_message, ImportService::PRODUCTS);
        } else {
            $status = ImportService::SUCCESS;
        }

        // 取り込み結果メッセージ
        $message = "取込：$successCount 件、削除：$deleteCount 件、スキップ：$skipCount 件、エラー：$errorCount 件";

        // DBログ保存
        $log_data = [
            'upload_date' => $now,
            'status' => $status,
            'file_name' => $fileName,
            'log_file_name' => $logFileName,
            'message' => $message,
        ];
        $log->update($log_data);
    }

    /**
     * カテゴリの取り込みを行う
     * 
     * カテゴリマスタは全件洗い替えとする
     * 一件でもエラーがある場合はロールバック
     *
     * @param Request $request
     * @return void
     */
    public function categoryImport(Request $request) {
  
        // CSVから取り込む項目とDBのカラム名紐付け
        $column_relation = self::CATEGORIES_COLUMN;
        $now = Carbon::now();
        $log = null;
        $status = '';
        $message = null;
        $logFileName = null;

        // CSVファイルのアップロードと初期ログテーブル登録
        [$csvFile, $fileName, $log] = $this->saveCSVandLog($request, ImportService::CATEGORY);

        if (!$fileName) {
            return;
        }

        DB::beginTransaction();
        
        try {
            // 全件削除
            Categorie::query()->delete();

            $index = 1;
            $column_names = [];
            $successCount = 0;
            $errorCount = 0;
            $total_log_message = '';

            foreach ($csvFile as $row) {

                // 最初の行は項目名とカラム名を紐付けてcontinue
                if ($index == 1) {
                    foreach ($row as $k => $v) {
                        if (isset($column_relation[$v])) {
                            $column_names[$k] = $column_relation[$v];
                        }
                    }
                    $index++;
                    continue;
                }

                // ２行目以降はCreate
                $values = [];
                $result = 'ok';
                $log_message = '';

                foreach ($row as $k => $v) {
                    if (isset($column_names[$k])) {
                        if(is_null($v) || trim($v) === '') {
                            $result = 'ng';
                            $log_message = $index . '行目：[' . array_search($column_names[$k], self::CATEGORIES_COLUMN) . '] は必須項目です';
                        } else {
                            if ($column_names[$k] == 'category_code') {
                                $values[$column_names[$k]] = str_pad($v, 4, 0, STR_PAD_LEFT);
                            } elseif ($column_names[$k] == 'class1_code') {
                                $values[$column_names[$k]] = str_pad($v, 2, 0, STR_PAD_LEFT);
                            } elseif ($column_names[$k] == 'class2_code') {
                                $values[$column_names[$k]] = str_pad($v, 2, 0, STR_PAD_LEFT);
                            } else {
                                $values[$column_names[$k]] = $v;
                            }
                        }
                    } else {
                        $result = 'ng';
                        $log_message = ($index+1) . '行目：データがありません';
                    }
                }

                if ($result == 'ok') {
                    $result = $this->categoryCreate($values);

                    if (empty($result)) {
                        $successCount++;
                    } else {
                        $log_message = $index . '行目：'.$result;
                        $errorCount++;
                    }
                } else {
                    $total_log_message .= $log_message . "\n";
                    $errorCount++;
                }

                $index++;
            }

            if ($errorCount > 0) {
                // エラー発生時
                DB::rollback();
                $message = '取込に失敗しました。';
                $status = ImportService::ERROR;
                $logFileName = $this->saveLogFile($fileName, $log, $total_log_message, ImportService::CATEGORY);                
            } else {
                // 全件正常時
                $message = "取込：${successCount} 件";
                $status = ImportService::SUCCESS;
                DB::commit();
            }
        } catch (\Exception $e) {
            DB::rollback();

            if ($log) {
                $message = '取込に失敗しました。';
                $logFileName = $this->saveLogFile($fileName, $log, $e, ImportService::CATEGORY);
            }
        }

        // DBログ保存
        $log_data = [
            'upload_date' => $now,
            'status' => $status,
            'file_name' => $fileName,
            'log_file_name' => $logFileName,
            'message' => $message,
        ];
        $log->update($log_data);
    }
}
