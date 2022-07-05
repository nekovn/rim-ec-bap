<?php

namespace App\Http\Controllers\Member\Auth;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\CustomerBehavior;

trait AuthenticatesMembersTrait
{
    use AuthenticatesUsers;

    /**
     * ログイン時処理
     *
     * @param Request $request
     * @param [type] $client
     * @return void
     */
    protected function authenticated(Request $request, $member)
    {
        // 顧客行動登録
        CustomerBehavior::putBehavior([
            'customer_id' => $member->id,
            'useragent' => $request->header('User-Agent'),
            'last_logined_at' => Carbon::now()
        ]);

        // カート属性を初期化
        \Cart::initializeCartAttribute();
    }

    /**
     * ログイン後の遷移先
     *
     * @return void
     */
    protected function redirectTo()
    {
        return route('member.shopTop');
    }
}
