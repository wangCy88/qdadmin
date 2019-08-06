<?php

namespace App\Http\Controllers;

use App\GrabBorrowOrder;
use App\GrabCustom;
use App\GrabCustomHigh;
use App\GrabCustomHign;
use App\GrabOrderAccount;
use App\GrabSendmsg;
use App\GrabUserCardticketDetail;
use App\GrabUsersPre;
use App\GrabUsersWallet;
use App\MerchantWithdrawApply;
use Illuminate\Http\Request;
use App\MerchantUsersPre;
use App\MerchantUsers;
use App\GrabUsers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use App\MerchantUsersPre2;

class CustomController extends Controller
{
    public function __construct(Request $request)
    {
        //dd($request -> toArray());
        if (!$request->user_id) {
            exit(json_encode(['code' => 1, 'msg' => '参数错误！']));
        }
    }



    /**
     * 新增够你花用户
     * @param Request $request
     */
    public function gouNhCustom(Request $request)
    {
        $user = GrabCustom::select('phone')->get();
        $phone = [];
        if ($user) {
            foreach ($user as $value) {
                $phone[] = $value->phone;
            }
        }
        //dd($phone);
        $list = MerchantUsersPre::with(['merchantUserStep' => function ($q) {
            $q->select();
        }])
            /*
            -> with(['merchantUsers' => function($q2){
                $q2 -> select('name' , 'id_number' , 'income' , 'pay_day');
            }])
            -> with(['merchantUsersEx' => function($q3){
                $q3 -> select('property' , 'car' , 'security' , 'fund');
            }])*/
            ->select('phone', 'mchid', 'province', 'city', 'area', 'data_status', 'id')
            ->whereNotIn('phone', $phone)
            //-> orderBy('id' , 'desc')
            ->take(10)
            ->get();
        if (!empty($list)) {
            $arr = [];
            $arr2 = [];
            foreach ($list as $v) {
                if ($v->data_status) {
                    $info = MerchantUsers::where(['phone' => $v->phone, 'mchid' => $v->mchid])
                        ->select('id', 'phone', 'name', 'id_number', 'income', 'pay_day')
                        ->first();
                    //dd($info -> toArray());
                    $arr[] = [
                        'phone' => $v->phone,
                        'channel' => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                        'is_high' => 1,
                        'channel_id' => $info->id,
                        'sex' => \idNumber::get_sex($info->id_number),
                        'age' => \idNumber::get_age($info->id_number),
                        'province' => $v->province,
                        'city' => $v->city,
                        'area' => $v->area,
                        'name' => $info->name,
                        'status' => 0,
                        'price' => 2,
                        'unit' => 'card_ticket',
                        'withdraw_amount' => rand(2, 5) * 10000
                    ];
                    //dd($arr);
                } else {
                    $arr[] = [
                        'phone' => $v->phone,
                        'channel' => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                        'status' => 0,
                        'province' => $v->province,
                        'city' => $v->city,
                        'area' => $v->area,
                        'is_high' => 0,
                        'channel_id' => null,
                        'sex' => null,
                        'age' => null,
                        'name' => null,
                        'price' => 1,
                        'unit' => 'card_ticket',
                        'withdraw_amount' => rand(2, 5) * 10000
                    ];
                }
            }
            //dd($arr);
            //echo GrabCustom::insert($arr);
            try {
                echo GrabCustom::insert($arr);
            } catch (\Exception $e) {
                echo $e->getMessage();
            }

        }
        //dd($list -> toArray());
    }


    public function xiaohuaCustom(Request $request)
    {
        $user = GrabCustom::select('phone')->get();
        $phone = [];
        if ($user) {
            foreach ($user as $value) {
                $phone[] = $value->phone;
            }
        }
        //dd($phone);
        $list = MerchantUsersPre2::whereNotIn('phone', $phone)
            ->whereNotNull('location')
            ->where('location', '!=', '')
            ->select('phone', 'location')
            ->orderBy('id', 'DESC')
            ->take(10)
            ->get();
        $arr = [];
        if ($list) {
            foreach ($list as $v) {
                $arr2 = explode(',', $v->location);
                //dd($arr2);
                if (count($arr2) < 2) {
                    continue;
                }
                $arr[] = [
                    'phone' => $v->phone,
                    'channel' => 2,
                    'created_at' => date('Y-m-d H:i:s'),
                    'status' => 0,
                    'province' => $arr2[0],
                    'city' => $arr2[1],
                    'area' => isset($arr2[2]) ? $arr2[2] : '',
                    'is_high' => 0,
                    'channel_id' => null,
                    'sex' => null,
                    'age' => null,
                    'name' => null,
                    'price' => 1,
                    'unit' => 'card_ticket',
                    'withdraw_amount' => rand(2, 5) * 10000
                ];
            }
            if ($arr) {
                echo GrabCustom::insert($arr);
                echo '<br>';
            };
        }
        echo 'success';
        //dd($arr);
    }

    /**
     * 补充资料
     * @param Request $request
     */
    public function gouNhHigh(Request $request)
    {
        $list = GrabCustomHign::select('custom_id')->get();
        $idArr = [];
        if ($list) {
            foreach ($list as $v) {
                $idArr[] = $v->custom_id;
            }
        }
        $list2 = GrabCustom::where('is_high', 1)->whereNotIn('id', $idArr)->get();
        if (!empty($list2)) {
            $arr = [];
            foreach ($list2 as $v2) {
                $info = MerchantUsers::with(['merchantUsersEx' => function ($q2) {
                    $q2->select();
                }])
                    ->where('id', $v2->channel_id)
                    ->first();
                //dd($info -> toArray() );
                $arr[] = [
                    'custom_id' => $v2->id,
                    'company' => $info->merchantUsersEx->company,
                    'withdraw_amount' => rand(2, 5) * 10000,
                    'social' => $info->merchantUsersEx->security,
                    'housing_fund' => $info->merchantUsersEx->fund,
                    'house' => $info->merchantUsersEx->property ? 1 : 0,
                    'car' => $info->merchantUsersEx->car,
                    'job' => '职员',
                    'created_at' => date('Y-m-d H:i:s')
                ];
            }
            try {
                echo GrabCustomHign::insert($arr);
            } catch (\Exception $e) {
                echo $e->getMessage();
            }

        }
        echo 'success';
    }


    /**
     * 自动分配客户给信贷经理
     * @param Request $request
     */
    public function customAssign(Request $request)
    {
        $list = GrabCustom::where('status', 0)->orwhere(function ($q) {
            $q->where('status', 1)->where('rob_at', '<', date('Y-m-d H:i:s', strtotime('-7 days')));
        })
            ->select('id', 'status', 'city', 'is_high', 'phone', 'withdraw_amount')
            ->take(10)
            ->get();
        //dd($list -> toArray());
        if ($list) {
            foreach ($list as $v) {

                $grabUser = GrabUsers::/*with(['grabUsersWallet' => function($q){
                    $q -> where('card_ticket' , '>' , 0) -> select();
                }])
                    -> */
                where('city', $v->city)
                    ->where('is_open', 1)
                    ->orderBy('user_id', 'DESC')
                    ->get()
                    ->toArray();
                //dd($grabUser[0]['user_id']);
                if (!$grabUser) {
                    //无符合条件信贷经历
                    continue;
                }
                //查看当前客户是否被该经历抢过

                //有符合条件信贷经理
                foreach ($grabUser as $k2 => $v2) {
                    //dd($v2['user_id']);
                    $res = GrabBorrowOrder::where(['user_id' => $v2['user_id'], 'custom_id' => $v->id])->first();
                    if ($res) {
                        unset($grabUser[$k2]);
                    }
                }
                if (empty($grabUser)) {
                    continue;
                }
                $user = $grabUser[0]['user_id'];
                //dd($user);
                try {
                    //开启事务
                    DB::beginTransaction();
                    $price = $v->is_high ? 2 : 1;
                    $orderNo = getOrderNo();
                    //dd([$price , $orderNo]);
                    //生成订单
                    $res2 = GrabBorrowOrder::insert(
                        [
                            'user_id' => $user,
                            'custom_id' => $v->id,
                            'order_no' => $orderNo,
                            'withdraw_amount' => $v->withdraw_amount,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                            'status' => 0,
                            'price' => $price,
                            'unit' => 'card_ticket',
                            'is_high' => $v->is_high
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
                    $res5 = GrabCustom::where('id', $v->id)->update(
                        [
                            'status' => 1,
                            'rob_at' => date('Y-m-d H:i:s')
                        ]
                    );

                    //发送短信/消息
                    \SendMsg::sendmail($v->phone, '【帮带客】您的申请已通过，请注意接听客服电话。'); // 发送给客户
                    \SendMsg::sendmail($grabUser[0]['phone'], '【帮带客】您已成功抢单，请登陆APP查看。'); //发送给信贷经理
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
                        continue;
                    }
                    DB::commit();  //提交
                    //echo 'success';die;
                } catch (\Exception $e) {
                    echo $e->getMessage();
                }
            }
            echo 'success';
        }
    }

    /**
     * 普通单列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function customList(Request $request)
    {
        $list = GrabCustom::where('is_high', 0);
            if($request -> city){
                $list = $list -> where('city' , $request -> city);
            }

            $list = $list->select('id', 'name', 'phone', 'created_at', 'province', 'city', 'status', 'rob_at')
            ->orderBy('id', 'DESC')
            ->paginate(15);
        if ($list) {
            //dd($list -> toArray());
            $list = $list->toArray();
            foreach ($list['data'] as $k => $v) {
                //dd($v);
                if (!$v['name']) {
                    $list['data'][$k]['name'] = 'XX';
                }
                $time = time2string(ceil(time() - strtotime($v['created_at'])));
                $list['data'][$k]['time'] = $time;
                $isRob = 0;
                if ($v['rob_at']) {
                    $time2 = (time() - strtotime($v['rob_at'])) / 60 * 60 * 24;
                    if ($time2 < 7) {
                        $isRob = 1;
                    }
                }
                $list['data'][$k]['isRob'] = $isRob;
                $order = GrabBorrowOrder::where(['user_id' => $request->user_id, 'custom_id' => $v['id']])->first();
                if (!$order) {
                    $list['data'][$k]['phone'] = yc_phone($v['phone']);
                }
            }
        }
        //dd($list);
        return response()->json(['code' => 0, 'msg' => 'success', 'data' => $list]);
    }

    //优质单
    public function highCustomList(Request $request)
    {
        $list = GrabCustom::with(['grabCustomHigh' => function ($q) {
            $q->select();
        }])
            ->where('is_high', 1);
        if ($request->city) {
            $list = $list->where('city', $request->city);
        }
        if ($request->minAge && $request->maxAge) {
            $list = $list->whereBetween('age', [$request->minAge, $request->maxAge]);
        }
        if ($request->minAmount && $request->maxAmount) {
            $list = $list->whereBetween('withdraw_amount', [$request->minAmount, $request->maxAmount]);
        }
        if ($request->credit) {
            $list = $list->where('credit', $request->credit);
        }
        if($request -> city){
            $list = $list -> where('city' , $request -> city);
        }
        $list = $list->select('id', 'name', 'phone', 'created_at', 'province', 'city', 'status', 'age', 'sex', 'rob_at', 'withdraw_amount')
            ->orderBy('id', 'DESC')
            ->paginate(5);
        if ($list) {
            //dd($list -> toArray());
            $list = $list->toArray();
            foreach ($list['data'] as $k => $v) {
                //dd($v);
                if (!$v['name']) {
                    $list['data'][$k]['name'] = 'XX';
                }
                $time = time2string(ceil(time() - strtotime($v['created_at'])));;
                $list['data'][$k]['time'] = $time;
                $isRob = 0;
                if ($v['rob_at']) {
                    $time2 = time() - strtotime($v['rob_at']);
                    //dd($time2);
                    if ($time2 < 7*24*60*60) {
                        $isRob = 1;
                    }
                }
                $list['data'][$k]['isRob'] = $isRob;
                $order = GrabBorrowOrder::where(['user_id' => $request->user_id, 'custom_id' => $v['id']])->first();
                if (!$order) {
                    $list['data'][$k]['phone'] = yc_phone($v['phone']);
                }
                $list['data'][$k]['withdraw_amount'] = $list['data'][$k]['withdraw_amount'] ? $list['data'][$k]['withdraw_amount'] : $list['data'][$k]['grab_custom_high']['withdraw_amount'];
            }
        }
        //dd($list);
        return response()->json(['code' => 0, 'msg' => 'success', 'data' => $list]);
    }

    /**
     * 客户详情
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function orderDetail(Request $request)
    {
        if (!$request->custom_id) {
            return response()->json(['code' => 1, 'msg' => '参数错误']);
        }
        $res = GrabBorrowOrder::where(['user_id' => $request->user_id, 'custom_id' => $request->custom_id])->first();
        if (!$res) {
            //return response()->json(['code' => 1, 'msg' => '权限不足']);
        }
        $info = GrabCustom::with(['grabCustomHigh' => function ($q) {
            $q->select();
        }])
            ->where('id', $request->custom_id)
            ->first();
        if (!$info) {
            return response()->json(['code' => 2, 'msg' => '客户不存在']);
        }
        if(!$res){
            $info['phone'] = yc_phone($info -> phone);
        }
        //$info -> grabCustomHigh;
        //dd($info -> toArray());
        $info['social'] = $info -> social ? '有' : '无';
        $info['grabCustomHigh']['social'] = $info -> grabCustomHigh -> social ? '有' : '无';
        $info['grabCustomHigh']['housing_fund'] = $info -> grabCustomHigh -> housing_fund ? '有' : '无';
        $wages = config('config.wages');
        $info['grabCustomHigh']['wages'] = $wages[$info -> grabCustomHigh -> wages];
        $info['grabCustomHigh']['human_life'] = $info -> grabCustomHigh -> human_life ? '有' : '无';
        $info['grabCustomHigh']['webank'] = $info -> grabCustomHigh -> webank ? '有' : '无';
        return response()->json(['code' => 0, 'msg' => 'success', 'data' => $info]);
    }


    /**
     * 抢单
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function robOrder(Request $request)
    {
        $custom = GrabCustom::where('id', $request->custom_id)->first();
        if ($custom->status) {
            $data = date('Y-m-d H:i:s', strtotime('-7 days'));
            if ($data < $custom->rob_at) {
                return response()->json(['code' => 1, 'msg' => '客户已被抢']);
            }
        }
        $order = GrabBorrowOrder::where(['user_id' => $request->user_id, 'custom_id' => $request->custm_id])->first();
        if ($order) {
            return response()->json(['code' => 2, 'msg' => '此人是您的客户']);
        }
        $user = GrabUsers::where('user_id', $request->user_id)->first();
        /*if ($user->city != $custom->city) {
            return response()->json(['code' => 3, 'msg' => '超出管辖范围']);
        }*/

        $user = $request->user_id;
        //dd($user);
        try {
            //开启事务
            DB::beginTransaction();
            $price = $custom->is_high ? 2 : 1;
            $is_assign = 0;
            if ($request->is_assign) {
                $is_assign = 1;
            }
            $orderNo = getOrderNo();
            //dd([$price , $orderNo]);
            //生成订单
            $res2 = GrabBorrowOrder::insert(
                [
                    'user_id' => $user,
                    'custom_id' => $custom->id,
                    'order_no' => $orderNo,
                    'withdraw_amount' => $custom->withdraw_amount,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'status' => 0,
                    'price' => $price,
                    'unit' => 'card_ticket',
                    'is_high' => $custom->is_high,
                    'is_assign' => $is_assign
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
            $res5 = GrabCustom::where('id', $custom->id)->update(
                [
                    'status' => 1,
                    'rob_at' => date('Y-m-d H:i:s')
                ]
            );

            //发送短信/消息
            /*\SendMsg::sendmail($custom -> phone , '您的申请已经被处理,请注意接听来电'); // 发送给客户
            \SendMsg::sendmail($user -> phone , '您已抢到客户，请前往APP查看');*/
            \SendMsg::sendmail($v->phone, '【帮带客】您的申请已通过，请注意接听客服电话。'); // 发送给客户
            \SendMsg::sendmail($grabUser[0]['phone'], '【帮带客】您已成功抢单，请登陆APP查看。'); //发送给信贷经理
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
            return response()->json(['code' => 0, 'msg' => '抢单成功']);
            //echo 'success';die;
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

    }


    /**
     * 我的订单
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function userOrder(Request $request)
    {
        $type = $request->is_high ? $request->is_high : 0;
        $list = GrabBorrowOrder::with(['grabCustom' => function ($q) {
            $q->select();
        }])
            ->with(['GrabCustomHigh' => function ($q2) {
                $q2->select();
            }])
            ->where('is_high', $type)
            ->where('user_id', $request->user_id)
            ->get();
        //dd($list -> toArray());
        return response()->json(['code' => 0, 'msg' => 'success', 'data' => $list]);
    }

    /**
     * 退单理由
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function exitOrderAccount(Request $request)
    {
        $list = GrabOrderAccount::select('id', 'account')->get();
        return response()->json(['code' => 0, 'msg' => 'success', 'data' => $list]);
    }

    /**
     * 退单
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function exitOrder(Request $request)
    {
        if (!$request->account || !$request->order_id) {
            return response()->json(['code' => 1, 'msg' => '参数错误']);
        }
        $info = GrabBorrowOrder::where(['id' => $request->order_id, 'user_id' => $request->user_id, 'status' => 0])->first();
        if (!$info) {
            return response()->json(['code' => 2, 'msg' => '订单不存在']);
        }
        GrabBorrowOrder::where(['id' => $request->order_id, 'user_id' => $request->user_id, 'status' => 0])->update(['status' => 1, 'account' => $request->account]);
        return response()->json(['code' => 0, 'msg' => 'success']);
    }


    //验证短信验证码
    public static function msCodeVerify($mscode, $phone)
    {
        $code = Redis::get('qdmscode_' . $phone);
        if ($code != strtoupper($mscode)) {
            return false;
        }
        Redis::set('qdmscode_' . $phone, null);
        return true;
    }

}
