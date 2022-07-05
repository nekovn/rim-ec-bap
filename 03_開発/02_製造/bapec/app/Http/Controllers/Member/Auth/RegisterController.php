<?php

namespace App\Http\Controllers\Member\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Http\Controllers\Controller;
use App\Services\Member\MembersService;
use App\Http\Requests\MembersRegisterRequest;
use App\Http\Controllers\Member\Auth\AuthenticatesMembersTrait;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */
    use RegistersUsers;
    use AuthenticatesMembersTrait;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(MembersService $membersService)
    {
        $this->service = $membersService;
        $this->middleware('guest');
    }
    /**
     * Get the guard to be used during registration.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('member');
    }
    /**
     * 会員登録画面を表示する。
     * 
     * @return string View名
     */
    public function showRegistrationForm()
    {
        return view('member.auth.register-entry');
    }

    /**
     * 登録処理
     *
     * @param  MembersRegisterRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function register(MembersRegisterRequest $request)
    {
        $customer = $this->service->store($request);
        $this->guard()->login($customer, false);
        \Session::flash('success', '登録が完了しました');

        return $this->sendLoginResponse($request);
    }

    /**
     * ログイン後の遷移先
     */
    protected function redirectTo()
    {
        return route('member.shopTop');
    }
}
