<?php
/**
 * Created by PhpStorm.
 * User: BF100311
 * Date: 2018/7/29
 * Time: 17:54
 */

class Utils
{

    /** 生成yyyymmddHHmiss 20160921142808
     * @return bool|string
     */
    static function trade_date()
    {//生成时间

        return date('YmdHis', time());

    }

    /**
     * 生成唯一订单号
     */
    static function create_uuid($prefix = "")
    {    //可以指定前缀
        $str = md5(uniqid(mt_rand(), true));
        $uuid = substr($str, 0, 8) . '-';
        $uuid .= substr($str, 8, 4) . '-';
        $uuid .= substr($str, 12, 4) . '-';
        $uuid .= substr($str, 16, 4) . '-';
        $uuid .= substr($str, 20, 12);
        return $prefix . $uuid;
    }


    /**
     * 返回32位md5值
     *
     * @param string $str 字符串
     * @return string $str 返回32位的字符串
     */
    static function md5_32($str)
    {
        if (empty($str)) {
            return "";
        }

        return md5($str);
    }


}