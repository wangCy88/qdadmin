<?php

namespace App\Http\Controllers;

use App\GrabAliPay;
use App\GrabBankList;
use App\GrabCardTicket;
use App\GrabJdbindcard;
use App\GrabPoints;
use App\GrabUserCardticketDetail;
use App\GrabUserPointsDetail;
use App\GrabUsers;
use App\GrabUsersPre;
use App\GrabUsersWallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use PhpParser\Node\Stmt\TryCatch;

class UserController extends Controller
{
    public function __construct(Request $request)
    {
        //dump($request -> toArray());die;
        if (!$request->user_id) {
            exit(json_encode(['code' => 1, 'msg' => '参数错误！']));
        }
    }

    /**
     * 用户首页
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        /*$user = GrabUsersPre::where('id' , $request -> user_id)
            -> with(['grabUsersWallet' => function($q){
                $q -> select('card_ticket' , 'points' , 'phone');
            }])
            -> select('id' , 'phone')
            -> first();*/
        $user = GrabUsersWallet::where('user_id', $request->user_id)->select('phone', 'card_ticket', 'points')->first();
        //dump($user -> toArray());
        $user['phone'] = yc_phone($user->phone);
        return response()->json(['code' => 0, 'msg' => 'success', 'data' => $user->toArray()]);
    }

    /**
     * 用户资料/实名认证
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function userInfo(Request $request)
    {
        //echo 1;die;
        //dump($_FILES);die;
        //dump($request -> input());die;
        if ($request->isMethod('get')) {
            //get执行的代码
            //echo 1;die;
            $user = GrabUsers::where('user_id', $request->user_id)->first();
            //dump($user);die;
            return response()->json(['code' => 0, 'msg' => 'success', 'data' => $user]);
        } else {
            //dump($request -> file());
//            $file = $request->file();
//            if (!$file['id_pic']) {
//                return response()->json(['code' => 1, 'msg' => '请选择身份证照片']);
//            }
//            if(!$file['face_pic']){
//                return response()->json(['code' => 6, 'msg' => '请选择人脸照片']);
//            }
//            $arr = [];
//            foreach ($file as $k => $v) {
//                $originalName = $v->getClientOriginalName(); //文件原名
//                $ext = $v->getClientOriginalExtension();     // 扩展名
//
//                $realPath = $v->getRealPath();   //临时文件的绝对路径
//                $type = $v->getClientMimeType();     // image/jpeg
//                $size =$v->getSize();
//                $this->_result['code']=101;
//                if($size > 2*1024*1024){
//                    return response()->json(['code' => 5, 'msg' => '文件过大']);
//                }
//                $extArr = array('jpg','jpeg','png','gif');
//                if(!in_array($ext,$extArr)){
//                    $this->_result['message']='文件格式不正确';
//                    return response()->json(['code' => 4, 'msg' => '文件格式不正确']);
//                }
//                $filename = date('YmdHis') . uniqid() . '.' . $ext;
//                // 使用我们新建的upload_company_img本地存储空间（目录）
//                //这里的upload_company_img是配置文件的名称
//                $bool = Storage::disk('local')->put($filename, file_get_contents($realPath));
//                if($bool){
//                    /*$this->_result['code']=200;
//                    $this->_result['message']='成功';*/
//                    $url='/upload/'.date('Ymd',time()).'/'.$filename;
//                    $arr[$k] = $url;
//                    /*$path='/static/study/images/company/'.date('Ym',time()).'/'.$filename;
//                    $this->_result['data']=array('url'=>$url,'path'=>$path);
//                    echo json_encode($this->_result);*/
//                }else{
//                    return response()->json(['code' => 2, 'msg' => '操作失败请联系管理员']);
//                }
//            }
            //dump($arr);
            $arr['id_pic'] = base64_image_content($request->id_pic, 'upload');
            $arr['face_pic'] = base64_image_content($request->face_pic, 'upload');
            //dd($arr);
            //return response()->json(['code' => 0, 'msg' =>$arr]);
            $arr['created_at'] = date('Y-m-d H:i:s');
            $arr['updated_at'] = date('Y-m-d H:i:s');
            $arr['phone'] = GrabUsersPre::where('id', $request->user_id)->value('phone');
            $request = $request->toArray();
            unset($request['/userInfo']);
            $id = GrabUsers::insertGetId($arr + $request);
            if ($id) {
                return response()->json(['code' => 0, 'msg' => '提交成功！']);
            } else {
                return response()->json(['code' => 2, 'msg' => '操作失败请联系管理员']);
            }
            /*if($arr['id_pic'] && $arr['face_pic']){

            }else{
                return response()->json(['code' => 2, 'msg' => '操作失败请联系管理员']);
            }*/
        }
    }

    /**
     * 卡券列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function cardTicketList(Request $request)
    {
        $list = GrabCardTicket::where('status', 1)->orderBy('face_value')->select('id', 'face_value', 'price', 'rebate', 'original_price')->get();
        return response()->json(['code' => 0, 'msg' => 'success', 'data' => $list]);
    }

    /**
     * 积分商品列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function pointsList(Request $request)
    {
        $list = GrabPoints::where('status', 1)->orderBy('face_value')->select('id', 'face_value', 'price', 'rebate', 'original_price')->get();
        return response()->json(['code' => 0, 'msg' => 'success', 'data' => $list]);
    }

    /**
     * 银行列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function bankList(Request $request)
    {
        \Log::LogWirte('request:' . json_encode($request->input()), 'bankList');
        $keyName = $request->keyName;
        if ($keyName) {
            $list = GrabBankList::where('bankName', 'LIKE', '%' . $keyName . '%')->orwhere('smallName', 'LIKE', '%' . $keyName . '%')->select('smallName', 'bankAbribge')->get();
        } else {
            $list = GrabBankList::select('smallName', 'bankAbribge')->get();
        }
        return response()->json(['code' => 0, 'msg' => 'success', 'data' => $list]);
    }

    /**
     * 发送绑定银行卡验证码
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function bindBankCardCode(Request $request)
    {
        \Log::LogWirte('request:' . json_encode($request->input()), 'bindBankCardCode');
        if (!$request->bank_card || !$request->name || !$request->id_number || !$request->phone || !$request->bankAbribge || !$request->bankName || !$request->user_id) {
            return response()->json(['code' => 1, 'msg' => '参数错误']);
        }
        $jd = new JdController();
        $res = $jd->bindCardSend($request);
        if ($res['code'] == 200) {
            return response()->json(['code' => 0, 'msg' => '验证码发送成功']);
        } else {
            \Log::LogWirte('返回错误:' . $res['msg'], 'bindBankCardCode');
            return response()->json(['code' => 2, 'msg' => $res['msg']]);
        }
    }

    /**
     * 绑定银行卡
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function bindBankCard(Request $request)
    {
        \Log::LogWirte('Request:' . json_encode($request->input()), 'bindBankCard');
        if (!$request->bank_card || !$request->name || !$request->id_number || !$request->phone || !$request->bankAbribge || !$request->bankName || !$request->user_id || !$request->msgCode) {
            return response()->json(['code' => 1, 'msg' => '参数错误']);
        }
        $code = $request->msgCode;
        unset($request['msgCode']);
        $where = [
            'phone' => $request->phone,
            'bank_card' => $request->bank_card,
            'user_id' => $request->user_id
        ];
        $bank = GrabJdbindcard::where($where)->select('id', 'out_trade_no', 'agreement_no')->orderBy('id', 'DESC')->first();
        if (!$bank) {
            return response()->json(['code' => 2, 'msg' => '请确定您的银行卡号']);
        }
        $bank['code'] = $code;
        $jd = new JdController();
        $res = $jd->bindBank($bank);
        if ($res['code'] == 200) {
            return response()->json(['code' => 0, 'msg' => '绑定成功']);
        } else {
            return response()->json(['code' => 3, 'msg' => $res['msg']]);
        }
    }

    /**
     * 银行卡列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function userBankList(Request $request)
    {
        $list = GrabJdbindcard::where('user_id', $request->user_id)->select('id', 'bank_card', 'master', 'bank_name', 'phone')->orderBy('master', 'DESC')->get();
        return response()->json(['code' => 0, 'msg' => 'success', 'data' => $list]);
    }

    /**
     * 更换银行卡主卡
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateMainCard(Request $request)
    {
        \Log::LogWirte('request:' . json_encode($request->input()), 'updateMainCard');
        if (!$request->bank_id) {
            return response()->json(['code' => 1, 'msg' => '参数错误']);
        }
        $master = GrabJdbindcard::where('id', $request->bank_id)->where('user_id', $request->user_id)->value('master');
        if ($master) {
            return response()->json(['code' => 2, 'msg' => '该卡是主卡']);
        }

        DB::beginTransaction();
        try {
            GrabJdbindcard::where('master', 1)->where('user_id', $request->user_id)->update(['master' => 0]);
            GrabJdbindcard::where('id', $request->bank_id)->where('user_id', $request->user_id)->update(['master' => 1]);
            DB::commit();
            return response()->json(['code' => 0, 'msg' => '操作成功']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['code' => 3, 'msg' => '服务器错误']);
        }
    }


    /**
     * 购买卡券
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function cardTicketPay(Request $request)
    {
        $status = GrabUsers::where('user_id', $request->user_id)->orderBy('id', 'DESC')->value('status');
        $user = GrabUsers::where('user_id', $request->user_id)->orderBy('id', 'DESC')->select('user_id', 'phone')->first();
        if ($status != 1) {
            return response()->json(['code' => 1, 'msg' => '请认证后再来充值']);
        }
        if (!$request->cardTicketId || !$request->payType || !$request -> order) {
            return response()->json(['code' => 2, 'msg' => '参数错误！']);
        }
        $cardTicket = GrabCardTicket::select('face_value', 'price', 'rebate', 'original_price', 'id')
            ->where('id', $request->cardTicketId)
            ->where('status', 1)
            ->first();
        if (!$cardTicket) {
            return response()->json(['code' => 3, 'msg' => '商品下架或不存在！']);
        }
        //$order = 'order_' . date('YmdHis') . getRandomStr(8, false);
        if ($request->payType == 1) {
            //银行卡支付
            if (!$request->cardId) {
                return response()->json(['code' => 2, 'msg' => '参数错误！']);
            }

            $jd = new JdController();
            $input = [
                'cardId' => $request->cardId,
                'user_id' => $request->user_id,
                'amount' => 0.01,//$cardTicket -> original_price,
                'order_no' => $request -> order,
                'described' => '卡券支付',
                'type' => 1,
                'product_id' => $cardTicket->id
            ];
            $res = $jd->repayment($input);
            if ($res['code'] == 200) {
                return response()->json(['code' => 0, 'msg' => '支付成功']);
            } else {
                return response()->json(['code' => 3, 'msg' => $res['msg']]);
            }
        } elseif ($request->payType == 2) {
            //支付宝支付
            \Log::LogWirte('支付宝支付开始', 'authorizationPay');
            $ali = new AliController();
            $input = [
                'name' => '帮卡支付',
                'amount' => $cardTicket->original_price,
                'notify' => 'aliCallBack',
                'order' => $request -> order,
                'product_id' => $cardTicket->id
            ];
            GrabAliPay::insert(
                [
                    'user_id' => $request->user_id,
                    'order_no' => $input['order'],
                    'amount' => $cardTicket->original_price,
                    'status' => 0,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'phone' => $user->phone,
                    'mchid' => $user->user_id,
                    'type' => 1,
                    'described' => '购买卡券',
                    'product_id' => $cardTicket->id
                ]
            );
            $ali->aliPay($input);
        }

    }

    /**
    * 获取唯一订单号
    * @param Request $request
     */
    public function getOrder(Request $request)
    {
        $order = 'order_' . date('YmdHis') . getRandomStr(8, false);
        return response()->json(['code' => 0, 'msg' => 'success' , 'order' => $order]);
    }

    /**
     * 购买积分
     * @param Request $request
     */
    public function pointsPay(Request $request)
    {
        $status = GrabUsers::where('user_id', $request->user_id)->orderBy('id', 'DESC')->value('status');
        $user = GrabUsers::where('user_id', $request->user_id)->orderBy('id', 'DESC')->select('user_id', 'phone')->first();
        if ($status != 1) {
            return response()->json(['code' => 1, 'msg' => '请认证后再来充值']);
        }
        if (!$request->cardTicketId || !$request->payType) {
            return response()->json(['code' => 2, 'msg' => '参数错误！']);
        }
        $points = GrabPoints::select('face_value', 'price', 'rebate', 'original_price', 'id')
            ->where('id', $request->cardTicketId)
            ->where('status', 1)
            ->first();
        if (!$points) {
            return response()->json(['code' => 3, 'msg' => '商品下架或不存在！']);
        }

        if ($request->payType == 1) {
            //银行卡支付
            if (!$request->cardId) {
                return response()->json(['code' => 2, 'msg' => '参数错误！']);
            }
            $jd = new JdController();
            $input = [
                'cardId' => $request->cardId,
                'user_id' => $request->user_id,
                'amount' => $points->original_price,
                'order_no' => $request -> order,
                'described' => '积分支付',
                'type' => 2,
                'product_id' => $points->id
            ];
            $res = $jd->repayment($input);
            if ($res['code'] == 200) {
                return response()->json(['code' => 0, 'msg' => '支付成功']);
            } else {
                return response()->json(['code' => 3, 'msg' => $res['msg']]);
            }
        } elseif ($request->payType == 2) {
            //支付宝支付
            \Log::LogWirte('支付宝支付开始', 'authorizationPay');
            $ali = new AliController();
            $input = [
                'name' => '帮分支付',
                'amount' => $points->original_price,
                'notify' => 'aliCallBack',
                'order' => $request -> order
            ];
            GrabAliPay::insert(
                [
                    'user_id' => $request->user_id,
                    'order_no' => $input['order'],
                    'amount' => $points->original_price,
                    'status' => 0,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'phone' => $user->phone,
                    'mchid' => $user->user_id,
                    'type' => 2,
                    'described' => '购买积分',
                    'product_id' => $points->id
                ]
            );
            $ali->aliPay($input);
        }
    }


    public function getOrderResult(Request $request)
    {
        $status = GrabAliPay::where('order_no' , $request -> order_no) -> where('user_id' , $request -> user_id) -> value('status');
        //dd($status);
        if(!isset($status)){
            return response()->json(['code' => 3, 'msg' => '订单不存在']);
        }
        switch ($status){
            case 0:
                return response()->json(['code' => 1, 'msg' => '未支付']);
                break;
            case 1:
                return response()->json(['code' => 200, 'msg' => '支付成功']);
                break;
            case 2:
                return response()->json(['code' => 2, 'msg' => '支付失败']);
                break;
            default:
                return response()->json(['code' => 3, 'msg' => '订单不存在']);
        }
    }

    /**
     * 开启/关闭自动抢单
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function userOpen(Request $request)
    {
        $open = GrabUsers::where('user_id', $request->user_id)->value('is_open');
        $open = $open ? 0 : 1;
        GrabUsers::where('user_id', $request->user_id)->update(['is_open' => $open]);
        return response()->json(['code' => 0, 'msg' => 'success']);
    }

    /**
     * 推广链接
     * @param Request $request
     */
    public function inviteUrl(Request $request)
    {
        $str = GrabUsersPre::where('id', $request->user_id)->value('rand_str');
        //dd($str);
        return response()->json(['code' => 0, 'msg' => 'success', 'data' => $str]);
    }

    /**
     * 账单流水
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function consumeDetail(Request $request)
    {
        if ($request->type == 1) {
            $data = GrabUserCardticketDetail::where('user_id', $request->user_id)->orderbY('id', 'DESC')->paginate(10);
        } else {
            $data = GrabUserPointsDetail::where('user_id', $request->user_id)->orderBy('id', 'DESC')->paginate(10);
        }
        return response()->json(['code' => 0, 'msg' => 'success', 'data' => $data]);
    }


}
