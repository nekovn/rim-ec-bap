<?php
namespace App\Repositories;

use App\Models\ImportLog;
use App\Enums\ImportStatusDefine;
use Illuminate\Support\Carbon;

/**
 * メーカー関連の処理をまとめたリポジトリクラス
 *
 * @package   App\Repositories
 * @version   1.0
 */
class ImportLogsRepository
{
    use BaseRepository;

    /**
     * 利用するModelクラスを取得する。
     */
    protected function getModel()
    {
        return ImportLog::where([]);
    }
    /**
     * 実行可能なタスクを取得する
     * @param number $type 
     * @param number $logId
     * @return array
     */
    public function getExecutableTask($type, $logId = null)
    {
        $query = $this->getModel()
        ->select(['*'])
            ->where('status', '=', ImportStatusDefine::WAIT_EXEC)
            ->where('type', '=', $type);
        if (isset($logId)) {
            $query->where('id', '=', $logId);
        } else {
            $query->where(function ($query) {
                $query->orWhereNull('upload_date')
                ->orWhere('upload_date', '<', Carbon::now());
            });
        }
        $query->orderBy('id', 'asc');

        return $query->get();
    }
    /**
     * import_logsテーブル更新
     * @param logId ＩＤ
     * @param statusCd ステータスコード
     * @param message メッセージ
     * @param updateParams 更新項目[]
     * @return
     */
    public function updateImpotStatus($logId, $statusCd, $message = null, $updateParam = [])
    {
        $param = array_merge(['message' => $message], $updateParam);
        // 個別パラメータ
        if ($statusCd !== null) {
            $param = array_merge($param, ['status' => $statusCd]);
            switch ($statusCd) {
                case ImportStatusDefine::WAIT_EXEC:
                    break;
                case ImportStatusDefine::SUCCESS:
                    // $param = array_merge($param, ['ends_at' => now()]); 
                    $param = array_merge($param, ['upload_date' => now()]);
                    break;
                case ImportStatusDefine::ABORT:
                    // $param = array_merge($param, ['ends_at' => now()]);
                    $param = array_merge($param, ['upload_date' => now()]);
                    break;
                case ImportStatusDefine::EXECUTING:
                    $param = array_merge($param, ['upload_date' => now()]);
                    break;
                case ImportStatusDefine::SOME_ERROR:
                    // $param = array_merge($param, ['ends_at' => now()]); 
                    $param = array_merge($param, ['upload_date' => now()]);
                    break;
                default:
                    break;
            }
        }
        // 更新
        $this->getModel()->where('id', $logId)
        ->update($param);
        return;
    }
}