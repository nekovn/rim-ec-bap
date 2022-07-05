<?php

namespace App\Providers;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;

use App\Enums\FlagDefine;
use App\Models\Menu;

/**
 * ユーザーのログイン処理を拡張するプロバイダークラス
 *
 */
class EloquentUserExUserProvider extends EloquentUserProvider
{
    /**
     * Create a new database user provider.
     *
     * @param  \Illuminate\Contracts\Hashing\Hasher  $hasher
     * @param  string  $model
     * @return void
     */
    public function __construct(HasherContract $hasher, $model)
    {
        parent::__construct($hasher, $model);
    }

    /**
     * 指定された識別子に該当するユーザー情報を取得する
     *
     * @param mixed $identifier 識別子
     * @return UserContract|void|null ユーザー情報
     */
    public function retrieveById($identifier)
    {
        //メニューデータ取得
        $model = $this->createModel();
        $user = $this->newModelQuery($model)
                     ->where($model->getAuthIdentifierName(), $identifier)
                     ->where('is_unusable', FlagDefine::OFF)
                     ->first();

        $user->menuAuths = Menu::getAuthFlgs($identifier);
        return $user;
    }
}
