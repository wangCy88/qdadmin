<?php

namespace App\Http\Controllers\admin;

use App\GrabFeedback;
use App\GrabSendmsg;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MsgController extends Controller
{
    //后台消息管理控制器


    /**
     * 意见反馈列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function msgList(Request $request)
    {
        $data = GrabFeedback::with(['grabFeedbackType' => function ($query) {
            $query->select('id', 'type_name');
        }])
            ->with(['grabUsersPre' => function ($q2) {
                $q2->select('id', 'phone');
            }])
            ->orderBy('id', 'DESC')
            ->paginate(10);
        //dd($data -> toArray());
        $msgStatus = config('config.msgStatus');
        return view('admin.Msg.msgList', compact('data', 'msgStatus'));
    }

    /**
     * 回复反馈
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function sendMsgToUser(Request $request)
    {
        if ($request->isMethod('post')) {
            GrabFeedback::where('id', $request->id)->update(['answer' => $request->answer, 'status' => 1]);
            return response()->json(['code' => 0, 'msg' => '回复成功']);
        } else {
            $info = GrabFeedback::where('id', $request->id)->first();
            return view('admin.Msg.sendMsgToUser', compact('info'));
        }
    }

    /**
     * 发送群消息
     * 
     */
    public function sendMsgToUsers(Request $request)
    {
        if ($request->isMethod('post')) {
            GrabSendmsg::insertGetId(['content' => $request->content]);
            return response()->json(['code' => 0, 'msg' => '发送成功']);
        } else {
            return view('admin.Msg.sendMsgToUsers');
        }
    }
}
