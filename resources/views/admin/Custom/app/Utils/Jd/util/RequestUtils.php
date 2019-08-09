<?php

/**
 *  加密请求工具类
 */
class RequestUtils
{

    /**
     * 发送http请求
     * @param unknown $data 请求数据
     * @param unknown $url url
     * @param unknown $encryptType_RSA 加密类型，不加密传null
     */
    static function tradeRequestSSL($bizData, $url)
    {
        //echo "url=".$url."\n";
        $dataJson = json_encode($bizData);
        /** 基础信息 **/
        $reqData = [];
        /** 会员号  **/
        $reqData["merId"] = Constants::CUSTOMER_NO;
        $reqData["merType"] = Constants::MER_TYPE_CCC;
        $reqData["reqId"] = uniqid();
        $reqData["reqTime"] = date("Y-m-d H:i:s", time());
        $reqData["charset"] = Constants::CHARSET_UTF8;
        $reqData["formatType"] = Constants::DATA_FORMAT_TYPE_JSON;
        $reqData["signType"] = Constants::SING_TYPE;
        $reqData["encType"] = Constants::ENCRYPT_TYPE_AP7;


//		$bizDataStr = SignUtils::map2LinkString($bizData);
        $encData = SignEnvelope::envelope($dataJson);//加密数据
        //echo "encData=".$encData."\n";
        $reqData["encData"] = $encData;
        $sign = SignUtils::sign($reqData, Constants::SING_TYPE, Constants::SHA_256_KEY);
        //echo "sign=".$sign."\n";
        $reqData["sign"] = $sign;

        $reqJson = http_build_query($reqData);
        //echo "接口入参=".$dataJson."\n";
        //echo "reqJson=".$reqJson."\n";
        list ($return_code, $return_content) = HttpUtils:: http_post_data($url, $reqJson);
        //echo "接口出参:".$return_content."\n";
        $return_content = str_replace("\n", '', $return_content);
        $return_content = str_replace("\r", '', $return_content);
        return $return_content;
    }
}

?>