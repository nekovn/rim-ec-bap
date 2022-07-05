<?php

namespace App\Http\Controllers\Sandbox;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\ImportLog;
use App\Enums\ImportTypeDefine as ImportTypeDefine;

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

        $path = ['app'];
        $path = ['uploads'];
        if ($log->type === ImportTypeDefine::CLIENT) {
            $path[] = 'clients';
        } else if ($log->type === ImportTypeDefine::PRODUCT) {
            $path[] = 'products';
        } else if ($log->type === ImportTypeDefine::CATEGORY) {
            $path[] = 'categories';
        }

        $path[] = $log->id;
        $path[] = 'logs';
        $path[] = $log->log_file_name;
        $filePath = implode('/', $path);

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

        $path = ['app'];
        $path = ['uploads'];
        if ($log->type === ImportTypeDefine::CLIENT) {
            $path[] = 'clients';
        } else if ($log->type === ImportTypeDefine::PRODUCT) {
            $path[] = 'products';
        } else if ($log->type === ImportTypeDefine::CATEGORY) {
            $path[] = 'categories';
        }

        $path[] = $log->id;
        $path[] = 'csv';
        $path[] = $log->file_name;
        $filePath = implode('/', $path);

        if (!Storage::exists($filePath)) {
            return null;
        }
        $mimeType = Storage::mimeType($filePath);
        $headers = [['Content-Type' => $mimeType]];

        return Storage::download($filePath, $log->file_name, $headers);
    }
}
