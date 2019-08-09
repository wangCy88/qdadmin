<?php

namespace App\Http\Controllers;

use App\GrabFeedbackType;
use App\GrabFeedback;

use App\GrabSendmsg;
use Illuminate\Http\Request;

class MsgController extends Controller
{
    public function __construct(Request $request)
    {
        if (!$request->user_id) {
            exit(json_encode(['code' => 1, 'msg' => '参数错误！']));
        }
    }


    /**
     * 意见反馈类型
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function feedbackType(Request $request)
    {
        $list = GrabFeedbackType::select('id', 'type_name')->get();
        return response()->json(['code' => 0, 'msg' => 'success', 'data' => $list]);
    }

    /**
     * 提交意见反馈
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitFeedback(Request $request)
    {
        //验证意见类型
        if (!$this->typeVerify($request->type)) {
            return response()->json(['code' => 3, 'msg' => 'error type']);
        }
        //验证意见
        if (!$this->remarkVerify($request->remark)) {
            return response()->json(['code' => 4, 'msg' => 'error remark']);
        }
        //存储意见
        if (!self::saveFeedback($request)) {
            return response()->json(['code' => 7, 'msg' => 'fail']);
        }
        return response()->json(['code' => 0, 'msg' => 'success']);
    }

    /**
     * 获取意见反馈
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFeedback(Request $request)
    {
        //意见反馈记录
        $data = self::feedbackRecord($request);
        if (!$data) {
            return response()->json(['code' => 3, 'msg' => 'error data']);
        }
        return response()->json(['code' => 0, 'data' => $data]);
    }

    //获取意见反馈详情
    public static function getFeedbackDetail(Request $request)
    {
        //意见反馈详情记录
        $data = self::feedbackDetailRecord($request);
        if (!$data) {
            return response()->json(['code' => 3, 'msg' => 'error data']);
        }
        return response()->json(['code' => 0, 'data' => $data]);
    }

    //存储意见
    private static function saveFeedback($request)
    {
        $insertData = [
            'user_id' => $request->user_id,
            'type' => $request->type,
            'remark' => $request->remark,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'status' => 0,
            'answer' => ''
        ];
        $result = GrabFeedback::insert($insertData);
        return $result;
    }

    //意见反馈记录
    private static function feedbackRecord($request)
    {
        $where = ['user_id' => $request->user_id];
        $data = GrabFeedback::with(['grabFeedbackType' => function ($q) {
            $q->select('id', 'type_name');
        }])
            ->where($where)->select('id', 'type', 'remark', 'answer', 'status')->orderBy('id', 'desc')->get();
        return $data;
    }

    //意见反馈详情记录
    private static function feedbackDetailRecord($request)
    {
        if (empty($request->id)) {
            return false;
        }
        $where = ['id' => $request->id];
        $data = GrabFeedback::with(['grabFeedbackType' => function ($q) {
            $q->select('id', 'type_name');
        }])
            ->where($where)->select('id', 'type', 'remark', 'answer', 'status')->find($request->id);
        return $data;
    }

    //验证意见
    public function remarkVerify($remark)
    {
        if (empty($remark) || strlen($remark) > 255) {
            return false;
        }
        return true;
    }

    //验证意见类型
    public function typeVerify($type)
    {
        if (empty($type) && $type !== '0') {
            return false;
        }
        return true;
    }

    /**
     * 是否存在未读消息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function isRead(Request $request)
    {
        $res = GrabSendmsg::where(['user_id' => $request->user_id, 'status' => 0])->first();
        $res = $res ? 1 : 0;
        return response()->json(['code' => 0, 'data' => $res, 'msg' => 'success']);
    }

    /**
     * 消息列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function msgList(Request $request)
    {
        $list = GrabSendmsg::where('user_id', $request->user_id) -> orwhere('user_id' , 0)->orderBy('id', 'DESC')->get();
        //GrabSendmsg::where('user_id' , $request -> user_id) -> update(['status' => 1]);
        return response()->json(['code' => 0, 'data' => $list, 'msg' => 'success']);
    }

    /**
     * 读取未读消息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function msgIsRead(Request $request)
    {
        GrabSendmsg::where('user_id', $request->user_id)->update(['status' => 1]);
        return response()->json(['code' => 0, 'msg' => 'success']);
    }

    public function sendMsgToUsers(Request $request)
    {

    }

}
