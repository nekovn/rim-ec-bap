<?php

namespace App\Services\Admin;

/** 
 * 　ファイルの操作に関する処理を行う
 */
class FileUtils
{
    /**
     * Upload出力パス（DB出力値）
     * @param  $propId:batchName
     */
    public static function getUpLoadDirPath($propId, $logsId)
    {
        return  'uploads/' . $propId . '/' . $logsId;
    }
    /**
     * Upload出力パス(ファイル保存時)
     */
    public static function getUpLoadSaveDirPath($propId, $logsId)
    {
        return 'public/' . FileUtils::getUpLoadDirPath($propId, $logsId);
    }
    /**
     * Download出力パス（DB出力値）
     */
    public static function getDownLoadDirPath($propId, $logsId)
    {
        return 'uploads/' . $propId . '/' . $logsId. '/logs';
    }
    /**
     * Download出力パス(ファイル保存時)
     */
    public static function getDownLoadSaveDirPath($propId, $logsId)
    {
        return 'public/' . FileUtils::getDownLoadDirPath($propId, $logsId);
    }

    /**
     * 改行コードを$toに変換する
     */
    public static function convertEOL($string, $to = PHP_EOL) {
        return preg_replace("/\r\n|\r|\n/", $to, $string);
    }

    /**
     * CSV文字列を分割して配列に格納
     */
    public static function getAryCsv($line) {
        $quoteSw = 0;
        $tmpStr = '';
        $aryCsv = [];
        for ($i = 0; $i < strlen($line); $i++) {
            $strone = mb_substr($line, $i, 1);
            //前後文字
            $befstr = '';
            $afstr = '';
            if ($i > 0) {
                $befstr = mb_substr($line, $i - 1, 1);
            }
            if ($i < strlen($line) - 1) {
                $afstr = mb_substr($line, $i + 1, 1);
            }
            if ($strone == '"' && $befstr != '"' && $afstr != '"') {
                $quoteSw += 1;
                continue;
            } else if ($strone == ',') {
                if ($quoteSw == 0 || $quoteSw == 2) {
                    $aryCsv[] = $tmpStr;
                    $tmpStr = '';
                    $quoteSw = 0;
                    continue;
                }
            }
            $tmpStr .= $strone;
            if ($i == strlen($line) - 1 && $tmpStr != '') {
                $aryCsv[] = $tmpStr;
            }
        }
        return $aryCsv;
    }
}
