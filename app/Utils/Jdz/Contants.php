<?php

namespace wepay\join\demo\common;
class Contants
{

    /*******************测试使用，对接生产系统需要和网银申请相关生产证书，替换相应的值和文件*******************/
    const httpurl = "https://mapi.jdpay.com";//测试地址，外部商户请更换生产地址
    const signkey = "51760e1494e6457ed49c518b03e07566f06a5d8f2989b3ec25bd348d54096132";//签名key，测试环境测试的都是test，生产上一个会员对应一个key

    const  passWord = "123123";//测试秘钥文件密码，随同pfx证书一起的密码
    const  pri = "/usr/share/nginx/html/xiaohua/app/Utils/Jdz/rsa/xinxi.pfx";//测试秘钥文件名（该文件包含公钥和私钥）外部商户请更换申请的pfx证书
    const  pub = "/usr/share/nginx/html/xiaohua/app/Utils/Jdz/rsa/npp_11_API2.pem";//代付证书文件名

    /*******************测试使用，生产需要替换相应的值和文件*******************/


    const signtype = "SHA-256";//签名类型
    const encrypttype = "RSA";//加密类型

    const RETURN_PARAM_NULL = "RETURN_PARAM_NULL";//返回数据为null
    const SYSTEM_ERROR = "SYSTEM_ERROR";
    const OUT_TRADE_NO_EXIST = "OUT_TRADE_NO_EXIST";
    const TRADE_NOT_EXIST = "TRADE_NOT_EXIST";
    const SUCCESS = "0000";

    const TRADE_FINI = "FINI";
    const TRADE_CLOS = "CLOS";
    const TRADE_WPAR = "WPAR";
    const TRADE_BUID = "BUID";
    const TRADE_ACSU = "ACSU";

    const IGNORE = array("sign_type", "sign_data", "encrypt_type", "encrypt_data", "salt");
    const CODE = array(
        "0000" => "成功",
        "EXPARTNER_INFO_UNCORRECT" => "传入商户接口信息不正确",
        "ILLEGAL_SIGN" => "签名验证出错",
        "ILLEGAL_ARGUMENT" => "输入参数有错误",
        "ILLEGAL_AUTHORITY" => "权限不正确",
        "CUSTOMER_NOT_EXIST" => "提交会员不存在",
        "ILLEGAL_CHARSET" => "字符集不合法",
        "ILLEGAL_CLIENT_IP" => "客户端IP地址无权访问服务",
        "SYSTEM_ERROR" => "系统错误",
        "OUT_TRADE_NO_EXIST" => "外部交易号已经存在",
        "TRADE_NOT_EXIST" => "交易不存在",
        "ILLEGAL_TRADE_TYPE" => "无效交易类型",
        "BUYER_USER_NOT_EXIST" => "买家会员不存在",
        "SELLER_USER_NOT_EXIST" => "卖家会员不存在",
        "BUYER_SELLER_EQUAL" => "买家、卖家是同一帐户 ",
        "USER_STATE_ERROR" => "会员状态不正确",
        "COMMISION_ID_NOT_EXIST" => "佣金收取帐户不存在",
        "COMMISION_SELLER_DUPLICATE" => "收取佣金帐户和卖家是同一帐户",
        "COMMISION_FEE_OUT_OF_RANGE" => "佣金金额超出范围",
        "TOTAL_FEE_OUT_OF_RANGE" => "交易总金额超出范围",
        "ILLEGAL_AMOUNT_FORMAT" => "非法金额格式",
        "ILLEGAL_TRADE_AMMOUT" => "交易金额不正确",
        "ILLEGAL_TRADE_CURRENCY" => "交易币种不正确",
        "SELF_TIMEOUT_NOT_SUPPORT" => "不支持自定义超时",
        "COMMISION_NOT_SUPPORT" => "不支持佣金 ",
        "VIRTUAL_NOT_SUPPORT" => "不支持虚拟収货方式",
        "PAYMENT_LIMITED" => "支付受限",
        "ILLEGAL_BANK_CARD_NO" => "卡号不正确",
        "ILLEGAL_BANK_CARD_VALID_PERIOD" => "卡有效期不正确",
        "ILLEGAL_ID_CARD_NO" => "身份证号码不正确",
        "ILLEGAL_BANK_CARD_NAME" => "持卡人姓名不正确",
        "ILLEGAL_BANK_CARD_TYPE" => "卡类型不正确 ",
        "REFUND_FAILED" => "退款失败",
        "CURRENT_PAY_CANNOT_REVOKE" => "当前支付请求状态无法撤销",
        "CURRENT_USER_DIFFERENT_FROM" => "当前用户和已登录绑定用户不一致",
        "ILLEGAL_PAY_TYPE" => "无效支付类型"
    );

    static function isContainCode($response_code)
    {
        return array_key_exists($response_code, self::CODE);
    }
}