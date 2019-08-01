<?php

class TradeStatusUtils
{

    /**
     * 交易请求及确认 错误码及交易状态处理说明
     * @param $dataBase64 base64字符串
     */
    static function creditPaymentTradeStatus($dataBase64)
    {
        $bizData = base64_decode($dataBase64);
        $objData = json_decode($bizData, true);
        $code = $objData["code"];
        echo "返回报文:" . $bizData . "\n";
        if ("00050" == $code) {
            echo "交易重复请求，请等待异步通知或者调用查询接口获取交易状态\n";
        } else {
            if (!empty($tradeInfo["tradeStatus"])) {
                TradeStatusUtils::tradeStatusDesc($tradeInfo["tradeStatus"]);
            } else {
                if ("99998" === $code) {
                    echo "系统异常，请等待异步通知或者调用查询接口获取交易状态\n";
                } else if ("00007" === $code) {
                    echo "调用金融渠道超时，请等待异步通知或调用交易查询接口获取交易状态\n";
                } else if ("00019" === $code) {
                    echo "银行系统异常，请等待异步通知或调用交易查询接口获取交易状态\n";
                } else if ("99999" === $code) {
                    echo "处理中，请等待异步通知或调用交易查询接口获取交易状态\n";
                } else {
                    echo "可以置为失败\n";
                }
            }
        }
    }

    /**
     * 交易请求及确认 错误码及交易状态处理说明
     * @param $dataBase64 base64字符串
     */
    static function creditagreementStatus($dataBase64)
    {
        $bizData = base64_decode($dataBase64);
        $objData = json_decode($bizData, true);
        $code = $objData["code"];
        //echo "返回报文:".$bizData."\n";
        //return $objData;
        return $bizData;
    }

    /**
     * 交易查询的交易状态处理说明
     * @param $dataBase64 base64字符串
     */
    static function tradeQueryTradeStatus($dataBase64)
    {
        $bizData = base64_decode($dataBase64);
        $objData = json_decode($bizData, true);
        $code = $objData["code"];
        return $objData;
        //echo "返回报文:".$bizData."\n";
        if ("0000" === $code) {
            //$tradeInfo = $objData["tradeInfo"];
            /* if(!empty($tradeInfo)) {
                $tradeStatus = $tradeInfo["tradeStatus"];
            } */

            if (!empty($objData["tradeStatus"])) {
                TradeStatusUtils::tradeStatusDesc($objData["tradeStatus"]);
            } else {
                //echo "未知错误，请联系技术支持进行查询\n";
            }
        } else if ("00072" === $code) {
            //TODO 1、请核实二级商户号和商户订单号是否正确，如果确定正确，说明交易未请求成功，可以不用换单号重新发起交易
            //TODO 2、如果确定参数没问题，在交易请求10分钟后查询返回该错误码时，可以置为失败
            echo "交易不存在\n";
        } else if ("00011" === $code) {
            echo "提交参数不正确（二级商户号或者商户订单号为空）\n";
        } else {
            echo "请看接口文档错误码描述\n";
        }
        return $objData;
    }

    /**
     * 交易状态判断
     * Enter description here ...
     * @param unknown_type $tradeStatus
     */
    static function tradeStatusDesc($tradeStatus)
    {
        if (is_null($tradeStatus)) {
            return;
        }
        if (Constants::TRADE_FINI === $tradeStatus) {
            echo "交易成功\n";
        } else if (Constants::TRADE_CLOS === $tradeStatus) {
            echo "交易失败\n";
        } else if (Constants::TRADE_WPAR === $tradeStatus || Constants::TRADE_BUID === $tradeStatus || Constants::TRADE_ACSU === $tradeStatus) {
            echo "交易处理中，等待异步通知或者调用查询接口获取交易的状态\n";
        } else if (Constants::TRADE_REFU === $tradeStatus) {
            echo "交易退款\n";
        }
    }
}

?>