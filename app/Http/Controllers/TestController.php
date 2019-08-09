<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    //测试模块
    public function test(Request $request)
    {
        \SendMsg::sendmail('17639811057' , '【帮带客】您的申请已通过，请注意接听客服电话。');
    }
}
