<?php

namespace App\Http\Controllers\admin;

use App\GrabSendmsg;
use App\GrabUserFormClick;
use App\GrabUsers;
use App\GrabUsersPre;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\GrabUserFrom;

class UserController extends Controller
{
    /**
     * 信贷经理列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function userList(Request $request)
    {
        $data = GrabUsersPre::with(['grabUsersWallet' => function ($q) {
            $q->select();
        }])
            ->select('id', 'phone', 'updated_at', 'auth_status')
            ->orderBy('id', 'desc')
            //-> toSql();
            ->paginate(2);
        $authStatusList = config('config.authStatusList');
        //dd($data -> toArray());
        return view('admin.User.userList', compact('data', 'authStatusList'));
    }

    /**
     * 获取用户详细资料
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUsers(Request $request)
    {
        if (!$request->id) {
            //return response()->json(['code' => 1, 'msg' => 'error imgcode']);
            exit('error imgcode');
        }
        $info = GrabUsers::where('user_id', $request->id)->first();
        //$info2 = GrabUsersPre::where('id' , $request -> id) -> first();
        if (!$info) {
            //return response()->json(['code' => 2, 'msg' => '未认证']);
            /*echo '用户未认证';die;*/
            exit('用户未认证!');
        }
        //return response()->json(['code' => 0, 'msg' => 'success' , 'data' => $info]);
        return view('admin.User.getUsers', compact('info'));
    }

    /**
     * 更新用户状态
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateUserStatus(Request $request)
    {
        $status = $request->status;
        try {
            DB::beginTransaction();
            $res = GrabUsers::where('user_id', $request->id)->update(['status' => $status]);
            $res2 = GrabUsersPre::where('id', $request->id)->update(['auth_status' => $status]);
            if ($res && $res2) {
                DB::commit();  //提交
                return response()->json(['code' => 0, 'msg' => '认证成功']);
            } else {
                DB::rollback();  //回滚
                return response()->json(['code' => 1, 'msg' => '操作失败']);
            }
        } catch (\Exception $e) {
            //echo $e -> getMessage();
            return response()->json(['code' => 2, 'msg' => $e->getMessage()]);
        }
    }


    /**
     * 发送消息
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function sendNews(Request $request)
    {
        if ($request->isMethod('post')) {
            //dd($request -> input());
            GrabSendmsg::insert($request->input());
            return response()->json(['code' => 0, 'msg' => '发送成功']);
        } else {
            $id = $request->id;
            return view('admin.User.sendNews', compact('id'));
        }
    }

    /**
     * 经理渠道列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function userFormList(Request $request)
    {
        $data = GrabUserFrom::orderBy('id', 'DESC')->paginate(10);
        $formStatus = config('config.formStatus');
        return view('admin.User.userFormList', compact('data', 'formStatus'));
    }

    /**
     * 添加信贷渠道
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function userFromAdd(Request $request)
    {
        if ($request->isMethod('post')) {
            GrabUserFrom::insert(
                [
                    'name' => trim($request->name),
                    'username' => trim($request->username),
                    'password' => md5(trim($request->password)),
                    'code' => strtoupper(getRandomStr(8, false)),
                    'status' => 1,
                    'uv_ratio' => $request->uv_ratio,
                    'reg_ratio' => $request->reg_ratio
                ]
            );
            return response()->json(['code' => 0, 'msg' => '添加成功']);
        } else {
            return view('admin.User.userFormAdd');
        }
    }

    /**
     * 编辑渠道
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function userFormEdit(Request $request)
    {
        if ($request->isMethod('post')) {
            //dd($request -> input());
            $data = $request->input();
            if (!$request->password) {
                unset($data['password']);
            } else {
                $data['password'] = md5(trim($data['password']));
            }
            GrabUserFrom::where('id', $request->id)->update($data);
            return response()->json(['code' => 0, 'msg' => '操作成功']);
        } else {
            $info = GrabUserFrom::where('id', $request->id)->first();
            return view('admin.User.userFormEdit', compact('info'));
        }
    }

    /**
     * 开启/关闭
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function userFormStatus(Request $request)
    {
        $status = GrabUserFrom::where('id', $request->id)->value('status');
        $status = $status == 1 ? 0 : 1;
        //dd($request -> input());
        GrabUserFrom::where('id', $request->id)->update(['status' => $status]);
        return response()->json(['code' => 0, 'msg' => '操作成功']);
    }


    /**
     * 用户渠道监控
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function userFormSee(Request $request)
    {
        $data = GrabUserFormClick::orderBy('id', 'DESC')->paginate(2);
        return view('admin.User.userFormSee', compact('data'));
    }


}
