<?php

namespace wepay\join\demo\common;

use wepay\join\demo\common\Contants;
use wy\demo\SignEnvelope\SignEnvelope;

include 'SignEnvelope.php';

class SignUtils
{
    /**
     * 组装http请求数据
     * @param unknown $data 请求数据
     * @return string 返回http格式的参数字符串 如a=a&b=b
     */
    static function paddingDataString($data)
    {
        $linkStr = "";
        $isFirst = true;
        foreach ($data as $key => $value) {
            if (!$isFirst) {
                $linkStr .= "&";
            }
            $linkStr .= $key . "=" . urlencode($value);
            if ($isFirst) {
                $isFirst = false;
            }
        }
        return $linkStr;
    }

    /**
     * RSA加密
     * @param unknown $data 请求数据
     * @param unknown $encryptType_RSA 加密类型 Contants::encrypttype
     * @return obj
     */
    static function enctyptData($data, $encryptType_RSA)
    {
        $newData = [];
        $sign = SignUtils:: sign($data, Contants::signtype, Contants::signkey);
        if (null == $encryptType_RSA) {
            $data["sign_type"] = Contants::signtype;
            $data["sign_data"] = $sign;
            $newData = $data;
        } else {
            $newData["sign_type"] = Contants::signtype;
            $newData["sign_data"] = $sign;
            $newData["encrypt_type"] = $encryptType_RSA;
            $newData["customer_no"] = $data["customer_no"];
            if (Contants::encrypttype == $encryptType_RSA) {
                $dataStr = SignUtils::map2LinkString($data);
                $encryptData = SignEnvelope::envelope($dataStr);//加密数据
                //echo "---------->>".$encryptData."\n";
                $newData["encrypt_data"] = $encryptData;//加密数据
            } else {
                echo "不支持的加密类型";
            }
        }
        return $newData;
    }

    /**
     * 验证签名
     * @param unknown $objData 返回的数据（已转成object）
     * @return obj
     */
    static function verifySing($objData)
    {
        $newsign = SignUtils:: sign($objData, $objData["sign_type"], Contants::signkey);
        $oldsign = $objData["sign_data"];
        if ($newsign === $oldsign) {
            return $objData;
        } else {
            return null;
        }
    }

    /**
     * 签名
     * @param unknown $obj 请求数据
     * @param unknown $algorithm 签名类型 Contants::signtype
     * @param unknown $salt 签名密匙 Contants::signkey
     * @return string 签名后字符串
     */
    static function sign($obj, $algorithm, $salt)
    {
        $link = SignUtils:: map2LinkString($obj);
        $link .= $salt;
        $str = "";
        if ("SHA" == $algorithm) {
            $str = sha1($link);
        } else if ("SHA-256" == $algorithm) {
            $str = hash("sha256", $link);
        }
        $str = strtoupper($str);
        //echo $str."\n";
        return $str;
    }

    /**
     * 组装签名字符串
     * @param unknown $data 数据对象
     * @return string 按键升序排列后拼装的字符串 如a=xxx&b=xxx&e=xxx
     */
    static function map2LinkString($data)
    {
        $linkStr = "";
        $isFirst = true;
        ksort($data);
        foreach ($data as $key => $value) {
            $bool = false;
            foreach (Contants::IGNORE as $str) {
                if ($key . "" == $str . "") {
                    $bool = true;
                    break;
                }
            }
            if ($bool) {
                continue;
            }
            if (!$isFirst) {
                $linkStr .= "&";
            }
            $linkStr .= $key . "=" . $value;
            if ($isFirst) {
                $isFirst = false;
            }
        }
        //echo "--->".$linkStr."\n";
        return $linkStr;
    }
}

?>