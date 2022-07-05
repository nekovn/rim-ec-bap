<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GMOTestController extends Controller
{
    //テスト用
    public function GMOTest()
    {
        return view('member.gmo',[]);
    }
}
