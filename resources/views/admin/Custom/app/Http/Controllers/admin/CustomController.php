<?php

namespace App\Http\Controllers\admin;

use App\GrabCustom;
use App\GrabCustomFormClick;
use App\GrabCustomFrom;
use App\GrabCustomHigh;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\GrabUsers;
use App\GrabBorrowOrder;
use App\GrabUsersWallet;
use App\GrabUserCardticketDetail;
use App\GrabSendmsg;
use Illuminate\Support\Facades\DB;

class CustomController extends Controller
{
    //后台客户管理
    public function customList(Request $request)
    {
        $where = [];
        //dd($request -> input());
        $is_high = $request->is_high ? $request->is_high : '';
        $date1 = $request->date1 ? $request->date1 : '';
        $date2 = $request->date2 ? $request->date2 : '';
        $channel = $request->channel ? $request->channel : '';
        $is_rob = $request->is_rob ? $request->is_rob : '';
        $phone = $request->phone ? $request->phone : '';
        $data = GrabCustom::select();
        if ($is_high) {
            $data = $data->where('is_high', $is_high);
        }
        if ($channel) {
            $data = $data->where('channel', $channel);
        }
        if ($phone) {
            $data = $data->where('phone', 'LIKE', '%' . $phone . '%');
        }
        if ($is_rob) {
            $data = $data->where('status', $is_rob);
        }
        if ($date1 && $date2) {
            $data = $data->whereBetween('created_at', [$date1, $date2]);
        }
        $data = $data->orderBy('id', 'DESC')
            ->paginate(10);
        $channelList = config('config.channelList');
        $isHigh = config('config.isHigh');
        $isRob = config('config.isRob');
        //dd($data -> toArray());
        return view('admin.Custom.customList', compact('data', 'channelList', 'isHigh', 'isRob', 'is_high', 'channel', 'date1', 'date2', 'is_rob', 'phone'));
    }

    /**
     * 客户详情
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function customDetail(Request $request)
    {
        if ($request->isMethod('post')) {
            //dd($request -> toArray());
            if (is_array($request->location)) {
                $arr = $request->location;
            } else {
                $arr = explode(',', $request->location);
            }

            //dd($arr);
            GrabCustom::where('id', $request->id)->update(
                [
                    'sex' => $request->sex,
                    'age' => $request->age,
                    'province' => $arr[0],
                    'city' => $arr[1],
                    'area' => $arr[2],
                    'name' => $request->name,
                    'withdraw_amount' => $request->withdraw_amount,
                    'credit' => $request->credit,
                    'is_high' => $request->is_high
                ]
            );
            $res = GrabCustomHigh::where('id', $request->id)->first();
            if ($res) {
                GrabCustomHigh::where('custom_id', $request->id)->update(
                    [
                        'withdraw_amount' => $request->withdraw_amount,
                        'social' => $request->social,
                        'housing_fund' => $request->housing_fund,
                        'wages' => $request->wages,
                        'human_life' => $request->human_life,
                        'webank' => $request->webank,
                        'credit' => $request->credit,
                        'house' => $request->house,
                        'car' => $request->car,
                        'job' => $request->job
                    ]
                );
            } else {
                GrabCustomHigh::insert(
                    [
                        'custom_id' => $request->id,
                        'company' => '',
                        'withdraw_amount' => $request->withdraw_amount,
                        'social' => $request->social,
                        'housing_fund' => $request->housing_fund,
                        'wages' => $request->wages,
                        'human_life' => $request->human_life,
                        'webank' => $request->webank,
                        'credit' => $request->credit,
                        'house' => $request->house,
                        'car' => $request->car,
                        'job' => $request->job,
                        'created_at' => date('Y-m-d H:i:s')
                    ]
                );
            }

            return response()->json(['code' => 200, 'msg' => '保存成功']);
            //exit(json_encode(['code' => 200 , 'msg' => 'success']));
        } else {
            $id = $request->custom_id;
            $info = GrabCustom::select()
                ->with(['grabCustomHigh' => function ($q) {
                    $q->select();
                }])
                ->where('id', $request->custom_id)
                ->first();
            //dump($info -> toArray());
            $sexList = config('config.sexList');
            $creditList = config('config.creditList');
            if ($info->grabCustomHigh) {
                return view('admin.Custom.customDetail', compact('info', 'sexList', 'creditList', 'id'));
            } else {
                return view('admin.Custom.addHigh', compact('id', 'creditList', 'sexList'));
            }
        }
    }


    /**
     * 分派订单
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function userAssign(Request $request)
    {
        $city = GrabCustom::where('id', $request->id)->value('city');
        $grabUser = GrabUsers::/*with(['grabUsersWallet' => function($q){
                    $q -> where('card_ticket' , '>' , 0) -> select();
                }])
                    -> */
        where('city', $city)
            ->where('is_open', 1)
            ->orderBy('user_id', 'DESC')
            ->get()
            ->toArray();
        //dd($grabUser[0]['user_id']);
        if (!$grabUser) {
            //无符合条件信贷经历
            return response()->json(['code' => 1, 'msg' => '无符合条件信贷经理']);
        }
        //查看当前客户是否被该经历抢过

        //有符合条件信贷经理
        foreach ($grabUser as $k2 => $v2) {
            //dd($v2['user_id']);
            $res = GrabBorrowOrder::where(['user_id' => $v2['user_id'], 'custom_id' => $request->id])->first();
            if ($res) {
                unset($grabUser[$k2]);
            }
        }
        //dd($grabUser);
        if (empty($grabUser)) {
            return response()->json(['code' => 1, 'msg' => '无符合条件信贷经理']);
        }
        $user = $grabUser[0]['user_id'];
        //dd($user);
        try {
            //开启事务
            DB::beginTransaction();
            $is_high = GrabCustom::where('id', $request->id)->value('is_high');
            $withdraw_amount = GrabCustom::where('id', $request->id)->value('withdraw_amount');
            $price = $is_high ? 2 : 1;
            $orderNo = getOrderNo();
            //dd([$price , $orderNo]);
            //生成订单
            $res2 = GrabBorrowOrder::insert(
                [
                    'user_id' => $user,
                    'custom_id' => $request->id,
                    'order_no' => $orderNo,
                    'withdraw_amount' => $withdraw_amount,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'status' => 0,
                    'price' => $price,
                    'unit' => 'card_ticket',
                    'is_high' => $is_high
                ]
            );
            //扣款
            $res3 = GrabUsersWallet::where('user_id', $user)->decrement('card_ticket', $price);

            $cardTicket = GrabUsersWallet::where('user_id', $user)->value('card_ticket');
            //记录流水
            $res4 = GrabUserCardticketDetail::insert(
                [
                    'user_id' => $user,
                    'type' => 0,
                    'num' => '-' . $price,
                    'total_num' => $cardTicket,
                    'described' => '派单消费',
                    'order_no' => $orderNo,
                    'pay_type' => 0,
                    'created_at' => date('Y-m-d H:i:s')
                ]
            );

            //修改客户状态
            $res5 = GrabCustom::where('id', $request->id)->update(
                [
                    'status' => 1,
                    'rob_at' => date('Y-m-d H:i:s')
                ]
            );

            //发送短信/消息
            $phone = GrabCustom::where('id', $request->id)->value('phone');
            \SendMsg::sendmail($phone, '您的申请已经被处理,请注意接听来电'); // 发送给客户
            \SendMsg::sendmail($grabUser[0]['phone'], '您已抢到客户，请前往APP查看');
            $res6 = GrabSendmsg::insert(
                [
                    'user_id' => $user,
                    'content' => '您已抢到客户',
                    'created_at' => date('Y-m-d H:i:s'),
                    'status' => 0
                ]
            );

            if (!$res2 || !$res3 || !$res4 || !$res5 || !$res6) {
                DB::rollback();  //回滚
            }
            DB::commit();  //提交
            return response()->json(['code' => 0, 'msg' => '操作成功']);
        } catch (\Exception $e) {
            //echo $e -> getMessage();
            return response()->json(['code' => 1, 'msg' => $e->getMessage()]);
        }
    }


    public function customOrder(Request $request)
    {
        $status = $request->status;
        $date1 = $request->date1;
        $date2 = $request->date2;

        $data = GrabBorrowOrder::with(['grabUsers' => function ($q) {
            $q->select();
        }])
            ->with(['grabCustom' => function ($q2) {
                $q2->select();
            }])
            ->with(['grabOrderAccount' => function ($q3) {
                $q3->select();
            }]);
        if ($status) {
            $data = $data->where('status', $request->status);
        }
        if ($date1 && $date2) {
            $data = $data->whereBetween('created_at', [$date1, $date2]);
        }
        $data = $data->orderBy('id', 'DESC')
            ->paginate(10);
        //dd($data -> toArray());
        $borrowStatus = config('config.borrowStatus');
        return view('admin.User.customOrder', compact('data', 'borrowStatus', 'status', 'date1', 'date2'));
    }

    /**
     * 拒绝退单
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function exitCustomOrder(Request $request)
    {
        $info = GrabBorrowOrder::with(['grabUsers' => function ($q) {
            $q->select('id', 'phone', 'user_id');
        }])
            ->with(['grabCustom' => function ($q2) {
                $q2->select('id', 'phone');
            }])
            ->where('id', $request->id)
            ->first();
        if (!$info) {
            return response()->json(['code' => 1, 'msg' => '订单不存在']);
        }
        //dd($info -> toArray());
        if ($request->status == 2) {
            //同意退单
            try {
                DB::beginTransaction();
                //更该订单状态
                GrabBorrowOrder::where('id', $request->id)->update(['status' => 2]);
                //卡券归还
                GrabUsersWallet::where('user_id', $info->grabUsers->user_id)->increment('card_ticket', $info->price);
                //添加流水
                $cardTicket = GrabUsersWallet::where('user_id', $info->user_id)->value('card_ticket');
                GrabUserCardticketDetail::insert(
                    [
                        'user_id' => $info->user_id,
                        'type' => 2,
                        'num' => '+' . $info->price,
                        'total_num' => $cardTicket,
                        'described' => '订单退还',
                        'order_no' => $info->order_no,
                        'pay_type' => 0,
                        'created_at' => date('Y-m-d H:i:s')
                    ]
                );
                DB::commit();
                return response()->json(['code' => 0, 'msg' => '操作成功']);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['code' => 2, 'msg' => $e->getMessage()]);
            }
        } else {
            //拒绝退单
            GrabBorrowOrder::where('id', $request->id)->update(['status' => 0]);
            return response()->json(['code' => 0, 'msg' => '操作成功']);
        }
    }


    public function customFormList()
    {
        $data = GrabCustomFrom::orderBy('id', 'DESC')->paginate(10);
        $formStatus = config('config.formStatus');
        return view('admin.Custom.userFormList', compact('data', 'formStatus'));
    }

    /**
     * 添加信贷渠道
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function CustomFromAdd(Request $request)
    {
        if ($request->isMethod('post')) {
            GrabCustomFrom::insert(
                [
                    'name' => trim($request->name),
                    'username' => trim($request->username),
                    'password' => md5(trim($request->password)),
                    'code' => strtoupper(getRandomStr(8, false)),
                    'status' => 1,
                    'uv_ratio' => $request->uv_ratio,
                    'reg_ratio' => $request->reg_ratio
                ]
            );
            return response()->json(['code' => 0, 'msg' => '添加成功']);
        } else {
            return view('admin.Custom.CustomFromAdd');
        }
    }

    /**
     * 编辑渠道
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function customFormEdit(Request $request)
    {
        if ($request->isMethod('post')) {
            //dd($request -> input());
            $data = $request->input();
            if (!$request->password) {
                unset($data['password']);
            } else {
                $data['password'] = md5(trim($data['password']));
            }
            GrabCustomFrom::where('id', $request->id)->update($data);
            return response()->json(['code' => 0, 'msg' => '操作成功']);
        } else {
            $info = GrabCustomFrom::where('id', $request->id)->first();
            return view('admin.Custom.customFormEdit', compact('info'));
        }
    }

    /**
     * 开启/关闭
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function customFormStatus(Request $request)
    {
        $status = GrabCustomFrom::where('id', $request->id)->value('status');
        $status = $status == 1 ? 0 : 1;
        //dd($request -> input());
        GrabCustomFrom::where('id', $request->id)->update(['status' => $status]);
        return response()->json(['code' => 0, 'msg' => '操作成功']);
    }


    /**
     * 用户渠道监控
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function customFormSee(Request $request)
    {
        $data = GrabCustomFormClick::orderBy('id', 'DESC')->paginate(2);
        return view('admin.Custom.customFormSee', compact('data'));
    }
}
