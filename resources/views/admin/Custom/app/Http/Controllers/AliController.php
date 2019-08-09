<?php

namespace App\Http\Controllers;

use App\GrabAliPay;
use Illuminate\Http\Request;

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
        $notifyUrl = 'https://cv.chuwine.com/' . $request['notify'];
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
        \Log::LogWirte('request:' . json_encode($request->input()), 'authNotifyUrl');
        $json = "{
            \"gmt_create\": \"2019-07-05 14:46:17\",
            \"charset\": \"UTF-8\",
            \"seller_email\": \"xichuliujizhou@126.com\",
            \"subject\": \"支付\",
            \"sign\": \"QH1zA/Tld1Il5VciAuHFzKERvER5rPHwYvSspF2F8dKIHDSkDCj5OOEtNQEg2oDdU67bUYdt2W9ZECvOgomt0GROaQAiXMmAYJYP0gek0pmd7JahhYOMzd/SNX+Gc3SDlKl5B0QANLZ4eBt4spcrj2emb9kUcin1zu0SxpN2Vitj5/NzHzupXbsFpZpGfNzMyVsVaeBC6TWIGn2SFyLEW+qpiiSLTIhPa3egvW/HEd6vg9bttyYraUNDkGvOMSTT52I2JT0Su0KKaXR93uVjKV8BxnvZGcTY2t4V7LfZ5a52YRAntqP/33LJez3T7bgPIIac4nyjIbYnobEKo0hFmg==\",
            \"body\": \"支付\",
            \"buyer_id\": \"2088702270821571\",
            \"invoice_amount\": \"0.01\",
            \"notify_id\": \"2019070500222144618021571032348029\",
            \"fund_bill_list\": \"[{'amount':'0.01','fundChannel':'ALIPAYACCOUNT'}]\",
            \"notify_type\": \"trade_status_sync\",
            \"trade_status\": \"TRADE_SUCCESS\",
            \"receipt_amount\": \"0.01\",
            \"buyer_pay_amount\": \"0.01\",
            \"app_id\": \"2019030863459753\",
            \"sign_type\": \"RSA2\",
            \"seller_id\": \"2088431709171508\",
            \"gmt_payment\": \"2019-07-05 14:46:18\",
            \"notify_time\": \"2019-07-05 14:46:18\",
            \"version\": \"1.0\",
            \"out_trade_no\": \"TUVNRX1H_20190705144543\",
            \"total_amount\": \"0.01\",
            \"trade_no\": \"2019070522001421571039940928\",
            \"auth_app_id\": \"2019030863459753\",
            \"buyer_logon_id\": \"909***@qq.com\",
            \"point_amount\": \"0.00\",
            \"/getNotify\": null
        }";
        //$request = json_decode($json , true);
        $request = $request->input();
        if ($request['trade_status'] == 'TRADE_SUCCESS') {
            //支付成功
            GrabAliPay::where('order_no', $request['out_trade_no'])->update(['status' => 1]);
        } elseif ($request['trade_status'] == 'TRADE_CLOSED') {
            //支付失败
            GrabAliPay::where('order_no', $request['out_trade_no'])->update(['status' => 2]);
        } else {

        }
        echo "success";
    }
}
