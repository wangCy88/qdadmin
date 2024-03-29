<?php

namespace App\Http\Controllers;

use App\BaseGroups;
use App\BaseRoutes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class IndexController extends Controller
{
    //页面顶部显示
    public static function main()
    {
        $gid = session('user_info')->gid;
        $name = session('user_info')->name;
        $data = BaseRoutes::where(['upid' => 0]);
        if ($gid !== 0) {
            $routes = BaseGroups::select('routes')->find($gid)->routes;
            $routes = explode(',', $routes);
            $data = $data->whereIn('id', $routes);
        }
        $data = $data->select('id', 'name')->get();
        return view('Index.main', compact('data', 'name'));
    }

    //切换模块
    public static function changeModel(Request $request)
    {
        $id = $request->id;
        if (!empty($id)) {
            $model = BaseRoutes::select('name')->find($id)->name;
            $gid = session('user_info')->gid;
            $data = BaseRoutes::where(['upid' => $id]);
            if ($gid !== 0) {
                $routes = BaseGroups::select('routes')->find($gid)->routes;
                $routes = explode(',', $routes);
                $data = $data->whereIn('id', $routes);
            }
            $data = $data->select('id', 'name', 'route')->get();
            if (empty($data)) {
                return response()->json(['code' => 1, 'msg' => '无权限访问']);
            }
            return response()->json(['code' => 0, 'data' => $data, 'model' => $model, 'url' => '/']);
        } else {
            return response()->json(['code' => 1, 'msg' => '缺少参数']);
        }
    }

    /*//页面底部显示
    public static function nav(){
        $gid = session('user_info')->gid;
        $routes = AuthGroups::select('routes')->find($gid)->routes;
        $routes = explode(',', $routes);
        $data = AuthRoutes::where(['upid' => 0])->whereIn('id' ,$routes)->select('id', 'name')->get();
        return view('Index.nav', compact('data'));
    }*/

    //首页
    public static function index()
    {
        return view('Index.index');
    }

    //登陆
    public static function login()
    {
        if ($_POST) {
            $username = Input::get('username');
            $password = Input::get('password');
            $code = Input::get('code');
            if (strtolower($code) != session('authcode')) {
                return response()->json(array('code' => 1, 'msg' => '验证码错误'));
            }
            $data = DB::table('base_users')->where(['account' => $username, 'password' => md5($password)])->first();
            if (!$data) {
                return response()->json(array('code' => 2, 'msg' => '用户名或密码错误'));
            }
            session(['user_info' => $data]);
            return response()->json(array('code' => 0));
        }
        return view('Index.login');
    }

    //登出
    public static function logout()
    {
        session(['user_info' => null]);
        return response()->json(array('code' => 0));
        //return redirect('login');
    }

    //图片验证码
    public static function createImgLoginCode(Request $request)
    {
        $image = imagecreatetruecolor(100, 30);//imagecreatetruecolor函数建一个真彩色图像
        //生成彩色像素
        $bgcolor = imagecolorallocate($image, 255, 255, 255);//白色背景     imagecolorallocate函数为一幅图像分配颜色
        $textcolor = imagecolorallocate($image, 0, 0, 255);//蓝色文本
        //填充函数，xy确定坐标，color颜色执行区域填充颜色
        imagefill($image, 0, 0, $bgcolor);
        $captch_code = "";//初始空值

        //该循环,循环取数
        for ($i = 0; $i < 4; $i++) {
            $fontsize = 6;
            $x = ($i * 25) + rand(5, 10);
            $y = rand(5, 10);//位置随机
            //  $fontcontent=$i>2?chr(rand(97,122)):chr(rand(65,90));//是小写，否则是大写
            $data = 'abcdefghijkmnpqrstuvwxyz3456789';
            $fontcontent = substr($data, rand(0, strlen($data) - 1), 1);//strlen仅仅是一个计数器的工作  含数字和字母的验证码
            //可以理解为数组长度0到30

            $fontcolor = imagecolorallocate($image, rand(0, 100), rand(0, 100), rand(0, 100));//随机的rgb()值可以自己定

            imagestring($image, $fontsize, $x, $y, $fontcontent, $fontcolor); //水平地画一行字符串
            $captch_code .= $fontcontent;
        }
        session(['authcode' => $captch_code]);//将变量保存再session的authcode变量中
        //该循环,循环画背景干扰的点
        for ($m = 0; $m <= 600; $m++) {

            $x2 = rand(1, 99);
            $y2 = rand(1, 99);
            $pointcolor = imagecolorallocate($image, rand(0, 255), rand(0, 255), rand(0, 255));
            imagesetpixel($image, $x2, $y2, $pointcolor);// 水平地画一串像素点
        }

        //该循环,循环画干扰直线
        for ($i = 0; $i <= 10; $i++) {
            $x1 = rand(0, 99);
            $y1 = rand(0, 99);
            $x2 = rand(0, 99);
            $y2 = rand(0, 99);
            $linecolor = imagecolorallocate($image, rand(0, 255), rand(0, 255), rand(0, 255));
            imageline($image, $x1, $y1, $x2, $y2, $linecolor);//画一条线段

        }
        header('content-type:image/png');
        imagepng($image);
    }
}
