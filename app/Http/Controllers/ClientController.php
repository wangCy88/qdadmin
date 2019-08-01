<?php

namespace App\Http\Controllers;

use App\GrabCustomFormClick;
use App\GrabCustomFormTotal;
use App\GrabCustomFrom;
use App\GrabFeedback;
use App\GrabUserFormClick;
use App\GrabUserFrom;
use App\GrabUsers;
use App\GrabUsersPre;
use App\GrabUsersWallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use App\GrabCustom;
use App\GrabCustomHigh;
use App\GrabUserFormTotal;

class ClientController extends Controller
{
    //用户注册
    public static function register(Request $request)
    {
        //dd($request -> input());
        \Log::LogWirte('request:' . json_encode($request->input()), 'register');
        //验证手机号
        //dd($request -> input());
        if (!self::phoneVerify($request->phone)) {
            return response()->json(['code' => 1, 'msg' => 'error phone']);
        }
        //验证商户号
        if (!self::mchidVerify($request->mchid)) {
            return response()->json(['code' => 2, 'msg' => 'error mchid']);
        }
        //验证图片验证码
        /*if (!empty($request->imgcode)) {
            if (!self::imgcodeVerify($request->imgcode, $request->phone)) {
                //return response()->json(['code' => 4, 'msg' => 'error imgcode']);
            }
        }*/
        //验证短信验证码
        /*if (!self::mscodeVerify($request->mscode, $request->phone)) {
            //return response()->json(['code' => 5, 'msg' => 'error mscode']);
        }*/
        //验证密码
        if ($request->password) {
            if (!self::passwordVerify($request->password)) {
                return response()->json(['code' => 6, 'msg' => 'error password']);
            }
        }
        $channelId = 0;
        if ($request->channelCode) {
            $channelId = GrabUserFrom::where('code', $request->channelCode)->value('id');
        }

        //录入注册数据
        $ip = \Common::getIp();
        //dd($request -> input());
        $id = self::insertPreData($request, $ip);
        if (!$id) {
            return response()->json(['code' => 7, 'msg' => '重复注册']);
        } else {
            GrabUsersWallet::insert(
                [
                    'phone' => $request->phone,
                    'user_id' => $id,
                    'created_at' => date('Y-m-d H:i:s'),
                    'card_ticket' => 0,
                    'points' => 0,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]
            );
            if ($channelId) {
                $status = GrabUserFrom::where('code', $request->channelCode)->value('status');
                $where2 = ['channel_id' => $channelId, 'date' => date('Y-m-d'), 'status' => $status];
                $info = GrabUserFormClick::where($where2)->select('register', 'zhuan_register')->first();
                $form = GrabUserFrom::where('id', $channelId)->first();
                if ($info) {
                    if ($info->register == 0 && $info->zhuan_register == 0) {
                        GrabUserFormClick::where($where2)->update(['register' => 1, 'zhuan_register' => 1]);
                    }
                    if ($info->register > 0 && $info->zhuan_register > 0) {
                        GrabUserFormClick::where($where2)->increment('register');
                        $ratio = $form->ratio;
                        $zhen = $info->zhuan_register * 100 / $info->register;
                        if ($ratio > $zhen) {
                            GrabUserFormClick::where($where2)->increment('zhuan_register');
                        }
                    }

                }
            }
        }
        return response()->json(['code' => 0, 'msg' => 'register success']);
    }

    /**
     * 发送短信验证码
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public static function clientSendMsg(Request $request)
    {
        \Log::LogWirte('request:' . json_encode($request->input()), 'clientSendMsg');
        if (!$request->phone) {
            return response()->json(['code' => 2, 'msg' => '参数错误']);
        }
        $code = rand(1000, 9999);
        self::sendMsg($request->phone, $code);
        return response()->json(['code' => 0, 'msg' => 'success']);
    }


    /**
     * 发送短信验证码
     * @param $phone
     * @param $code
     */
    public static function sendMsg($phone, $code)
    {
        //echo 1;die;
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

    //用户登录
    public static function clientLogin(Request $request)
    {
        //验证手机号
        if (!self::phoneVerify($request->phone)) {
            return response()->json(['code' => 1, 'msg' => 'error phone']);
        }
        //验证商户号
        if (!self::mchidVerify($request->mchid)) {
            return response()->json(['code' => 2, 'msg' => 'error mchid']);
        }
        //验证图片验证码
        if (!empty($request->imgcode)) {
            if (!self::imgcodeVerify($request->imgcode, $request->phone)) {
                return response()->json(['code' => 3, 'msg' => 'error imgcode']);
            }
        }
        //验证密码
        if (!self::passwordVerify($request->password)) {
            return response()->json(['code' => 4, 'msg' => 'error password']);
        }
        //登录验证
        $data = self::loginVerify($request);
        if (!$data) {
            return response()->json(['code' => 5, 'msg' => 'error login']);
        }
        //更新登录数据
        $ip = \Common::getIp();
        self::updateLoginData($request, $ip);
        return response()->json(['code' => 0, 'msg' => 'login success', 'user_id' => $data]);
    }

    //用户验证码登录
    public static function clientCodeLogin(Request $request)
    {
        //验证手机号
        if (!self::phoneVerify($request->phone)) {
            return response()->json(['code' => 1, 'msg' => 'error phone']);
        }
        //验证商户号
        if (!self::mchidVerify($request->mchid)) {
            return response()->json(['code' => 2, 'msg' => 'error mchid']);
        }
        //验证短信验证码
        if (!self::mscodeVerify($request->mscode, $request->phone)) {
            //return response()->json(['code' => 3, 'msg' => 'error mscode']);
        }
        $data = self::codeLoginVerify($request);
        if (!$data) {
            return response()->json(['code' => 4, 'msg' => 'error login']);
        }
        //更新登录数据
        $ip = \Common::getIp();
        self::updateLoginData($request, $ip);
        return response()->json(['code' => 0, 'msg' => 'login success', 'user_id' => $data]);
    }

    public function ClientIndex(Request $request)
    {
        $url = "/upload/20190715/201907151857565d2c5c343bef6.jpg";
        return response()->json(['code' => 0, 'msg' => 'login success', 'data' => ['pic' => $url]]);
    }

    //验证手机号
    private static function phoneVerify($phone)
    {
        if (empty($phone) || !preg_match("/^1[3456789]\d{9}$/", $phone)) {
            return false;
        }
        return true;
    }

    //验证商户号
    private static function mchidVerify($mchid)
    {
        if (empty($mchid) || !preg_match("/^\d{1,4}$/", $mchid)) {
            return false;
        }
        return true;
    }

    //验证图片验证码
    private static function imgcodeVerify($imgcode, $phone)
    {
        $code = Redis::get('qdimgcode_' . $phone);
        if ($code != strtoupper($imgcode)) {
            return false;
        }
        return true;
    }

    //验证短信验证码
    private static function mscodeVerify($mscode, $phone)
    {
        $code = Redis::get('qdmscode_' . $phone);
        if ($code != strtoupper($mscode)) {
            return false;
        }
        Redis::set('qdmscode_' . $phone, null);
        return true;
    }

    //验证密码
    private static function passwordVerify($password)
    {
        if (empty($password)) {
            return false;
        }
        return true;
    }

    //录入注册数据
    private static function insertPreData($request, $ip)
    {
        $info = GrabUsersPre::where('phone', $request->phone)->first();
        if ($info) {
            return false;
        }
        $brand = empty($request->brand) ? '' : $request->brand;
        $version = empty($request->version) ? '' : $request->version;
        $imei = empty($request->imei) ? '' : $request->imei;
        $mac = empty($request->mac) ? '' : $request->mac;
        $location = empty($request->location) ? '' : $request->location;
        $upid = empty($request->upid) ? '' : $request->upid;
        $province = empty($request->province) ? '' : $request->province;
        $city = empty($request->city) ? '' : $request->city;
        $area = empty($request->area) ? '' : $request->area;
        $pid = 0;
        //dd($request -> input());
        $channel = 0;
        if (!empty($request->channelCode)) {
            $channel = GrabUserFrom::where('code', $request->channelCode)->value('id');
        }
        if (!empty($request->parentCode)) {
            $pid = GrabUsersPre::where('rand_str', $request->parentCode)->value('id');
            $channel = GrabUsersPre::where('rand_str', $request->parentCode)->value('channel');
        }
        $mchid = 1;
        $cond = ['phone' => $request->phone];
        $data = [
            'mchid' => $request->mchid,
            'password' => md5($request->password),
            'brand' => $brand,
            'version' => $version,
            'imei' => $imei,
            'mac' => $mac,
            'location' => $location,
            'reg_ip' => $ip,
            'upid' => $upid,
            'province' => $province,
            'city' => $city,
            'area' => $area,
            'rand_str' => strtoupper(getRandomStr(8, false)),
            'pid' => $pid,
            'channel' => $channel
        ];
        $input = $cond + $data;
        $result = GrabUsersPre::insertGetId($input);
        return $result;
    }

    //登录验证
    private static function loginVerify($request)
    {
        $data = GrabUsersPre::where(['phone' => $request->phone, 'password' => md5($request->password)])
            ->value('id');
        if (!$data) {
            return false;
        }
        return $data;
    }

    //验证码登录验证
    private static function codeLoginVerify($request)
    {
        $data = GrabUsersPre::where(['phone' => $request->phone])->value('id');
        if (!$data) {
            return false;
        }
        return $data;
    }

    //更新登录数据
    private static function updateLoginData($request, $ip)
    {
        $location = empty($request->location) ? '' : $request->location;
        $brand = empty($request->brand) ? '' : $request->brand;
        $version = empty($request->version) ? '' : $request->version;
        $imei = empty($request->imei) ? '' : $request->imei;
        $mac = empty($request->mac) ? '' : $request->mac;
        $where = ['phone' => $request->phone];
        $updateData = [
            'brand' => $brand,
            'version' => $version,
            'imei' => $imei,
            'mac' => $mac,
            'location' => $location,
            'lgn_ip' => $ip,
            'login_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        $result = GrabUsersPre::where($where)->update($updateData);
        return $result;
    }

    //基础认证
    public static function baseAuth(Request $request)
    {
        //验证手机号
        if (!self::phoneVerify($request->phone)) {
            return response()->json(['code' => 1, 'msg' => 'error phone']);
        }
        //验证姓名
        if (!self::nameVerify($request->name)) {
            return response()->json(['code' => 2, 'msg' => 'error name']);
        }
        //验证身份证
        if (!self::idNumberVerify($request->id_number)) {
            return response()->json(['code' => 3, 'msg' => 'error id_number']);
        }
        //验证城市
        if (!self::compCityVerify($request->comp_city)) {
            return response()->json(['code' => 4, 'msg' => 'error company']);
        }
        //验证单位全称
        if (!self::companyVerify($request->company)) {
            return response()->json(['code' => 5, 'msg' => 'error company']);
        }
        //验证公司电话
        if (!empty($request->comp_code) || !empty($request->comp_phone)) {
            if (!self::companyPhoneVerify($request->comp_code, $request->comp_phone)) {
                return response()->json(['code' => 6, 'msg' => 'error company phone']);
            }
        }
        //验证微信号
        if (!self::wechatVerify($request->wechat)) {
            return response()->json(['code' => 7, 'msg' => 'error wechat']);
        }
        //存储基础数据
        if (!self::insertBaseData($request)) {
            return response()->json(['code' => 8, 'msg' => 'error data']);
        }
        //处理图片
        $imageName1 = md5('qd1_' . $request->phone) . '.png';
        $imageUrl1 = '/usr/share/nginx/html/qdocr/' . $imageName1;
        if (!self::dealWithPic($request->image1, $imageUrl1)) {
            return response()->json(['code' => 9, 'msg' => 'error image']);
        }
        $imageName2 = md5('qd2_' . $request->phone) . '.png';
        $imageUrl2 = '/usr/share/nginx/html/qdocr/' . $imageName2;
        if (!self::dealWithPic($request->image2, $imageUrl2)) {
            return response()->json(['code' => 10, 'msg' => 'error image']);
        }
        $imageName3 = md5('qd3_' . $request->phone) . '.png';
        $imageUrl3 = '/usr/share/nginx/html/qdocr/' . $imageName3;
        if (!self::dealWithPic($request->image3, $imageUrl3)) {
            return response()->json(['code' => 11, 'msg' => 'error image']);
        }
        //更新认证状态
        if (!self::updateAuthStatus($request->phone, 1)) {
            return response()->json(['code' => 12, 'msg' => 'error status']);
        }
        return response()->json(['code' => 0, 'msg' => 'success']);
    }

    //验证姓名
    private static function nameVerify($name)
    {
        if (empty($name) || strlen($name) > 30) {
            return false;
        }
        return true;
    }

    //验证身份证
    private static function idNumberVerify($id_number)
    {
        if (
            empty($id_number) ||
            !preg_match('/^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}([0-9]|X)$/', $id_number)
        ) {
            return false;
        }
        return true;
    }

    //验证城市
    private static function compCityVerify($comp_city)
    {
        if (empty($comp_city)) {
            return false;
        }
        return true;
    }

    //验证单位全称
    private static function companyVerify($company)
    {
        if (empty($company) || strlen($company) > 128) {
            return false;
        }
        return true;
    }

    //验证公司电话
    private static function companyPhoneVerify($comp_code, $comp_phone)
    {
        if (empty($comp_code) || empty($comp_phone) || !preg_match('/^\d+$/', $comp_code . $comp_phone)) {
            return false;
        }
        return true;
    }

    //验证微信号
    private static function wechatVerify($wechat)
    {
        if (empty($wechat) || strlen($wechat) > 30) {
            return false;
        }
        return true;
    }

    //存储基础数据
    private static function insertBaseData($request)
    {
        $cond = ['phone' => $request->phone];
        $id = GrabUsersPre::where($cond)->value('id');
        if (!$id) {
            return false;
        }
        $comp_code = empty($request->comp_code) ? '' : $request->comp_code;
        $comp_phone = empty($request->comp_phone) ? '' : $request->comp_phone;
        $data = [
            'name' => $request->name, 'id_number' => $request->id_number, 'comp_city' => $request->comp_city,
            'company' => $request->company, 'comp_code' => $comp_code, 'comp_phone' => $comp_phone,
            'wechat' => $request->wechat
        ];
        $result = GrabUsers::firstOrCreate($cond, $data);
        if (!$result->wasRecentlyCreated) {
            return false;
        }
        return true;
    }

    //处理图片
    private static function dealWithPic($image, $url)
    {
        if (empty($image)) {
            return false;
        }
        if (strstr($image, ",")) {
            $image = explode(',', $image);
            $image = $image[1];
        }
        $result = file_put_contents($url, base64_decode($image));
        if (!$result) {
            return false;
        }
        return true;
    }

    //更新认证状态
    public static function updateAuthStatus($phone, $status)
    {
        $where = ['phone' => $phone];
        if (GrabUsersPre::where($where)->value('auth_status') >= $status) {
            return false;
        }
        $updateData = ['auth_status' => $status, 'updated_at' => date('Y-m-d H:i:s')];
        $result = GrabUsersPre::where($where)->update($updateData);
        return $result;
    }

    //获取用户基础数据
    public static function getUserInfo(Request $request)
    {
        //验证手机号
        if (!self::phoneVerify($request->phone)) {
            return response()->json(['code' => 1, 'msg' => 'error phone']);
        }
        //获取用户数据
        $data = self::userInfo($request);
        if (!$data) {
            return response()->json(['code' => 3, 'msg' => 'error data']);
        }
        return response()->json(['code' => 0, 'data' => $data]);
    }

    //获取用户数据
    private static function userInfo($request)
    {
        $where = ['phone' => $request->phone];
        $data = GrabUsers::where($where)->first();
        return $data;
    }

    //修改密码
    public static function changePassword(Request $request)
    {
        //验证手机号
        if (!self::phoneVerify($request->phone)) {
            return response()->json(['code' => 1, 'msg' => 'error phone']);
        }
        //验证商户号
        if (!self::mchidVerify($request->mchid)) {
            //return response()->json(['code' => 2, 'msg' => 'error mchid']);
        }
        //echo 1;die;
        //验证密码
        if (!self::passwordVerify($request->oldPwd) || !self::passwordVerify($request->newPwd) || !self::passwordVerify($request->newPwd2)) {
            return response()->json(['code' => 3, 'msg' => 'error password']);
        }
        //验证两次新密码输入一致
        if ($request->newPwd != $request->newPwd2) {
            return response()->json(['code' => 4, 'msg' => 'newPwd not same']);
        }
        //验证旧密码
        if (!self::oldPwdVerify($request)) {
            return response()->json(['code' => 5, 'msg' => 'error oldPwd']);
        }
        //更新密码
        if (!self::updatePwd($request)) {
            return response()->json(['code' => 6, 'msg' => 'fail']);
        }
        return response()->json(['code' => 0, 'msg' => 'success']);
    }

    //找回密码
    public static function retrievePassword(Request $request)
    {
        //验证手机号
        if (!self::phoneVerify($request->phone)) {
            return response()->json(['code' => 1, 'msg' => 'error phone']);
        }
        //验证商户号
        if (!self::mchidVerify($request->mchid)) {
            //return response()->json(['code' => 2, 'msg' => 'error mchid']);
        }
        //验证密码
        if (!self::passwordVerify($request->newPwd) || !self::passwordVerify($request->newPwd2)) {
            return response()->json(['code' => 3, 'msg' => 'error password']);
        }
        //验证两次新密码输入一致
        if ($request->newPwd != $request->newPwd2) {
            return response()->json(['code' => 4, 'msg' => 'newPwd not same']);
        }
        //验证短信验证码
        if (!self::mscodeVerify($request->mscode, $request->phone)) {
            return response()->json(['code' => 5, 'msg' => 'error mscode']);
        }
        //更新密码
        if (!self::updatePwd($request)) {
            return response()->json(['code' => 6, 'msg' => 'fail']);
        }
        return response()->json(['code' => 0, 'msg' => 'success']);
    }

    //验证旧密码
    private static function oldPwdVerify($request)
    {
        $where = ['phone' => $request->phone, 'password' => md5($request->oldPwd)];
        $id = GrabUsersPre::where($where)->value('id');
        if (!$id) {
            return false;
        }
        return true;
    }

    //更新密码
    private static function updatePwd($request)
    {
        $where = ['phone' => $request->phone];
        $updateData = ['password' => md5($request->newPwd), 'updated_at' => date('Y-m-d H:i:s')];
        $result = GrabUsersPre::where($where)->update($updateData);
        return $result;
    }


    //获取姓名
    private static function getName($phone)
    {
        $where = ['phone' => $phone];
        $name = GrabUsers::where($where)->value('name');
        if (!$name) {
            return false;
        }
        return $name;
    }


    //图片验证码
    public static function createImgCode(Request $request)
    {
        //dd($request -> input());
        //验证手机号
        if (!self::phoneVerify($request->phone)) {
            return response()->json(['code' => 1, 'msg' => 'error phone']);
        }
        $image = imagecreatetruecolor(100, 30); //imagecreatetruecolor函数建一个真彩色图像
        //生成彩色像素
        $bgcolor = imagecolorallocate($image, 255, 255, 255); //白色背景     imagecolorallocate函数为一幅图像分配颜色
        $textcolor = imagecolorallocate($image, 0, 0, 255); //蓝色文本
        //填充函数，xy确定坐标，color颜色执行区域填充颜色
        imagefill($image, 0, 0, $bgcolor);
        $captch_code = ""; //初始空值

        //该循环,循环取数
        for ($i = 0; $i < 4; $i++) {
            $fontsize = 6;
            $x = ($i * 25) + rand(5, 10);
            $y = rand(5, 10); //位置随机
            //  $fontcontent=$i>2?chr(rand(97,122)):chr(rand(65,90));//是小写，否则是大写
            $data = 'abcdefghijkmnpqrstuvwxyz3456789';
            $fontcontent = substr($data, rand(0, strlen($data) - 1), 1); //strlen仅仅是一个计数器的工作  含数字和字母的验证码
            //可以理解为数组长度0到30

            $fontcolor = imagecolorallocate($image, rand(0, 100), rand(0, 100), rand(0, 100)); //随机的rgb()值可以自己定

            imagestring($image, $fontsize, $x, $y, $fontcontent, $fontcolor); //水平地画一行字符串
            $captch_code .= $fontcontent;
        }
        //$_SESSION['authcode']=$captch_code;//将变量保存再session的authcode变量中
        Redis::set('qdimgcode_' . $request->phone, strtoupper($captch_code));
        Redis::expire('qdimgcode_' . $request->phone, 600);

        //该循环,循环画背景干扰的点
        for ($m = 0; $m <= 600; $m++) {

            $x2 = rand(1, 99);
            $y2 = rand(1, 99);
            $pointcolor = imagecolorallocate($image, rand(0, 255), rand(0, 255), rand(0, 255));
            imagesetpixel($image, $x2, $y2, $pointcolor); // 水平地画一串像素点
        }

        //该循环,循环画干扰直线
        for ($i = 0; $i <= 10; $i++) {
            $x1 = rand(0, 99);
            $y1 = rand(0, 99);
            $x2 = rand(0, 99);
            $y2 = rand(0, 99);
            $linecolor = imagecolorallocate($image, rand(0, 255), rand(0, 255), rand(0, 255));
            imageline($image, $x1, $y1, $x2, $y2, $linecolor); //画一条线段

        }
        header('content-type:image/png');
        imagepng($image);
    }

    /**
     * 推广页注册
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function customRegister(Request $request)
    {
        \Log::LogWirte('request:' . json_encode($request->input()), 'customRegister');
        $code = $request->msCode;
        $phone = $request->phone;
        /*if(!self::msCodeVerify($code , $phone)){
            //return response()->json(['code' => 1 , 'msg' => '短信验证码错误' ]);
        }*/
        $res = GrabCustom::where('phone', $phone)->first();
        if ($res) {
            return response()->json(['code' => 0, 'msg' => '更新资料']);
        }
        GrabCustom::insert(
            [
                'phone' => $phone,
                'channel' => 3,
                'province' => $request->province,
                'city' => $request->city,
                'area' => $request->area ? $request->area : ''
            ]
        );
        return response()->json(['code' => 0, 'msg' => '申请成功']);
    }

    /**
     * 完善资料
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function perfectData(Request $request)
    {
        $userId = GrabCustom::where('phone', $request->phone)->value('id');
        if (!$userId) {
            return response()->json(['code' => 1, 'msg' => '请注册']);
        }
        $info = GrabCustomHigh::where('custom_id', $userId)->first();
        if (!$info) {
            $pid = GrabCustomHigh::insertGetId(
                [
                    'custom_id' => $userId
                ]
            );
            if (!$pid) {
                return response()->json(['code' => 2, 'msg' => '资料填写失败']);
            }
        } else {
            $pid = $info->id;
        }
        GrabCustom::where('id', $userId)->update(
            [
                'sex' => $request->sex,
                'age' => $request->age,
                'province' => $request->province,
                'city' => $request->city,
                'area' => $request->area,
                'name' => $request->name,
                'withdraw_amount' => $request->withdraw_amount,
                'credit' => $request->credit
            ]
        );
        GrabCustomHigh::where('id', $pid)->update(
            [
                'withdraw_amount' => $request->withdraw_amount,
                'social' => $request->social,
                'housing_fund' => $request->housing_fund,
                'wages' => $request->wages,
                'human_life' => $request->human_life,
                'webank' => $request->webank,
                'credit' => $request->credit,
                'house' => $request->house,
                'car' => $request->car,
                'job' => $request->job
            ]
        );
        return response()->json(['code' => 0, 'msg' => '申请成功']);
    }

    /**
     * [channelTotal 渠道点击统计]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function userFormTotal(Request $request)
    {
        $request = $request->input();
        unset($request['/userFormTotal']);
        //dd($request);
        \Log::LogWirte('request:' . json_encode($request), 'userFormTotal');
        if (
            !isset($request['client_ip'])
            || !isset($request['channelCode'])
            || !isset($request['client_add'])
            || !isset($request['client_xx'])
            || !isset($request['time'])
        ) {
            exit(json_encode(['code' => 400, 'msg' => '参数错误', 'data' => $request]));
        }
        $channel_id = GrabUserFrom::where(['code' => $request['channelCode']])->value('id');
        $status = GrabUserFrom::where(['code' => $request['channelCode']])->value('status');
        if (!$channel_id) {
            exit(json_encode(['code' => 400, 'msg' => '渠道号错误']));
        }
        $is_valid = 0;
        $time = config('config.validTime');
        //dd($time);
        if ($time <= $request['time']) {
            $is_valid = 1;
        }
        $time2 = $request['time'];
        unset($request['time']);
        unset($request['channelCode']);
        $where = array_merge($request, ['channel_id' => $channel_id, 'date' => date('Y-m-d'), 'status' => $status]);
        $where2 = ['channel_id' => $channel_id, 'date' => date('Y-m-d'), 'status' => $status];
        //dd($where);

        $total = DB::table('grab_user_form_click')->where(['channel_id' => $channel_id])
            ->first(
                [
                    DB::raw('sum(register) as register'),
                    DB::raw('sum(click) as click')
                ]
            );
        if (!$total->register) {
            $total->register = 0;
        }
        if (!$total->click) {
            $total->click = 0;
        }
        $res = GrabUserFormTotal::where($where)->first();
        //dd($res);
        $config = GrabUserFrom::where(['id' => $channel_id])->first()->toArray();
        if ($res && $res->is_valid == 1) {
            //已统计过不再重复统计
        } elseif ($res && $res->is_valid == 0) {
            //已统计过更新为有效统计
            GrabUserFormTotal::where($where)->update(['is_valid' => 1]);
            //增加有效统计数和统计总数
            GrabUserFormTotal::where($where2)->increment('valid_click');
        } elseif (!$res) {
            //echo 2;die;
            # 未统计过
            $data = array_merge(
                $where,
                [
                    'time' => $time2,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'is_valid' => $is_valid
                ]
            );
            //dd($data);
            GrabUserFormTotal::insert($data);
            //判断该渠道有无记录
            $info = GrabUserFormClick::where($where2)->first();
            //dd($info);
            if ($info) {
                //echo 1;die;
                if ($total->click < 10) {
                    $click = 1;
                } else {
                    $click_scale = ($info->zhuan_uv / $info->click) * 100;
                    if ($click_scale <= $config['reg_price']) {
                        $click = 1;
                    } else {
                        $click = 0;
                    }
                }
                //dd(1);
                if ($is_valid) {
                    GrabUserFormClick::where($where2)->increment('click');
                    GrabUserFormClick::where($where2)->increment('valid_click');
                } else {
                    GrabUserFormClick::where($where2)->increment('click');
                }
                if ($click) {
                    GrabUserFormClick::where($where2)->increment('zhuan_uv');
                }
            } else {
                //echo 2;die;
                GrabUserFormClick::insert(
                    [
                        'channel_id' => $channel_id,
                        'click' => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                        'date' => date('Y-m-d'),
                        'updated_at' => date('Y-m-d H:i:s'),
                        'valid_click' => $is_valid ? 1 : 0,
                        'status' => $status,
                        'zhuan_uv' => 1
                    ]
                );
            }
        }
        exit(json_encode(['code' => 200, 'msg' => 'success']));
    }


    /**
     * 客户注册统计
     * @param Request $request
     */
    public function customFormTotal(Request $request)
    {
        $request = $request->input();
        unset($request['/userFormTotal']);
        //dd($request);
        \Log::LogWirte('request:' . json_encode($request), 'userFormTotal');
        if (
            !isset($request['client_ip'])
            || !isset($request['channelCode'])
            || !isset($request['client_add'])
            || !isset($request['client_xx'])
            || !isset($request['time'])
        ) {
            exit(json_encode(['code' => 400, 'msg' => '参数错误', 'data' => $request]));
        }
        $channel_id = GrabCustomFrom::where(['code' => $request['channelCode']])->value('id');
        $status = GrabCustomFrom::where(['code' => $request['channelCode']])->value('status');
        if (!$channel_id) {
            exit(json_encode(['code' => 400, 'msg' => '渠道号错误']));
        }
        $is_valid = 0;
        $time = config('config.validTime');
        //dd($time);
        if ($time <= $request['time']) {
            $is_valid = 1;
        }
        $time2 = $request['time'];
        unset($request['time']);
        unset($request['channelCode']);
        $where = array_merge($request, ['channel_id' => $channel_id, 'date' => date('Y-m-d'), 'status' => $status]);
        $where2 = ['channel_id' => $channel_id, 'date' => date('Y-m-d'), 'status' => $status];
        //dd($where);

        $total = GrabCustomFormClick::where(['channel_id' => $channel_id])
            ->first(
                [
                    DB::raw('sum(register) as register'),
                    DB::raw('sum(click) as click')
                ]
            );
        if (!$total->register) {
            $total->register = 0;
        }
        if (!$total->click) {
            $total->click = 0;
        }
        $res = GrabCustomFormTotal::where($where)->first();
        //dd($res);
        $config = GrabUserFrom::where(['id' => $channel_id])->first()->toArray();
        if ($res && $res->is_valid == 1) {
            //已统计过不再重复统计
        } elseif ($res && $res->is_valid == 0) {
            //已统计过更新为有效统计
            GrabCustomFormTotal::where($where)->update(['is_valid' => 1]);
            //增加有效统计数和统计总数
            GrabCustomFormClick::where($where2)->increment('valid_click');
        } elseif (!$res) {
            //echo 2;die;
            # 未统计过
            $data = array_merge(
                $where,
                [
                    'time' => $time2,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'is_valid' => $is_valid
                ]
            );
            //dd($data);
            GrabCustomFormTotal::insert($data);
            //判断该渠道有无记录
            $info = GrabCustomFormClick::where($where2)->first();
            //dd($info);
            if ($info) {
                //echo 1;die;
                if ($total->click < 10) {
                    $click = 1;
                } else {
                    $click_scale = ($info->zhuan_uv / $info->click) * 100;
                    if ($click_scale <= $config['reg_price']) {
                        $click = 1;
                    } else {
                        $click = 0;
                    }
                }
                //dd(1);
                if ($is_valid) {
                    GrabCustomFormClick::where($where2)->increment('click');
                    GrabCustomFormClick::where($where2)->increment('valid_click');
                } else {
                    GrabCustomFormClick::where($where2)->increment('click');
                }
                if ($click) {
                    GrabCustomFormClick::where($where2)->increment('zhuan_uv');
                }
            } else {
                //echo 2;die;
                GrabCustomFormClick::insert(
                    [
                        'channel_id' => $channel_id,
                        'click' => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                        'date' => date('Y-m-d'),
                        'updated_at' => date('Y-m-d H:i:s'),
                        'valid_click' => $is_valid ? 1 : 0,
                        'status' => $status,
                        'zhuan_uv' => 1
                    ]
                );
            }
        }
        exit(json_encode(['code' => 200, 'msg' => 'success']));
    }
}
