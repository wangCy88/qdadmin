<?php

class SignUtils
{

    /**
     * 验证签名
     * @param unknown $objData 返回的数据（已转成object）
     * @return obj
     */
    static function verifySign($objData)
    {
        $oldsign = $objData["sign"];
        unset($objData["sign"]);

        $newsign = SignUtils:: sign($objData, $objData["signType"], Constants::SHA_256_KEY);
        if ($newsign === $oldsign) {
            //dump($objData);die;
            return $objData['data'];
        } else {
            return null;
        }
    }

    /**
     * 签名
     * @param unknown $obj 请求数据
     * @param unknown $algorithm 签名类型 Constants::signtype
     * @param unknown $salt 签名密匙 Constants::signkey
     * @return string 签名后字符串
     */
    static function sign($obj, $algorithm, $salt)
    {
        $link = SignUtils:: map2LinkString($obj);
        $link .= $salt;
        //echo "signData=".$link."\n";
        $str = "";
        if ("SHA" == $algorithm) {
            $str = sha1($link);
        } else if ("SHA-256" == $algorithm) {
            $str = hash("sha256", $link);
        }
        $str = strtoupper($str);
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
            foreach (Constants::IGNORE as $str) {
                if ($key . "" == $str . "") {
                    $bool = true;
                    break;
                }
            }
            if ($bool) {
                continue;
            }
            if ("" != $value) {
                if (!$isFirst) {
                    $linkStr .= "&";
                }
                $linkStr .= $key . "=" . $value;
                if ($isFirst) {
                    $isFirst = false;
                }
            }

        }
        return $linkStr;
    }
}

?>