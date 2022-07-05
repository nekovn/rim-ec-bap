<?php
namespace App\Services\Admin;

use Illuminate\Support\Facades\Hash;

use App\Aspect\Annotation\Transactional;
use App\Repositories\UsersRepository;
use App\Services\SimpleCrudServiceTrait;

/**
 * ユーザ管理関連の処理をまとめたサービスクラス
 *
 * @package   App\Services
 * @version   1.0
 */
class UsersService
{
    use SimpleCrudServiceTrait;

    /**
     * コンストラクタ
     *
     * @access public
     * @param UsersRepository $usersRepository ユーザリポジトリ
     */
    public function __construct(
        UsersRepository $usersRepository
    ) {
        $this->repository = $usersRepository;
    }

    /**
     * 編集権限データを取得する。
     *
     * @access public
     * @param number $id
     * @return array
     */
    public function getAuthData($id)
    {
        $aryUserdata['data-userauth'] = $this->repository->getUserAuth($id);

        return $aryUserdata;
    }
    /**
     * データを更新する。
     *
     * @access public
     * @param number $id m_user.id
     * @param array $params パラメーター
     * @return Model
     * @Transactional()
     */
    public function updateAuth($id, array $params)
    {
        $where = ['user_id' => $id];
        return $this->repository->updateAuth($params, $where);
    }
}
