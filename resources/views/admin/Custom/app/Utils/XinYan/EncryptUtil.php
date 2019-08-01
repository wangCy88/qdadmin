<?php

/**
 * Created by PhpStorm.
 * User: BF100311
 * Date: 2018/7/29
 * Time: 17:59
 */
class EncryptUtil
{

    private $private_key;
    private $public_key;

    function __construct($private_key_path, $public_key_path, $private_key_password, $Debug = FALSE)
    {
        if (!$Debug) {
            ob_start();
        }
        // 初始化商户私钥
        $pkcs12 = file_get_contents($private_key_path);
        $private_key = array();
        openssl_pkcs12_read($pkcs12, $private_key, $private_key_password);
        //echo "私钥是否可用:", empty($private_key) == true ? '不可用':'可用', "\n";
        $this->private_key = $private_key["pkey"];

        /**
         * $keyFile = file_get_contents($public_key_path);
         * $this->public_key = openssl_get_publickey($keyFile);
         * LOG::LogWirte(empty($this->public_key) == true ? "公钥是否可用:不可用" : "公钥是否可用:可用");
         */

        if (!$Debug) {
            ob_end_clean();
        }
    }

    // 私钥加密
    function encryptedByPrivateKey($data_content)
    {
        $data_content = base64_encode($data_content);
        $encrypted = "";
        $totalLen = strlen($data_content);
        $encryptPos = 0;
        while ($encryptPos < $totalLen) {
            openssl_private_encrypt(substr($data_content, $encryptPos, 117), $encryptData, $this->private_key);
            $encrypted .= bin2hex($encryptData);
            $encryptPos += 117;
        }
        return $encrypted;
    }

    // 公钥解密
    function decryptByPublicKey($encrypted)
    {
        if (!function_exists('hex2bin')) {
            throw new Exception("请启用hex2bin方法");
        }

        $decrypt = "";
        $totalLen = strlen($encrypted);
        $decryptPos = 0;
        while ($decryptPos < $totalLen) {
            openssl_public_decrypt(hex2bin(substr($encrypted, $decryptPos, 256)), $decryptData, $this->public_key);
            $decrypt .= $decryptData;
            $decryptPos += 256;
        }
        $decrypt = base64_decode($decrypt);
        return $decrypt;
    }

    /**
     * function hex2bin( $str ) {
     * $sbin = "";
     * $len = strlen( $str );
     * for ( $i = 0; $i < $len; $i += 2 ) {
     * $sbin .= pack( "H*", substr( $str, $i, 2 ) );
     * }
     * return $sbin;
     * } */
}