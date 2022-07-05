<?php
namespace App\Repositories;

use App\Models\CustomerPointLog;

/**
 * 顧客ポイント履歴リポジトリクラス
 *
 * @package   App\Repositories
 * @version   1.0
 */
class CustomerPointLogsRepository
{
    use BaseRepository;

    /**
     * 利用するModelクラスを取得する。
     */
    protected function getModel()
    {
        return CustomerPointLog::where([]);
    }
}
