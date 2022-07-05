<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\ImportLog;
use App\Services\Admin\ImportService;
use App\Services\Admin\FileUtils;

/**
 * インポート画面コントローラートレイト
 */
trait ImportControllerTrait
{
    /**
     * ログファイルダウンロード処理
     *
     * @param Request $request
     * @param [type] $id インポートログid
     * @return void
     */
    public function log(Request $request, $id)
    {
        $log = ImportLog::find($id);
        if (!$log) {
            return null;
        }

        $propId = ImportService::TYPE_SETTING[$log->type]['name'];
        $filePath = FileUtils::getDownLoadSaveDirPath($propId, $id) . '/' . $log->log_file_name;

        if (!Storage::exists($filePath)) {
            return null;
        }
        $mimeType = Storage::mimeType($filePath);
        $headers = [['Content-Type' => $mimeType]];

        return Storage::download($filePath, $log->log_file_name, $headers);
    }

    /**
     * ファイルダウンロード処理
     *
     * @param Request $request
     * @param [type] $id インポートログid
     * @return void
     */
    public function file(Request $request, $id)
    {
        $log = ImportLog::find($id);
        if (!$log) {
            return null;
        }

        $propId = ImportService::TYPE_SETTING[$log->type]['name'];
        $filePath = FileUtils::getUpLoadSaveDirPath($propId, $id) .'/'.$log->file_name;

        if (!Storage::exists($filePath)) {
            return null;
        }
        $mimeType = Storage::mimeType($filePath);
        $headers = [['Content-Type' => $mimeType]];

        return Storage::download($filePath, $log->file_name, $headers);
    }
}
