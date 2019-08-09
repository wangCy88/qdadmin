<?php

namespace App\Http\Controllers;

use App\GrabAliPay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\GrabCardTicket;
use App\GrabUsersWallet;
use App\GrabUserCardticketDetail;
use App\GrabUserPointsDetail;
use App\GrabPoints;

class  AliController extends Controller
{
    /**
     * 支付宝支付
     * @param $request
     * @throws \Exception
     */
    public function aliPay($request)
    {
        $name = $request['name'] ? $request['name'] : '帮带客支付';
        $order = $request['order'];
        $amount = $request['amount'];
        $aop = new \AopClient ();
        $aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
        $aop->appId = config('alipay.appid');
        $aop->rsaPrivateKey = config('alipay.privateKey');
        $aop->alipayrsaPublicKey = config('alipay.publicKey');
        $aop->apiVersion = '1.0';
        $aop->postCharset = 'UTF-8';
        $aop->format = 'json';

        $aop->signType = 'RSA2';
        $returnUrl = '';
        $notifyUrl = 'https://control.leyijiu.com/' . $request['notify'];
        //dump($aop);die;
        $request = new \AlipayTradeWapPayRequest ();
        $request->setBizContent("{" .
            "    \"body\":\"帮带客支付\"," .
            "    \"subject\":\" " . $name . " \"," .
            "    \"out_trade_no\":\"" . $order . " \"," .
            "    \"timeout_express\":\"90m\"," .
            "    \"total_amount\": " . $amount . "," .
            "    \"product_code\":\"QUICK_WAP_WAY\"" .
            "  }");
        echo $aop->pageExecute($request, $returnUrl, $notifyUrl);
        //echo $result;
    }


    /**
     * 支付宝回调
     * @param Request $request
     */
    public function aliCallBack(Request $request)
    {
        \Log::LogWirte('request:' . json_encode($request->input()), 'aliCallBack');
        $json = "{
            \"gmt_create\": \"2019-07-31 16:08:18\",
            \"charset\": \"UTF-8\",
            \"seller_email\": \"bd@01dai.com\",
            \"subject\": \"帮卡支付\",
            \"sign\": \"AcPdsb7tDUO2tJVkKWMGywxJ4TJ3isxPVLS/DezjlQjoMcvuoaX+qpjs/lqr1Ud8ddX5JvFUYSQHfNROoJ0FgqE7whqaObQecYgLrDFZf+ke/yx0RevC8yQDBqoKBKtKskbgB7wagN5IVfxGX0yovmLpw5TbIlvRymQbRPl7iyvx8KLseZHeSK6qed3/SmW+Qv/SLJQu5zoB23+/whdEBdyx/xiqi/vkD0xKTXIs9y3eDLg7rbqPWtNRElAJG6+2MHWbuHaPBqcXi2ITOxPMpUXxmhfWeb18fj0PF97ggCkgklLXwnWz6nhkcuPkgUTb4APW9Zry85v/i1WAg6l6tw==\",
            \"body\": \"帮带客支付\",
            \"buyer_id\": \"2088702270821571\",
            \"invoice_amount\": \"0.01\",
            \"notify_id\": \"2019073100222160818021570520572851\",
            \"fund_bill_list\": \"[{'amount':'0.01','fundChannel':'ALIPAYACCOUNT'}]\",
            \"notify_type\": \"trade_status_sync\",
            \"trade_status\": \"TRADE_SUCCESS\",
            \"receipt_amount\": \"0.01\",
            \"buyer_pay_amount\": \"0.01\",
            \"app_id\": \"2018012502069457\",
            \"sign_type\": \"RSA2\",
            \"seller_id\": \"2088921858920767\",
            \"gmt_payment\": \"2019-07-31 16:08:18\",
            \"notify_time\": \"2019-07-31 16:33:30\",
            \"version\": \"1.0\",
            \"out_trade_no\": \"order_20190731160811ifE2MNmt\",
            \"total_amount\": \"0.01\",
            \"trade_no\": \"2019073122001421570570016612\",
            \"auth_app_id\": \"2018012502069457\",
            \"buyer_logon_id\": \"909***@qq.com\",
            \"point_amount\": \"0.00\",
            \"/aliCallBack\": null
        }";
        //$request = json_decode($json , true);
        //dump($request);die;
        $request = $request->input();
        if ($request['trade_status'] == 'TRADE_SUCCESS') {
            //支付成功
            //GrabAliPay::where('order_no' , $request['out_trade_no']) -> update(['status' => 1]);
            $status = GrabAliPay::where('order_no', $request['out_trade_no'])->value('status');
            if ($status == 1) {

            } else {
                DB::beginTransaction();
                try {
                    $where = ['order_no' => $request['out_trade_no']];
                    //dump($where);die;
                    GrabAliPay::where($where)->update(['status' => 1]);
                    $order = GrabAliPay::where($where)->first();
                    if ($order->type == 1) {
                        //卡券支付
                        //更新用户钱包数据
                        $value = GrabCardTicket::where('id', $order->product_id)->value('face_value');
                        GrabUsersWallet::where('user_id', $order->user_id)->increment('card_ticket', $value);
                        $total = GrabUsersWallet::where('user_id', $order->user_id)->value('card_ticket');
                        GrabUserCardticketDetail::insert(
                            [
                                'user_id' => $order->user_id,
                                'type' => 1,
                                'num' => '+' . $value,
                                'total_num' => $total,
                                'described' => '卡券充值',
                                'order_no' => $request['out_trade_no'],
                                'pay_type' => 2,
                                'created_at' => date('Y-m-d H:i:s')
                            ]
                        );
                        DB::commit();
                        echo "success";
                        echo "SUCCESS";
                        die;
                    } elseif ($order->type == 2) {
                        //积分支付
                        //更新用户钱包数据
                        $value = GrabPoints::where('id', $order->product_id)->value('face_value');
                        GrabUsersWallet::where('user_id', $order->user_id)->increment('points', $value);
                        $total = GrabUsersWallet::where('user_id', $order->user_id)->value('points');
                        GrabUserPointsDetail::insert(
                            [
                                'user_id' => $order->user_id,
                                'type' => 1,
                                'num' => '+' . $value,
                                'total_num' => $total,
                                'descrition' => '积分充值',
                                'order_no' => $request['out_trade_no'],
                                'pay_type' => 2,
                                'created_at' => date('Y-m-d H:i:s')
                            ]
                        );
                        DB::commit();
                        echo "success";
                        echo "SUCCESS";
                        die;
                    }
                } catch (\Exception $e) {
                    DB::rollBack();
                    echo $e->getMessage();
                }
            }


        } elseif ($request['trade_status'] == 'TRADE_CLOSED') {
            //支付失败
            GrabAliPay::where('order_no', $request['out_trade_no'])->update(['status' => 2]);
        } else {

        }
        echo "success";
        echo "SUCCESS";
    }
}
