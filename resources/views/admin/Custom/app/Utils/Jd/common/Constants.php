<?php

class Constants
{

    /** 域名 **/
    const DOMAIN = "https://wapi.jd.com";
    /** 1.1 发送短信url **/
    const CREDIT_PAY_REQUEST_URL = "/interc/agreementSignV";
    /** 1.2 短信确认url **/
    const CREDIT_PAY_CONFIRM_URL = "/interc/agreementSignConfirmV";
    /** 1.3 交易接口 **/
    const CREDIT_PAY_URL = "/interc/agreementPayV";
    /** 交易查询url **/
    const TRADE_QUERY_URL = "/interc/tradeQueryV";

    /****************************通用信息 start ************************************/
    /** 字符集 **/
    const CHARSET_UTF8 = "UTF-8";

    /** 数据格式 **/
    const DATA_FORMAT_TYPE_JSON = "JSON";

    /** 签名类型 **/
    const SING_TYPE = "SHA-256";

    /** 加密类型-新api接口     证书加密  **/
    const ENCRYPT_TYPE_AP7 = "AP7";

    /** 会员类型（固定值）  **/
    const MER_TYPE_CCC = "CCC";

    /****************************通用信息 end ************************************/


    /********************************业务参数*****************************************************/

    /** 会员号（ 36008开头） 请使用生产环境申请的 **/
    const CUSTOMER_NO = "360080004002741166";
    /** 二级商户号  **/
    const MERCHANT_NO = "110358850003";//商户号

    const SHA_256_KEY = "360080004002741166abc";//验签密钥 签名key，测试环境测试的都是test，生产上一个会员对应一个key

    const  passWord = "123123";//测试秘钥文件密码，随同pfx证书一起的密码
    /*const  pri="/usr/share/nginx/html/xiaohua/app/Utils/Jd/rsa/xinxi.pfx";//测试秘钥文件名（该文件包含公钥和私钥）外部商户请更换申请的pfx证书
    const  pub="/usr/share/nginx/html/xiaohua/app/Utils/Jd/rsa/npp_11_API2.pem";//公钥证书文件名，公钥证书已经放在demo中的rsa目录下*/
    const  pri = "/usr/share/nginx/html/qdadmin/app/Utils/Jd/rsa/xinxi.pfx";//测试秘钥文件名（该文件包含公钥和私钥）外部商户请更换申请的pfx证书
    const  pub = "/usr/share/nginx/html/qdadmin/app/Utils/Jd/rsa/npp_11_API2.pem";//公钥证书文件名，公钥证书已经放在demo中的rsa目录下
    const  path = "/php-7.2.14/scripts/dev";
    /*******************测试使用，生产需要替换相应的值和文件*******************/

    const TRADE_FINI = "FINI";
    const TRADE_CLOS = "CLOS";
    const TRADE_WPAR = "WPAR";
    const TRADE_BUID = "BUID";
    const TRADE_ACSU = "ACSU";
    const TRADE_REFU = "REFU";

    const IGNORE = array("sign_type", "sign_data", "encrypt_type", "encrypt_data", "salt");
}