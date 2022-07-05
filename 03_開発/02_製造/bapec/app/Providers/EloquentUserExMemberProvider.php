<?php

namespace App\Providers;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;

/**
 * 会員のログイン処理を拡張するプロバイダークラス
 *
 */
class EloquentUserExMemberProvider extends EloquentUserProvider
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
}
