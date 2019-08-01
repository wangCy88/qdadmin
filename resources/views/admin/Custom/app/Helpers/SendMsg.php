<?php

class SendMsg
{
    public static function sendCode($phone, $code)
    {
        $url = 'https://api.mysubmail.com/message/send.json';
        $appid = '34094';
        $signature = '57615de5b184b15ed0576cb86ac96dd6';
        $time = '10分钟';
        $setContent = '【帮带客】您的验证码是：' . $code . '，请在' . $time . '内输入。';
        $content['data'] = 'appid=' . $appid . '&content=' . $setContent . '&signature=' . $signature;
        $data = [
            'appid' => $appid,
            'signature' => $signature,
            'to' => $phone,
            'content' => $setContent
        ];
        //dump($data);die;
        Redis::set('qdmscode_' . $phone, $code);
        curl_request($url, $data);
    }

    public static function sendmail($phone, $setContent)
    {
        $url = 'https://api.mysubmail.com/message/send.json';
        $appid = '34094';
        $signature = '57615de5b184b15ed0576cb86ac96dd6';
        $time = '10分钟';
        $content['data'] = 'appid=' . $appid . '&content=' . $setContent . '&signature=' . $signature;
        $data = [
            'appid' => $appid,
            'signature' => $signature,
            'to' => $phone,
            'content' => $setContent
        ];
        curl_request($url, $data);
    }
}