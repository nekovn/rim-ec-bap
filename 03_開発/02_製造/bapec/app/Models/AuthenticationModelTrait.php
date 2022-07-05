<?php

namespace App\Models;

use Illuminate\Support\Facades\Hash;

/**
 * 認証モデル基底Traitクラス
 *
 * @category  システム共通
 * @package   App\Models
 * @copyright 2020 elseif.jp All Rights Reserved.
 * @version   1.0
 */
trait AuthenticationModelTrait
{
    /**
     * 適用されているgurad名を取得する。
     *
     * @return string gurad名
     */
    public function activeGuard()
    {
        foreach (array_keys(config('auth.guards')) as $guard) {
            if (auth()->guard($guard)->check()) {
                return $guard;
            }
        }
        return null;
    }
    /**
     * Set password attributes
     *
     * @param string $password
     * @return void
     */
    public function setPasswordAttribute($password)
    {
        if ($password) {
            $this->attributes['password'] = Hash::make($password);
        } else {
            unset($this->attributes['password']);
        }
    }
}
