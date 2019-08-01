<?php

/**
 * 此加密demo非线程安全，只供参考不能直接用于生产环境。请开发人员自行根据系统情况进行开发
 *
 */
class SignEnvelope
{
    public static function envelope($data)
    {
        $Millisecond = SignEnvelope::getMillisecond();

        /***************************************/
        //保存源数据文件
        $dataFile = Constants::path . "/" . $Millisecond . "data.txt";
        //保存签名文件
        $signedFile = Constants::path . "/" . $Millisecond . "signed.txt";
        //保存签名后base64文件
        $signedDataFile = Constants::path . "/" . $Millisecond . "signedData.txt";
        //保存信封后文件
        $envelopeFile = Constants::path . "/" . $Millisecond . "envelope.txt";
        #加载p12
        openssl_pkcs12_read(file_get_contents(Constants::pri), $certs, Constants::passWord);
        $signCert = $certs ['cert'];
        $signKey = $certs['pkey'];

        #加载加密证书
        $encryCert = file_get_contents(Constants::pub);

        #加密原文
        $fp = fopen($dataFile, "w");
        fwrite($fp, $data);
        fclose($fp);
        #签名
        openssl_pkcs7_sign($dataFile, $signedFile, $signCert, array($signKey, ""), NULL, PKCS7_NOATTR | PKCS7_BINARY | PKCS7_NOSIGS);
        $signedBase64 = file_get_contents($signedFile);
        $signedBase64 = substr($signedBase64, strpos($signedBase64, "base64") + strlen("base64"));
        trim($signedBase64);
        $signedBase64 = str_replace("\n", '', $signedBase64);
        $signedBase64 = str_replace("\r", '', $signedBase64);
        $signedData = base64_decode($signedBase64);

        $fp = fopen($signedDataFile, "w");
        fwrite($fp, $signedData);
        fclose($fp);
        #信封
        openssl_pkcs7_encrypt($signedDataFile, $envelopeFile,
            $encryCert, NULL, PKCS7_BINARY, OPENSSL_CIPHER_3DES);
        $envelopeBase64 = file_get_contents($envelopeFile);
        $envelopeBase64 = substr($envelopeBase64, strpos($envelopeBase64, "base64") + strlen("base64"));
        trim($envelopeBase64);
        $envelopeBase64 = base64_decode($envelopeBase64);
        $envelopeBase64 = base64_encode($envelopeBase64);
//		echo "加密数据envelopeBase64:".$envelopeBase64."\n";
        //删除加密过程中创建的文件
        unlink($dataFile);
        unlink($signedFile);
        unlink($signedDataFile);
        unlink($envelopeFile);
        return $envelopeBase64;
    }

    static function getMillisecond()
    {
        list($s1, $s2) = explode(' ', microtime());
        return (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000) . "";
    }
}

?>
