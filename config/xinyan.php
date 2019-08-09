<?php
return [
    //编码格式
    'charset' => "UTF-8",

    //商户公钥(暂不使用)
    //'merchant_public_key' => app_path() . '/Utils/XinYan/Keys/bfkey_8000013189.cer',//test
    'merchant_public_key' => app_path() . '/Utils/XinYan/Keys/xinyan_pub.cer',

    //商户私钥
    //'merchant_private_key' => app_path() .'/Utils/XinYan/Keys/8000013189_pri.pfx',//test
    'merchant_private_key' => app_path() . '/Utils/XinYan/Keys/xinyan_pri.pfx',

    //商户号
    //'memberId' => '8000013189',//test
    'memberId' => '8150728218',

    //终端号
    //'terminalId' => '8000013189',//test
    'terminalId' => '8150728218',

    //私钥密码
    //'pfxPwd' => '217526',
    'pfxPwd' => '876543',

    //数据类型
    'dataType' => 'json',

    //版本号
    'probeVersions' => '1.4.0',
    'radarVersions' => '1.3.0',

    //加密方式
    'encrypt_type' => 'MD5',

    //智能探针url
    //'probeUrl' => 'https://test.xinyan.com/product/negative/v4/black',//test
    'probeUrl' => 'https://api.xinyan.com/product/negative/v4/black',

    //全景雷达url
    //'radarUrl' => 'https://test.xinyan.com/product/radar/v3/report',//test
    'radarUrl' => 'https://api.xinyan.com/product/radar/v3/report'
];
