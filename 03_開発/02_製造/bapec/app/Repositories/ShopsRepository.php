<?php

namespace App\Repositories;

use App\Models\Shop;

/**
 * ショップ基本情報管理関連の処理をまとめたリポジトリクラス
 *
 * @package   App\Repositories
 * @version   1.0
 */
class ShopsRepository
{
    use BaseRepository;

    /**
     * 利用するModelクラスを取得する。
     */
    protected function getModel()
    {
        return Shop::where([]);
    }

    /**
     * ショップ基本情報 を取得する。
     */
    public function find($id)
    {
        return Shop::findOrFail($id);
    }
}
