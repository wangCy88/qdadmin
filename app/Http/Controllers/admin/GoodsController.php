<?php

namespace App\Http\Controllers\admin;

use App\GrabCardTicket;
use App\GrabPoints;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GoodsController extends Controller
{
    //商品管理controller

    /**
     * 卡券列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function cardTicketList(Request $request)
    {
        $data = GrabCardTicket::orderBy('id', 'DESC')->paginate(10);
        $cardTicketStatus = config('config.cardTicketStatus');
        return view('admin.Goods.cardTicketList', compact('data', 'cardTicketStatus'));
    }

    /**
     * 上下架
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Request $request)
    {
        $status = GrabCardTicket::where('id', $request->id)->value('status');
        $status = $status ? 0 : 1;
        GrabCardTicket::where('id', $request->id)->update(['status' => $status]);
        return response()->json(['code' => 0, 'msg' => '操作成功']);
    }

    /**
     * 编辑卡券
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function updateCardTicket(Request $request)
    {
        if ($request->isMethod('post')) {
            GrabCardTicket::where('id', $request->id)->update($request->input());
            return response()->json(['code' => 200, 'msg' => '操作成功']);
        } else {
            $info = GrabCardTicket::where('id', $request->id)->first();
            return view('admin.Goods.updateCardTicket', compact('info'));
        }
    }

    /**
     * 添加卡券
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function addCardTicket(Request $request)
    {
        if ($request->isMethod('post')) {
            //dd($request -> input());
            GrabCardTicket::insert($request->input());
            return response()->json(['code' => 200, 'msg' => '操作成功']);
        } else {
            return view('admin.Goods.addCardTicket');
        }
    }


    /**
     * 积分列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function pointsList(Request $request)
    {
        $data = GrabPoints::orderBy('id', 'DESC')->paginate(10);
        $cardTicketStatus = config('config.cardTicketStatus');
        return view('admin.Goods.pointsList', compact('data', 'cardTicketStatus'));
    }

    /**
     * 添加积分产品
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function addPoints(Request $request)
    {
        if ($request->isMethod('post')) {
            //dd($request -> input());
            GrabPoints::insert($request->input());
            return response()->json(['code' => 200, 'msg' => '操作成功']);
        } else {
            return view('admin.Goods.addPoints');
        }
    }

    /**
     * 编辑积分产品
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function updatePoints(Request $request)
    {
        if ($request->isMethod('post')) {
            GrabPoints::where('id', $request->id)->update($request->input());
            return response()->json(['code' => 200, 'msg' => '操作成功']);
        } else {
            $info = GrabPoints::where('id', $request->id)->first();
            return view('admin.Goods.updatePoints', compact('info'));
        }
    }

    /**
     * 积分上下架操作
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function pointsStatus(Request $request)
    {
        $status = GrabCardTicket::where('id', $request->id)->value('status');
        $status = $status ? 0 : 1;
        GrabPoints::where('id', $request->id)->update(['status' => $status]);
        return response()->json(['code' => 0, 'msg' => '操作成功']);
    }
}
