<?php

namespace App\Http\Controllers;

use App\GrabCardTicket;
use App\GrabPoints;
use App\GrabUserCardticketDetail;
use App\GrabUsersPre;
use App\GrabUsersWallet;
use Illuminate\Http\Request;
use App\GrabJdbindcard;
use App\GrabJdDeal;
use App\GrabUserPointsDetail;
use Illuminate\Support\Facades\DB;
class JdController extends Controller
{
    //京东相关
    /**
     * 发送绑卡验证码
     * @param Request $request
     * @return array
     */
    public function bindCardSend(Request $request)
    {
        $user = GrabUsersPre::where('id' , $request -> user_id) -> value('phone');
        if(!$user){
            return ['code' => 400 , 'msg' => '用户不存在'];
        }
        $info = GrabJdbindcard::where(['bank_card' => $request['bank_card'] , 'bind_status' => 2]) -> first();
        if($info){
            return ['code' => 400 , 'msg' => '该银行卡已绑定,请更换'];
        }
        $mains = new \jdces2();
        $result = $mains -> agreementSign($request['id_number'] , $request['name'] , $request['phone'] , $request['bank_card'] , $request['bankAbribge']);
        //echo $result;
        \Log::LogWirte('发送绑卡验证码返回:' . $result, 'bindBankSend');
        $userInfo = GrabJdbindcard::where(['user_id' => $request -> user_id]) -> where('bind_status' , 1) -> orwhere('bind_status' , 2) -> first();
        $userInfo ? $master = 0 : $master = 1;
        $result = json_decode($result , true);
        if($result['code'] == '0000'){
            \Log::LogWirte('发送绑卡验证码成功:', 'bindBankSend');
            $data = [
                'user_id'       => $request -> user_id,
                'out_trade_no'  => $result['outTradeNo'],
                'bank_card'     => $request -> bank_card,
                'bankid'        => $request -> bankAbribge,
                'bind_status'   => 0, //待确认
                'bindid'        => '',
                'agreement_no'  => $result['agreementNo'],
                'created_at'    => date('Y-m-d H:i:s'),
                'phone'         => $request -> phone,
                'status'        => 0,
                'master'        => $master,
                'bank_name'     => $request -> bankName
            ];
            GrabJdbindcard::insert($data);
            return ['code' => 200 , 'msg' => '短信发送成功'];
        }else{
            \Log::LogWirte('发送绑卡验证码失败:' . $result['desc'], 'bindBankSend');
            return ['code' => 400 , 'msg' => $result['desc']];
        }
    }

    /**
     * 确定绑定银行卡
     * @param Request $request
     */
    public function bindBank($request)
    {
        \Log::LogWirte('绑卡验证码:' . json_encode($request), 'bindBank');
        $mains = new \jdces2();
        $res = $mains -> agreementSignConfirm($request -> out_trade_no , $request -> agreement_no , $request -> code);
        //echo $res;die;
        \Log::LogWirte('绑卡验证码:' . $res, 'bindBank');
        $res = json_decode($res , true);
        \Log::LogWirte('绑卡结果:' . $res['code'], 'bindBank');
        if($res['code'] == 0000){
            GRABJdBindcard::where(['id' => $request -> id]) -> update(['bind_status' => 1]);
            //\Log::LogWirte('绑定成功:' . $res, 'bindBank');
            return ['code' => 200 , 'msg' => '绑定成功'];
        }else{
            return ['code' => 400 , 'msg' => $res['desc']];
        }
    }

    /**
     * 绑定银行卡回调
     * @param Request $request
     */
    public function bindCallback(Request $request)
    {
        \Log::LogWirte('request:' . json_encode($request -> input()), 'bindCallBack');
        $mains = new \jdces2();
        //$res = "{\"sign\":\"950A6B3E3F7485D5352BC6E485FCDA53D55EC63B3D8BF3DFBE592C352AC51631\",\"formatType\":\"JSON\",\"encType\":\"AP7\",\"signType\":\"SHA-256\",\"data\":\"eyJhZ3JlZW1lbnRTdGF0dXMiOiJGSU5JIiwiZGVzYyI6IuaIkOWKnyIsInBheVRvb2wiOiJDT0xMIiwiZmluaXNoRGF0ZSI6IjIwMTkwNjEwMTkyODE3Iiwib3V0VHJhZGVObyI6IjVjZmUzZTdmMmIxNWUiLCJjb2RlIjoiMDAwMCIsIm5vdGlmeVVybCI6Imh0dHBzOi8vY3YuY2h1d2luZS5jb20vYmluZENhbGxCYWNrIiwiYWdyZWVtZW50Tm8iOiIyMDE5MDYxMDIwOTc2NTYyMDUzIn0=\",\"merType\":\"CCC\",\"merId\":\"360080004002741166\",\"charset\":\"UTF-8\",\"\/bindCallBack\":null}";
        $result = $mains -> asyVerifySign(json_encode($request -> input()));
        //dump($request);
        \Log::LogWirte('验签后解密数据:' . $result, 'bindCallBack');
        $result = json_decode($result , true);
        if($result && $result['code'] == '0000'){
            GrabJdBindcard::where(['agreement_no' => $result['agreementNo']]) -> update(['bind_status' => 2]);
        }
        //echo $result;
        echo 'success';
    }

    /**
     * JD银行卡支付
     * @param Request $request
     * @return array
     */
    public function repayment( $request)
    {
        \Log::LogWirte('request:' . json_encode($request), 'repayment');
        $info = GrabJdBindcard::where(['id' => $request['cardId'] , 'user_id' => $request['user_id']]) -> first();
        if(!$info){
            return ['code' => 500 , 'msg' => '银行卡不存在'];
        }
        $data = [
            'order_no'      => $request['order_no'],
            'user_id'       => $request['user_id'],
            'amount'        => $request['amount'],
            'status'        => 0,
            'created_at'    => date('Y-m-d H:i:s'),
            'described'     => $request['described'],
            'type'          => $request['type'],
            'product_id'    => $request['product_id']
        ];

        $mains = new \jdces2();
        if(isset($request['callback'])){
            $res = $mains -> agreementPay($info -> agreement_no , $info -> out_trade_no , $request['amount'] , $data['order_no'], 'jdCallBack2');
        }else{
            $res = $mains -> agreementPay($info -> agreement_no , $info -> out_trade_no , $request['amount'] , $data['order_no']);
        }
        \Log::LogWirte('支付返回值：' . json_encode($res), 'jdDeal');
        //dump($res);die;
        if($res['code'] == '0000'){
            GrabJdDeal::insert($data);
            \Log::LogWirte('申请成功', 'jdDeal');
            return ['code' => 200 , 'msg' => '申请成功'];
        }else{
            \Log::LogWirte('request:' . json_encode($res), 'jdDeal');
            return ['code' => 400 , 'msg' => $res['desc']];
        }
    }

    /**
     * 银行卡充值异步回调
     * @param Request $request
     */
    public function payCallBack(Request $request)
    {
        \Log::LogWirte('request:' . json_encode($request -> input()), 'payCallBack');
        //异步验签
        /*$json = "{
            \"sign\": \"5BD1261BCBB4704F7E25E38F61AE457A95C386A9C2E9BE525BA5B00AD6F2E7D5\",
            \"formatType\": \"JSON\",
            \"encType\": \"AP7\",
            \"signType\": \"SHA-256\",
            \"data\": \"eyJ0cmFkZVN0YXR1cyI6IkZJTkkiLCJkZXNjIjoi5oiQ5YqfIiwidHJhZGVObyI6IjIwMTkwNzMxMTYzNTA0MjAxMzYzMDcwMzI2MTg2MSIsImN1c3RvbWVyTm8iOiIzNjAwODAwMDQwMDI3NDExNjYiLCJmaW5pc2hEYXRlIjoiMjAxOTA3MzExNjM1MDQiLCJvdXRUcmFkZU5vIjoib3JkZXJfMjAxOTA3MzExNjM1MDREcDV6UEdtTyIsImNvZGUiOiIwMDAwIiwiY2FyZFR5cGUiOiJERSIsImJhbmtDb2RlIjoiQkNNIiwiY3VycmVuY3kiOiJDTlkiLCJwYXlUb29sIjoiRVhQUiIsImV4dGVuZFBhcmFtcyI6IntcInNlbmRTTVNcIjpcImZhbHNlXCJ9IiwidHJhZGVBbW91bnQiOiIxIiwibWVyY2hhbnRObyI6IjExMDM1ODg1MDAwMyIsIm1hc2tDYXJkTm8iOiIzNjU5IiwidHJhZGVTdWJqZWN0Ijoi6K6i5Y2V5pGY6KaBIn0=\",
            \"merType\": \"CCC\",
            \"merId\": \"360080004002741166\",
            \"charset\": \"UTF-8\",
            \"/payCallBack\": null
        }";*/
        $mains = new \jdces2();
        $result = $mains -> asyVerifySign(json_encode($request -> input()));
        //$result = $mains -> asyVerifySign($json);

        $result = json_decode($result , true);
        \Log::LogWirte('解密返回值：' . json_encode($result), 'payCallBack');
        //dump($result);die;
        if($result['code'] == '0000'){
            DB::beginTransaction();
            try{
                $where = ['order_no' => $result['outTradeNo']];
                //dump($where);die;
                GrabJdDeal::where($where) -> update(['status' => 1]);
                $order = GrabJdDeal::where($where) -> first();
                //dump($order -> toArray());die;
                if($order -> type == 1){
                    //卡券支付

                    //更新用户钱包数据
                    $value = GrabCardTicket::where('id' , $order -> product_id) -> value('face_value');
                    GrabUsersWallet::where('user_id' , $order -> user_id) -> increment('card_ticket' , $value);
                    $total = GrabUsersWallet::where('user_id' , $order -> user_id) -> value('card_ticket');
                    GrabUserCardticketDetail::insert(
                        [
                            'user_id'       => $order -> user_id,
                            'type'          => 1,
                            'num'           => '+' . $value,
                            'total_num'     => $total,
                            'described'     => '卡券充值',
                            'order_no'      => $result['outTradeNo'],
                            'pay_type'      => 1,
                            'created_at'    => date('Y-m-d H:i:s')
                        ]
                    );
                    DB::commit();
                    echo 'success';die;
                }elseif ($order -> type == 2){
                    //积分支付
                    //更新用户钱包数据
                    $value = GrabPoints::where('id' , $order -> product_id) -> value('face_value');
                    GrabUsersWallet::where('user_id' , $order -> user_id) -> increment('points' , $value);
                    $total = GrabUsersWallet::where('user_id' , $order -> user_id) -> value('points');
                    GrabUserPointsDetail::insert(
                        [
                            'user_id'       => $order -> user_id,
                            'type'          => 1,
                            'num'           => '+' . $value,
                            'total_num'     => $total,
                            'descrition'     => '积分充值',
                            'order_no'      => $result['outTradeNo'],
                            'pay_type'      => 1,
                            'created_at'    => date('Y-m-d H:i:s')
                        ]
                    );
                    DB::commit();
                    echo 'success';die;
                }
            }catch (\Exception $e){
                DB::rollBack();
                echo $e -> getMessage();
                echo '<br>';
            }

        }
        echo 'success';
    }
}
