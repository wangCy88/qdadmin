<?php
/**
 * Created by PhpStorm.
 * User: 淘气
 * Date: 2017/2/4
 * Time: 12:23
 */

class HttpCurl
{
    /**
     * 模拟POST与GET请求
     *
     * Examples:
     * ```
     * HttpCurl::request('http://example.com/', 'post', array(
     *  'user_uid' => 'root',
     *  'user_pwd' => '123456'
     * ));
     *
     * HttpCurl::request('http://example.com/', 'post', '{"name": "peter"}');
     *
     * HttpCurl::request('http://example.com/', 'post', array(
     *  'file1' => '@/data/sky.jpg',
     *  'file2' => '@/data/bird.jpg'
     * ));
     *
     * // windows
     * HttpCurl::request('http://example.com/', 'post', array(
     *  'file1' => '@G:\wamp\www\data\1.jpg',
     *  'file2' => '@G:\wamp\www\data\2.jpg'
     * ));
     *
     * HttpCurl::request('http://example.com/', 'get');
     *
     * HttpCurl::request('http://example.com/?a=123', 'get', array('b'=>456));
     * ```
     *
     * @param string $url [请求地址]
     * @param string $type [请求方式 post or get]
     * @param bool|string|array $data [传递的参数]
     * @param array $header [可选：请求头] eg: ['Content-Type:application/json']
     * @param int $timeout [可选：超时时间]
     *
     * @return array($body, $header, $status, $errno, $error)
     * - $body string [响应正文]
     * - $header string [响应头]
     * - $status array [响应状态]
     * - $errno int [错误码]
     * - $error string [错误描述]
     */
    public static function request($url, $type, $data = false, $header = [], $timeout = 0)
    {
        $cl = curl_init();
        // 兼容HTTPS
        if (stripos($url, 'https://') !== FALSE) {
            curl_setopt($cl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($cl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($cl, CURLOPT_SSLVERSION, 1);
        }
        // 设置返回内容做变量存储
        curl_setopt($cl, CURLOPT_RETURNTRANSFER, 1);
        // 设置需要返回Header
        curl_setopt($cl, CURLOPT_HEADER, true);
        // 设置请求头
        // 设置请求头
        if (count($header) > 0) {
            curl_setopt($cl, CURLOPT_HTTPHEADER, $header);
        }
        // 设置需要返回Body
        curl_setopt($cl, CURLOPT_NOBODY, 0);
        // 设置超时时间
        if ($timeout > 0) {
            curl_setopt($cl, CURLOPT_TIMEOUT, $timeout);
        }
        // POST/GET参数处理
        $type = strtoupper($type);
        if ($type == 'POST') {
            curl_setopt($cl, CURLOPT_POST, true);
            // convert @ prefixed file names to CurlFile class
            // since @ prefix is deprecated as of PHP 5.6
            if (class_exists('\CURLFile') && is_array($data)) {
                foreach ($data as $k => $v) {
                    if (is_string($v) && strpos($v, '@') === 0) {
                        $v = ltrim($v, '@');
                        $data[$k] = new \CURLFile($v);
                    }
                }
            }
            curl_setopt($cl, CURLOPT_POSTFIELDS, $data);
        }
        if ($type == 'GET' && is_array($data)) {
            if (stripos($url, "?") === FALSE) {
                $url .= '?';
            }
            $url .= http_build_query($data);
        }
        curl_setopt($cl, CURLOPT_URL, $url);
        // 读取获取内容
        $response = curl_exec($cl);

        // 读取状态
        $status = curl_getinfo($cl);
        // 读取错误号
        $errno = curl_errno($cl);
        // 读取错误详情
        $error = curl_error($cl);
        // 关闭Curl
        curl_close($cl);
        if ($errno == 0 && isset($status['http_code'])) {
            $header = substr($response, 0, $status['header_size']);
            $body = substr($response, $status['header_size']);
            return array($body, $header, $status, 0, '');
        } else {
            return array('', '', $status, $errno, $error);
        }
    }


    public static function Post($PostArry, $request_url)
    {
        $postData = $PostArry;
        $postDataString = http_build_query($postData);//格式化参数
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $request_url); // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE); // 从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_POST, true); // 发送一个常规的Post请求
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postDataString); // Post提交的数据包
        curl_setopt($curl, CURLOPT_TIMEOUT, 60); // 设置超时限制防止死循环返回
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $tmpInfo = curl_exec($curl); // 执行操作
        if (curl_errno($curl)) {

            $tmpInfo = curl_error($curl);//捕抓异常
        }
        curl_close($curl); // 关闭CURL会话
        return $tmpInfo; // 返回数据
    }


}