<?php
namespace App\Services\Admin\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * ログイン関連の処理をまとめたサービスクラス
 */
class LoginService
{
    /**
     * ログイン処理を実行する。
     * @access public
     * @param  \Illuminate\Http\Request $request
     * @return boolean true:認証成功 false:認証失敗
     */
    public function login(Request $request)
    {
        $credentials = $request->only('code', 'password');
        return Auth::guard('admin')->attempt($credentials, true);
    }
}
