<?php

namespace App\Http\Controllers\Member\Auth;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

use App\Helpers\Util\SystemHelper;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Services\Member\Auth\LoginService;
use App\Http\Controllers\Member\Auth\AuthenticatesMembersTrait;

/**
 * ログイン画面のコントローラー
 */
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesMembersTrait;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::MEMBER_HOME;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(LoginService $loginService)
    {
        $this->loginService = $loginService;
        $this->middleware('guest')->except('logout');
    }
    /**
     * 使用するguardを指定する。
     *
     * @return void
     */
    protected function guard()
    {
        return Auth::guard('member');
    }
    /**
     * ログイン画面を表示する。
     * @access protected
     * @return string View名
     */
    protected function showLoginForm(): string
    {
        if (config('app.app_only')) {
            return view('member/info');
        } else {
            return view('member/auth/login');
        }
    }
    /**
     * ログイン認証で使用するユーザーIDのカラム名を指定する。
     * @access public
     * @return string
     */
    public function username()
    {
        $code = request()->input('code');
        $field = 'email';
        request()->merge([$field=>$code, 'is_login_prohibited'=>0, 'is_locked'=>0]);
        return $field;
    }
    /**
     * ログインを実行する。
     * （Attempt to log the user into the application.）
     * @access protected
     * @param  \Illuminate\Http\Request $request リクエスト
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        $auth = $this->loginService->login($request);
        return $auth;
    }
    /**
     * ログアウトの処理を行う。
     * @access public
     * @param  \Illuminate\Http\Request $request リクエスト
     * @return ログイン画面
     */
    public function logout(Request $request)
    {
        $request->session()->flush();
        Auth::guard('member')->logout();
        return redirect()->route('member.login');
    }
    /**
     * ログイン失敗メッセージ
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            SystemHelper::getMessage('messages.E.login.fail', 
            ['login_id' => 'メールアドレス'])
        ]);
    }
}
