<?php

/*
代收业务
 */

class CreditPayment
{

    /**
     * 1.1 签约请求接口
     * DE 借记卡  CR 信用卡
     */
    static function agreementSign($idNo, $idName, $phone, $cardNo, $bankCode, $cardType = 'DE')
    {

        /** 请求接口的业务数据信息 **/
        $bizData = [];
        /** 固定值  **/
        $bizData["outTradeNo"] = uniqid();;
        /** 固定值  **/
        $bizData["templateNo"] = '360080004002741166';
        /** 二级商户号  **/
        $bizData["inMerchantNo"] = '110358850003';
        /** 会员号 **/
        $bizData["inCustomerNo"] = '360080004002741166';
        /** 签约支付工具 固定值：COLL  **/
        $bizData["payTool"] = "COLL";
        /** 签约终态异步通知地址 商户根据实际情况填写 **/
        $bizData["notifyUrl"] = "https://cv.chuwine.com/bindCallBack";
        /** 签约协议描述 商户根据实际情况填写 **/
        $bizData["tradeSubject"] = "协议描述";
        /** 商户测试请使用自己银行卡信息否则银行及客户投诉自行承担 **/
        /** 证件类型 目前请填写固定值 ID 身份证**/
        $bizData["idType"] = "ID";
        /** 证件号 身份证号 **/
        $bizData["idNo"] = $idNo;
        /** 持卡人姓名  **/
        $bizData["idName"] = $idName;
        /** 银行预留手机号  **/
        $bizData["phone"] = $phone;
        /** 银行侧卡号  **/
        $bizData["cardNo"] = $cardNo;
        /** 银行简码 **/
        $bizData["bankCode"] = $bankCode;
        /** 银行卡账户类型 P：对私业务 C：对公业务 **/
        $bizData["bankAccountType"] = "P";
        /** 银行卡类型  DE：借记卡  CR：贷记卡 **/
        $bizData["cardType"] = "DE";
        $returnContent = RequestUtils::tradeRequestSSL($bizData, Constants::DOMAIN . Constants::CREDIT_PAY_REQUEST_URL);
        $res = CreditPayment::verifySignAndTradeStatus($returnContent);
        return $res;
    }

    /**
     * 1.2 签合确认接口
     */
    static function agreementSignConfirm($outTradeNo, $agreementNo, $code)
    {
        /** 请求接口的业务数据信息 **/
        $bizData = [];
        $bizData["outTradeNo"] = $outTradeNo;//签约请求和签约确认的商户订单号传相同值  //5cf6229762d04
        $bizData["agreementNo"] = $agreementNo;
        $bizData["inCustomerNo"] = '360080004002741166';
        $bizData["verifyCode"] = $code;
        $returnContent = RequestUtils::tradeRequestSSL($bizData, Constants::DOMAIN . Constants::CREDIT_PAY_CONFIRM_URL);
        $res = CreditPayment::verifySignAndTradeStatus($returnContent);
        return $res;
    }

    /**
     * 1.3 交易接口
     */
    static function agreementPay($agreementNo, $outTradeNo, $tradeAmount, $order, $callback)
    {
        /** 请求接口的业务数据信息 **/
//		$bizData = [];
//		$bizData["agreementNo"] = $agreementNo;//签约请求接口返回的协议号
//		$bizData["payTool"] ="COLL";
//		$bizData["outTradeNo"] = $outTradeNo;//签约请求和签约确认的商户订单号传相同值
//		$bizData["inMerchantNo"] = '110358850003';
//		$bizData["inCustomerNo"] = '360080004002741166';
//		/** 订单金额，单位：分 **/
//		$bizData["tradeAmount"] = '2';
//		/** 货币类型，固定填CNY**/
//		$bizData["currency"] ="CNY";
//		$bizData["tradeSubject"] = $order;
//		/** 签约终态异步通知地址 商户根据实际情况填写 **/
//		$bizData["notifyUrl"] = "https://www.test.com/";
//		/** 交易过期关单时间。单位：分钟 **/
//		$bizData["tradeExpiryTime"] = "120";
//		//dump($bizData);die;
//		$returnContent = RequestUtils::tradeRequestSSL($bizData, Constants::DOMAIN.Constants::CREDIT_PAY_URL);
//		//dump($returnContent);die;
//		$res = CreditPayment::queryVerifySignAndTradeStatus($returnContent);
//		return $res;

        $bizData = [];
        $bizData["agreementNo"] = $agreementNo;//签约请求接口返回的协议号
        $bizData["payTool"] = "COLL";
        $bizData["outTradeNo"] = $order;//签约请求和签约确认的商户订单号传相同值
        $bizData["inMerchantNo"] = Constants::MERCHANT_NO;
        $bizData["inCustomerNo"] = Constants::CUSTOMER_NO;
        /** 订单金额，单位：分 **/
        $bizData["tradeAmount"] = strval($tradeAmount * 100);
        /** 货币类型，固定填CNY**/
        $bizData["currency"] = "CNY";
        $bizData["tradeSubject"] = "订单摘要";
        /** 签约终态异步通知地址 商户根据实际情况填写 **/
        $bizData["notifyUrl"] = "https://cv.chuwine.com/" . $callback;
        /** 交易过期关单时间。单位：分钟 **/
        $bizData["tradeExpiryTime"] = "120";
        //dump($bizData);die;
        $returnContent = RequestUtils::tradeRequestSSL($bizData, Constants::DOMAIN . Constants::CREDIT_PAY_URL);
        return CreditPayment::queryVerifySignAndTradeStatus($returnContent);
    }

    /**
     * 2 交易查询
     */
    static function queryTrade()
    {
        /** 请求接口的业务数据信息 **/
        $bizData = [];
        $bizData["outTradeNo"] = "s008201811014401350";
        $bizData["merchantNo"] = Constants::MERCHANT_NO;
        $returnContent = RequestUtils::tradeRequestSSL($bizData, Constants::DOMAIN . Constants::TRADE_QUERY_URL);
        CreditPayment::queryVerifySignAndTradeStatus($returnContent);

    }

    /**
     * 交易： 返回参数验证签名 & 交易状态判断
     */
    static function verifySignAndTradeStatus($returnContent)
    {
        $returnData = json_decode($returnContent, true);
        if (is_null($returnData)) {
            //echo "返回非json格式,请检查签名密钥、证书等加解密相关信息是否正确。".$returnContent."\n";
            return false;
        }
        $returnBizData = SignUtils::verifySign($returnData);
        if ($returnBizData == null) {
            //echo "验证签名不通过\n";
        } else {
            //echo "签名验证通过\n";
            $res = TradeStatusUtils::creditagreementStatus($returnBizData);
            return $res;
        }
    }

    /**
     * 查询： 返回参数验证签名 & 交易状态判断
     */
    static function queryVerifySignAndTradeStatus($returnContent)
    {
        //echo $returnContent;die;
        $returnData = json_decode($returnContent, true);
        if (is_null($returnData)) {
            //echo "返回非json格式,请检查签名密钥、证书等加解密相关信息是否正确。".$returnContent."\n";
            //echo 11;
            return false;
        }
        //dump($returnData);die;
        $returnBizData = SignUtils::verifySign($returnData);
        //dump($returnBizData);die;

        if ($returnBizData == null) {
            //echo "验证签名不通过\n";
        } else {
            //echo "签名验证通过\n";
            return TradeStatusUtils::tradeQueryTradeStatus($returnBizData);
        }
    }
}
