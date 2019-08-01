<?php

use wepay\join\demo\common\HttpUtils;
use wepay\join\demo\common\SignUtils;
use wepay\join\demo\common\Contants;

include 'HttpUtils.php';
include 'SignUtils.php';
include 'Contants.php';

class jdces
{

    public function execute($bank_code, $bank_card, $name, $amount, $bank_name, $phone)
    {
        return $this->defrayPay($bank_code, $bank_card, $name, $amount, $bank_name, $phone);
        //$this->verifySingNotify();
        //$this->tradeQuery();
        //$this->accountQuery();
    }

    /**
     * 代付交易demo
     */
    function defrayPay($bank_code, $bank_card, $name, $amount, $bank_name, $phone, $order)
    {
        //dump([$bank_code , $bank_card , $name , $amount , $bank_name , $phone]);die;
        /*$paramDic = [];
        $paramDic["payee_bank_code"]= $bank_code;
        $paramDic["customer_no"]="360080004002740937";
        $paramDic["extend_params"]="{\"ssss\":\"ssss\"}";
        $paramDic["payee_account_type"]="P";
        $paramDic["return_params"]="1234ssddffgghhj";
        $paramDic["trade_currency"]="CNY";
        $paramDic["pay_tool"]="TRAN";
        $paramDic["category_code"]="20jd222";
        $paramDic["payee_account_no"]= $bank_card;
        $paramDic["payee_account_name"]= $name;
        $paramDic["trade_source"]="testetst";
        $paramDic["notify_url"]="http://xxx/";//商户处理数据的异步通知地址
        $paramDic["biz_trade_no"]='orders_' . date('YmdHis');
        $paramDic["out_trade_no"]='order_' . date('YmdHis');//外部交易号
        $paramDic["seller_info"]="{\"customer_code\":\"360080004002740937\",\"customer_type\":\"CUSTOMER_NO\"}";
        $paramDic["out_trade_date"]=date('YYYYmmddTHHiiss');
        $paramDic["trade_amount"]="1";//交易金额，单位分

        $paramDic["payee_bank_fullname"]= $bank_name;
        $paramDic["sign_type"]=Contants::signtype;
        $paramDic["request_datetime"]=date('YYYYmmddTHHiiss');
        $paramDic["trade_subject"]="test代付";
        $paramDic["payee_card_type"]="DE";
        $paramDic["payee_mobile"]=$phone;//银行预留电话*/

        $paramDic = [];
        $paramDic["payee_bank_code"] = $bank_code;
        $paramDic["customer_no"] = "360080004002740937";
        $paramDic["extend_params"] = "{\"ssss\":\"ssss\"}";
        $paramDic["payee_account_type"] = "P";
        $paramDic["return_params"] = "1234ssddffgghhj";
        $paramDic["trade_currency"] = "CNY";
        $paramDic["pay_tool"] = "TRAN";
        $paramDic["category_code"] = "20jd222";
        $paramDic["payee_account_no"] = $bank_card;
        $paramDic["payee_account_name"] = $name;
        $paramDic["trade_source"] = "testetst";
        $paramDic["notify_url"] = "https://cv.chuwine.com/loanCallBack";//商户处理数据的异步通知地址
        $paramDic["biz_trade_no"] = 'orders_' . date('YmdHis');
        $paramDic["out_trade_no"] = $order;//外部交易号
        $paramDic["seller_info"] = "{\"customer_code\":\"360080004002740937\",\"customer_type\":\"CUSTOMER_NO\"}";
        $paramDic["out_trade_date"] = date('YYYYmmddTHHiiss');
        $paramDic["trade_amount"] = strval($amount * 100);//交易金额，单位分

        $paramDic["payee_bank_fullname"] = $bank_name;
        $paramDic["sign_type"] = Contants::signtype;
        $paramDic["request_datetime"] = date('YYYYmmddTHHiiss');
        $paramDic["trade_subject"] = "test代付";
        $paramDic["payee_card_type"] = "DE";
        $paramDic["payee_mobile"] = $phone;//银行预留电话


        //dump($paramDic);die;
        $returnData = $this->tradeRequest($paramDic, Contants::httpurl . "/npp10/defray_pay", Contants::encrypttype);
        if ($returnData == null) {
            echo "验证签名不成功\n";
        } else {
            return $returnData;
            //return $this->rescode($returnData,false);
        }
    }

    /**
     * 交易查询demo
     */
    function tradeQuery()
    {

        $paramDic["customer_no"] = "360080004002740937";//提交者会员号
        $paramDic["request_datetime"] = "20190605T183129";//请求时间
        $paramDic["out_trade_no"] = "23456587692";//商户订单号
        //$paramDic["trade_no"]="";  //代付交易号
        $paramDic["trade_type"] = "T_AGD";//T_AGD是查询代付类型，非必填
        $returnData = $this->tradeRequest($paramDic, Contants::httpurl . "/npp10/trade_query", null);
        if ($returnData == null) {
            echo "验证签名不成功\n";
        } else {
            $this->rescode($returnData, false);
        }
    }

    /**
     * 余额查询
     */
    function accountQuery()
    {
        $paramDic["customer_no"] = "360080004002740937";//提交者会员号（商户会员号）
        $paramDic["request_datetime"] = date('YYYYmmddTHHiiss');
        $paramDic["out_trade_no"] = "1234567890";
        $paramDic["out_trade_date"] = "20151214T141800";
        $paramDic["buyer_info"] = "{\"customer_code\":\"360080004002740937\",\"customer_type\":\"CUSTOMER_NO\"}";//customer_code必须和上面的会员号一致
        $paramDic["query_type"] = "BUSINESS_BASIC";
        $paramDic["ledger_type"] = "00";
        $returnData = $this->tradeRequest($paramDic, Contants::httpurl . "/npp10/account_query", null);
        if ($returnData == null) {
            //echo "验证签名不成功\n";
        } else {
            $response_code = $returnData["response_code"];
            if (Contants::SUCCESS == $response_code) {
                $account_amount = $returnData["account_amount"] == null ? 0 : $returnData["account_amount"];
                $frozen_amount = $returnData["frozen_amount"] == null ? 0 : $returnData["frozen_amount"];
                echo " 可用余额=" . ($account_amount - $frozen_amount) . "（分）\n";
            } else {
                echo "查询失败 描述：" . response_code . " " . $returnData["response_message"];
            }
        }
    }

    /**
     * http url通知获得到数据后处理demo
     * (用户需自行开发一个http url的通知地址，代付交易终态后会网银根据代付交易参数里notify_url的地址进行http请求将结果通知给用户，方法内$data字符串是通知的数据字符串)
     */
    function verifySingNotify($data)
    {
        //$data="sign_type=SHA&sign_data=24CFBF93C3E85E773CA2A87BB78D5314E7EE0A27&trade_no=20150605100042000000001046&merchant_no=22318136&notify_datetime=20155705T112406369&biz_trade_no=1433476626534&customer_no=360080002212160011&out_trade_no=1433476626534&trade_class=DEFY&trade_status=CLOS&is_success=Y&card_type=DE&buyer_info={\"customerNo\":\"360080002191800017"}&trade_subject=tixian&seller_info={\"customerNo\":\"360080002191800017\"}&trade_amount=1&category_code=20JR0131&trade_currency=CNY";
        //$data = [];
        $arrayData = explode("&", $data);
        $objData = [];
        for ($i = 0; $i < count($arrayData); $i++) {
            $subArray = explode("=", $arrayData[$i]);
            if (count($subArray) != 2) {
                continue;
            } else {
                $objData[$subArray[0]] = $subArray[1];
            }
        }
        $returnData = SignUtils::verifySing($objData);
        if ($returnData == null) {
            echo "验证签名不成功\n";
        } else {
            $this->tradeCode($returnData);
        }
    }

    /**
     * 发送http请求
     * @param unknown $data 请求数据
     * @param unknown $url url
     * @param unknown $encryptType_RSA 加密类型，不加密传null
     */
    function tradeRequest($data, $url, $encryptType_RSA)
    {
        $return_data = [];
        $data = SignUtils:: enctyptData($data, $encryptType_RSA);
        $data_string = SignUtils:: paddingDataString($data);
        list ($return_code, $return_content) = HttpUtils:: http_post_data($url, $data_string);
        $return_content = str_replace("\n", '', $return_content);
        $return_content = str_replace("\r", '', $return_content);
        //echo "返回数据".$return_content;
        if ($return_content == null || "" == $return_content) {
            $return_data["response_code"] = RETURN_PARAM_NULL;
            $return_data["response_message"] = "返回数据为空";
            return $return_data;
        }
        $return_data = json_decode($return_content, true);
        //print_r($return_data);die;
        if (is_null($return_data)) {
            echo "返回非json格式" . $return_content . "\n";
            $return_data["response_code"] = $return_content;
            return $return_data;
        }
        $return_data1 = SignUtils::verifySing($return_data);
        return $return_data1;
    }

    /**
     * 判断返回码
     * @param unknown $objData 返回数据
     * @param unknown $isQuery 是否查询返回数据
     */
    function rescode($objData, $isQuery)
    {
        $response_code = $objData["response_code"];
        //dump($objData);die;
        if (Contants::SUCCESS == $response_code) {
            //dump($objData);

            return $this->tradeCode($objData);
        } else if ($isQuery) {
            echo "查询异常，建议不做数据处理\n";//isQuery如果未true，非0000的返回编码表示查询异常，不能按失败处理
        } else if (!Contants::isContainCode($response_code)) {//返回编码不包含在配置中的
            $trade_status = $objData["trade_status"];
            if (!$trade_status || "" == $trade_status) {
                echo "返回编码不包含在配置中的,未知\n";
            } else {//返回编码不包含但$trade_status有状态，按状态处理
                $this->tradeCode($objData);
            }

            //TODO 返回编码不包含在配置中的,未知处理
        } else if (Contants::SYSTEM_ERROR == $response_code || Contants::RETURN_PARAM_NULL == $response_code) {
            echo "未知\n";//需查询交易获取结果或等待通知结果
            //TODO 未知业务逻辑或查询交易结果处理
        } else if (Contants::OUT_TRADE_NO_EXIST == $response_code) {
            echo "外部交易号已经存在\n";
            //TODO 需查询交易获取结果或等待通知结果
        } else {
            echo "失败\n";
            //TODO 失败处理逻辑
        }
    }

    /**
     * 判断业务状态
     * @param unknown $objData 返回数据
     */
    function tradeCode($objData)
    {
        $trade_status = $objData["trade_status"];
        return $objData;
        dump($objData);
        die;
        if (Contants::TRADE_FINI == $trade_status) {
            echo "交易成功\n";
            //TODO 成功后业务逻辑
        } else if (Contants::TRADE_CLOS == $trade_status) {
            echo "交易关闭，交易失败\n" . $trade_status;
            //TODO 失败后业务逻辑
        } else if (Contants::TRADE_WPAR == $trade_status || Contants::TRADE_BUID == $trade_status || Contants::TRADE_ACSU == $trade_status) {
            echo "等待支付结果，处理中\n";//需查询交易获取结果或等待通知结果
            //TODO 处理中业务逻辑
        }
    }
}