<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use App\Models\CodeValue;
use App\Enums\CodeDefine;
use App\Services\Member\MembersService;
use App\Http\Requests\MembersUpdateRequest;

class MembersController extends Controller
{
    /**
     * コンストラクタ
     */
    public function __construct(MembersService $memberService)
    {
        $this->memberService = $memberService;
    }

    /**
     * マイページトップを表示する
     */
    public function home(Request $request)
    {
        $fullname = $request->user()->full_name;
        
        return view('member.membersHome',['fullname' => $fullname]);
    }

    /**
     * 顧客情報取得
     */
    public function edit()
    {
        $userinfo = Auth::user();
        $newCustomer = new Customer();
        $customerInfo = $newCustomer->getCustomerInfo($userinfo->id);
        $newpref = new CodeValue();
        $preflist = $newpref->getCodeValue(CodeDefine::PREF_CD);
        return view('member.membersEdit',['customerInfo' => $customerInfo,'preflist' => $preflist]);
    }

    /**
     * 顧客情報更新
     */
    public function update(MembersUpdateRequest $request)
    {
        $newpref = new CodeValue();

        $Customer = Customer::find(Auth::user()->id);

        $Customer->surname        = $request->surname;
        $Customer->name           = $request->name;
        $Customer->surname_kana   = $request->surname_kana;
        $Customer->name_kana      = $request->name_kana;
        $Customer->full_name      = $request->surname . $request->name;
        $Customer->full_name_kana = $request->surname_kana . $request->name_kana;
        $Customer->gender         = $request->gender;
        $Customer->birthday_year  = $request->birthday_year;
        $Customer->birthday_month = $request->birthday_month;
        $Customer->birthday_day   = $request->birthday_day;
        $Customer->zip            = $request->zip;
        $Customer->prefcode       = $request->prefcode;
        $Customer->addr_1         = $request->addr_1;
        $Customer->addr_2         = $request->addr_2;
        $Customer->addr_3         = $request->addr_3;
        $Customer->addr           = $newpref->getPrefName($request->pref) . $request->addr_1 . $request->addr_2 . $request->addr_3;
        $Customer->tel            = $request->tel;
        $Customer->email          = $request->email;

        if(!is_null($request->password)){
            $Customer->password   = $request->password;
        }

        //顧客情報を更新する。
        $Customer->save();

        return redirect()->route('members.edit');
    }
}
