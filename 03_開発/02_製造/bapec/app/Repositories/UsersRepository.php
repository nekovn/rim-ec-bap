<?php
namespace App\Repositories;


use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\UserAuth;
use Illuminate\Support\Facades\Auth;
use App\Enums\FlagDefine;

/**
 * ユーザ管理関連の処理をまとめたリポジトリクラス
 *
 * @package   App\Repositories
 * @version   1.0
 */
class UsersRepository
{
    use BaseRepository;
    use SimpleCrudRepositoryTrait;

    /**
     * 利用するModelクラスを取得する。
     */
    protected function getModel()
    {
        return User::where([]);
    }
    /**
     * ユーザマスタから、ログインIDに一致するデータを取得する。
     *
     * @access public
     * @param string $loginId ユーザーID
     * @param bool $includeDeleted 削除含む
     * @return Administrator 管理者モデル
     */
    public function findByLoginId(string $loginId, bool $includeDeleted = false): ? User
    {
        $query = User::where('login_id', $loginId);
        if (!$includeDeleted) {
            $query->withTrashed();
        }

        if ($query->count() === 0) {
            return null;
        }

        return $query->first();
    }
    /**
     * クエリーを構築する。
     * @param array $conditions 検索条件
     * @return query
     */
    protected function getQueryByConditions(array $param)
    {
        $query = User::select(['*']);
        if (isset($param['code'])) {
            $query->where('code', 'LIKE', $param['code']. '%');
        }
        if (isset($param['name'])) {
            $query->where('name', 'LIKE', '%'. $param['name'] . '%');
        }

        return $query;
    }
    /**
     * ユーザ権限マスタ取得
     * @param userid:m_user.id
     */
    public function getUserAuth($userid, $isMenuAll = true)
    {
        $query = DB::table('menus as t1')
        ->select([
            't1.program_cd', 't1.name', 't2.has_update', 't2.has_report_output', 't1.href'
        ])
        ->selectRaw('case when t2.program_cd is null then 0 else 1 end as has_read');
        if ($isMenuAll) {
            $query->leftjoin('user_auths as t2', function ($join) use ($userid) {
                $join->on('t1.program_cd', '=', 't2.program_cd')
                ->where('t2.user_id', '=', $userid);
            });
        } else {
            $query->join('user_auths as t2', function ($join) use ($userid) {
                $join->on('t1.program_cd', '=', 't2.program_cd')
                ->where('t2.user_id', '=', $userid);
            });
        }
        $query
        ->where('t1.slug', '=', 'link')
        ->whereNotNull('t1.name')
        ->orderBy('t1.sequence');

        return $query->get();
    }
    /**
     * 更新する。
     * @param array $values 更新カラムの情報。[[key => value], ・・・]
     * @param array $where 更新条件。未指定の場合、全件更新される。
     * @param boolean $checkOptimistickLock 排他制御を行うか
     * @return number 更新件数
     */
    public function updateauth(array $values, array $where = [], $checkOptimistickLock = true)
    {
        DB::transaction(
            function () use ($values, $where) {

                // ユーザ権限削除
                UserAuth::where($where)->forceDelete();

                // 登録
                if (count($values) == 0) {
                    return;
                }
                $userId = $where['user_id'];
                foreach ($values as &$value) {
                    $value['user_id'] = $userId;
                }

                return UserAuth::insert($values);
        });
    }

    public function getUserList()
    {
        $ret = [];
        $resultCodes = [];
        $query = DB::table('users')
        ->select('users.id', 'users.name')
        ->where('users.is_deleted', '=', 0)
        ->orderby('users.id');
        $resultCodes = $query->get();
        foreach ($resultCodes as $result) {
            $ret += array($result->id => $result->name);
        }
        return $ret;
   }
}
