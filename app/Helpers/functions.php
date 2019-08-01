<?php

//参数1：访问的URL，参数2：post数据(不填则为GET)，参数3：提交的$cookies,参数4：是否返回$cookies
function curl_request($url, $post = '', $cookie = '', $returnCookie = 0)
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)');
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
    curl_setopt($curl, CURLOPT_REFERER, "http://XXX");
    if ($post) {
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
    }
    if ($cookie) {
        curl_setopt($curl, CURLOPT_COOKIE, $cookie);
    }
    curl_setopt($curl, CURLOPT_HEADER, $returnCookie);
    curl_setopt($curl, CURLOPT_TIMEOUT, 10);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    $data = curl_exec($curl);
    if (curl_errno($curl)) {
        return curl_error($curl);
    }
    curl_close($curl);
    if ($returnCookie) {
        list($header, $body) = explode("\r\n\r\n", $data, 2);
        preg_match_all("/Set\-Cookie:([^;]*);/", $header, $matches);
        $info['cookie'] = substr($matches[1][0], 1);
        $info['content'] = $body;
        return $info;
    } else {
        return $data;
    }
}

//自定义函数手机号隐藏中间四位
function yc_phone($str)
{
    //$str = $str;
    $resstr = substr_replace($str, '****', 3, 4);
    return $resstr;
}

/**
 * @Name XXX
 * @Description  上传图片接口
 * @Param picture：图片 @ParamTest 图片文件
 * @apiParam   (params) {String} picture 图片文件
 * @Response 通用格式:{"code":响应码,"message":"错误描述","data":{}}
 * data{
 *    path:"图片地址",
 * }
 *
 */

function uploadCompanyImg($request)
{
    //dump($request);die;
    $file = $request->file();
    dump($file);
    die;
    header('Content-type: application/json');
    // 文件是否上传成功
    if ($file->isValid()) {
        // 获取文件相关信息
        $originalName = $file->getClientOriginalName(); //文件原名
        $ext = $file->getClientOriginalExtension();     // 扩展名

        $realPath = $file->getRealPath();   //临时文件的绝对路径

        $type = $file->getClientMimeType();     // image/jpeg
        $size = $file->getSize();
        $this->_result['code'] = 101;
        if ($size > 2 * 1024 * 1024) {
            $this->_result['message'] = '文件大小超过2M';
            echo json_encode($this->_result);
            exit();
        }
        $extArr = array('jpg', 'jpeg', 'png', 'gif');
        if (!in_array($ext, $extArr)) {
            $this->_result['message'] = '文件格式不正确';
            echo json_encode($this->_result);
            exit();
        }

        // 拼接文件名称
        $filename = date('YmdHis') . uniqid() . '.' . $ext;
        // 使用我们新建的upload_company_img本地存储空间（目录）
        //这里的upload_company_img是配置文件的名称
        $bool = Storage::disk('upload_company_img')->put($filename, file_get_contents($realPath));

        if ($bool) {
            $this->_result['code'] = 200;
            $this->_result['message'] = '成功';
            $url = 'https://api.bxbedu.com/static/study/images/company/' . date('Ym', time()) . '/' . $filename;
            $path = '/static/study/images/company/' . date('Ym', time()) . '/' . $filename;
            $this->_result['data'] = array('url' => $url, 'path' => $path);
            echo json_encode($this->_result);
        } else {
            $this->_result['message'] = '上传失败';
            echo json_encode($this->_result);
        }

    } else {
        $this->_result['message'] = '上传失败';
        echo json_encode($this->_result);
    }
}

function getRandomStr($len, $special = true)
{
    $chars = array(
        "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
        "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
        "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
        "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
        "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",
        "3", "4", "5", "6", "7", "8", "9"
    );

    if ($special) {
        $chars = array_merge($chars, array(
            "!", "@", "#", "$", "?", "|", "{", "/", ":", ";",
            "%", "^", "&", "*", "(", ")", "-", "_", "[", "]",
            "}", "<", ">", "~", "+", "=", ",", "."
        ));
    }

    $charsLen = count($chars) - 1;
    shuffle($chars);                            //打乱数组顺序
    $str = '';
    for ($i = 0; $i < $len; $i++) {
        $str .= $chars[mt_rand(0, $charsLen)];    //随机取出一位
    }
    return $str;
}

/**
 * //生成24位唯一订单号码，格式：YYYY-MMDD-HHII-SS-NNNN,NNNN-CC，
 * //其中：YYYY=年份，MM=月份，DD=日期，HH=24格式小时，II=分，SS=秒，NNNNNNNN=随机数，CC=检查码
 */
function getOrderNo()
{
    //飞鸟慕鱼博客
    @date_default_timezone_set("PRC");
    while (true) {
        //订购日期
        $order_date = date('Y-m-d');
        //订单号码主体（YYYYMMDDHHIISSNNNNNNNN）
        $order_id_main = date('YmdHis') . rand(10000000, 99999999);
        //订单号码主体长度
        $order_id_len = strlen($order_id_main);
        $order_id_sum = 0;
        for ($i = 0; $i < $order_id_len; $i++) {
            $order_id_sum += (int)(substr($order_id_main, $i, 1));
        }
        //唯一订单号码（YYYYMMDDHHIISSNNNNNNNNCC）
        $order_id = $order_id_main . str_pad((100 - $order_id_sum % 100) % 100, 2, '0', STR_PAD_LEFT);
        return $order_id;
    }
}


//自定义函数：time2string($second) 输入秒数换算成多少天/多少小时/多少分/多少秒的字符串
function time2string($second)
{
    $day = floor($second / (3600 * 24));
    $second = $second % (3600 * 24);//除去整天之后剩余的时间
    $hour = floor($second / 3600);
    $second = $second % 3600;//除去整小时之后剩余的时间
    $minute = floor($second / 60);
    $second = $second % 60;//除去整分钟之后剩余的时间
    //返回字符串
    return $day . '天' . $hour . '小时' . $minute . '分' . $second . '秒';
}

//用户数据加密       加密数据
function jiami($data, $num, $numb)
{
    $length = mb_strlen($data, 'utf8') - $num - $numb;
    $str = str_repeat("*", $length);//替换字符数量
    $re = substr_replace($data, $str, $num, $length);
    return $re;
}

/**
 * [将Base64图片转换为本地图片并保存]
 * @param $base64_image_content [要保存的Base64]
 * @param $path [要保存的路径]
 * @return bool|string
 */
function base64_image_content($base64_image_content, $path)
{
    //匹配出图片的格式
    if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)) {
        $type = $result[2];
        $new_file = $path . "/" . date('Ymd', time()) . "/";
        $basePutUrl = $new_file;

        if (!file_exists($basePutUrl)) {
            //检查是否有该文件夹，如果没有就创建，并给予最高权限
            //dd($basePutUrl);
            mkdir($basePutUrl, 0777, true);
        }
        $ping_url = getRandomStr(8, false) . time() . ".{$type}";
        $ftp_image_upload_url = $new_file . $ping_url;
        $local_file_url = $basePutUrl . $ping_url;

        if (file_put_contents($local_file_url, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
            //TODO 个人业务的FTP 账号图片上传
            //ftp_upload(C('REMOTE_ROOT').$ftp_image_upload_url,$local_file_url);
            return '/' . $ftp_image_upload_url;
        } else {
            return false;
        }
    } else {
        return false;
    }
}


