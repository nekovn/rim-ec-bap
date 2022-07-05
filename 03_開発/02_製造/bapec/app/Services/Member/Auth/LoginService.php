<?php
namespace App\Services\Member\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * ログイン関連の処理をまとめたサービスクラス
 */
class LoginService
{
    /**
     * コンストラクタ
     *
     */
    public function __construct()
    {
    }
    /**
     * ログイン処理を実行する。
     * @access public
     * @param  \Illuminate\Http\Request $request
     * @return boolean true:認証成功 false:認証失敗
     */
    public function login(Request $request)
    {
        // $credentials = $request->only('code', 'password');
        $credentials =$request->only('email', 'password', 'is_login_prohibited', 'is_locked');

        return Auth::attempt($credentials, false); //, $request->input('remember_me'));
    }
}
