<?php

namespace App\Http\Controllers;

use App\AuthGroups;
use App\AuthMerchants;
use App\AuthRoutes;
use App\AuthUsers;
use App\BaseGroups;
use App\BaseRoutes;
use App\BaseUsers;
use Illuminate\Http\Request;

class SystemController extends Controller
{
    //内部路径管理
    public static function baseRouteControl()
    {
        $routes = BaseRoutes::where(['upid' => 0])->select('id', 'name')->get();
        $data = BaseRoutes::select('id', 'name', 'upid', 'route')->paginate(10);
        return view('System.baseRouteControl', compact('data', 'routes'));
    }

    //新增内部路由
    public static function addBaseRoute(Request $request)
    {
        $route = $request->route;
        $name = $request->name;
        $upid = $request->upid;
        if (!empty($route) && !empty($name)) {
            BaseRoutes::firstOrCreate(['route' => $route], ['name' => $name, 'upid' => $upid]);
        }
        return redirect('baseRouteControl');
    }

    //获取内部路由
    public static function modifyBaseRoute(Request $request)
    {
        $id = $request->id;
        if (empty($id)) {
            return response()->json(['code' => 1, 'msg' => '缺少参数']);
        }
        $data = BaseRoutes::select('id', 'name', 'route', 'upid')->find($id);
        if (empty($data['id'])) {
            return response()->json(['code' => 1, 'msg' => '参数错误']);
        }
        $routes = BaseRoutes::where(['upid' => 0])->select('id', 'name')->get();
        return response()->json(['code' => 0, 'data' => $data, 'routes' => $routes]);
    }

    //修改内部路由
    public static function modifyBaseRouteDo(Request $request)
    {
        $id = $request->id;
        $name = $request->name;
        $route = $request->route;
        $upid = $request->upid;
        if (empty($id) || empty($name) || empty($route)) {
            return response()->json(['code' => 1, 'msg' => '缺少参数']);
        }
        $updateData = ['name' => $name, 'route' => $route, 'upid' => $upid, 'updated_at' => date('Y-m-d H:i:s')];
        $result = BaseRoutes::where(['id' => $id])->update($updateData);
        if (!$result) {
            return response()->json(['code' => 1, 'msg' => '修改失败']);
        }
        return response()->json(['code' => 0, 'msg' => '修改成功']);
    }

    //删除内部路由
    public static function deleteBaseRoute(Request $request)
    {
        $id = $request->id;
        if (empty($id)) {
            return response()->json(['code' => 1, 'msg' => '缺少参数']);
        }
        $result = BaseRoutes::where(['id' => $id])->delete();
        if (!$result) {
            return response()->json(['code' => 1, 'msg' => '删除失败']);
        }
        return response()->json(['code' => 0, 'msg' => '删除成功']);
    }

    //内部角色管理
    public static function baseGroupControl()
    {
        $data = BaseGroups::select('id', 'name', 'created_at')->paginate(10);
        return view('System.baseGroupControl', compact('data'));
    }

    //获取内部路由列表
    public static function addBaseGroup()
    {
        $routes = BaseRoutes::select('id', 'name', 'upid')->get();
        $data = [];
        $info = [];
        foreach ($routes as $k => $v) {
            if ($v->upid === 0) {
                $data[$v->name] = [];
                $info[$v->id] = $v->name;
            }
        }
        foreach ($routes as $k => $v) {
            if ($v->upid !== 0) {
                foreach ($info as $kk => $vv) {
                    if ($v->upid === $kk) {
                        $data[$vv][$v->name] = $v->id;
                        break;
                    }
                }
            }
        }
        return response()->json(['code' => 0, 'data' => $data]);
    }

    //新增内部角色
    public static function addBaseGroupDo(Request $request)
    {
        $name = $request->name;
        $routes = $request->routes;
        if (empty($name) || empty($routes)) {
            return response()->json(['code' => 1, 'msg' => '缺少参数']);
        }
        $upid = BaseRoutes::whereIn('id', $routes)->select('upid')->distinct()->get();
        foreach ($upid as $v) {
            $routes[] = "$v->upid";
        }
        sort($routes);
        $routes = implode(',', $routes);
        $insertData = ['name' => $name, 'routes' => $routes, 'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')];
        $result = BaseGroups::insert($insertData);
        if (!$result) {
            return response()->json(['code' => 1, 'msg' => '新增失败']);
        }
        return response()->json(['code' => 0, 'msg' => '新增成功']);
    }

    //获取内部角色
    public static function modifyBaseGroup(Request $request)
    {
        $id = $request->id;
        if (empty($id)) {
            return response()->json(['code' => 1, 'msg' => '缺少参数']);
        }
        //获取当前角色路由
        $self = BaseGroups::select('name', 'routes')->find($id);
        $name = $self->name;
        $route = explode(',', $self->routes);
        //获取路由列表
        $routes = BaseRoutes::select('id', 'name', 'upid')->get();
        $data = [];
        $info = [];
        foreach ($routes as $k => $v) {
            if ($v->upid === 0) {
                $data[$v->name] = [];
                $info[$v->id] = $v->name;
            }
        }
        foreach ($routes as $k => $v) {
            if ($v->upid !== 0) {
                foreach ($info as $kk => $vv) {
                    if ($v->upid === $kk) {
                        $data[$vv][$v->name] = $v->id;
                        break;
                    }
                }
            }
        }
        return response()->json(['code' => 0, 'data' => $data, 'route' => $route, 'name' => $name]);
    }

    //修改内部角色
    public static function modifyBaseGroupDo(Request $request)
    {
        $id = $request->id;
        $name = $request->name;
        $routes = $request->routes;
        if (empty($id) || empty($name) || empty($routes)) {
            return response()->json(['code' => 1, 'msg' => '缺少参数']);
        }
        $upid = BaseRoutes::whereIn('id', $routes)->select('upid')->distinct()->get();
        foreach ($upid as $v) {
            $routes[] = "$v->upid";
        }
        sort($routes);
        $routes = implode(',', $routes);
        $updateData = ['name' => $name, 'routes' => $routes, 'updated_at' => date('Y-m-d H:i:s')];
        $result = BaseGroups::where(['id' => $id])->update($updateData);
        if (!$result) {
            return response()->json(['code' => 1, 'msg' => '修改失败']);
        }
        return response()->json(['code' => 0, 'msg' => '修改成功']);
    }

    //删除内部角色
    public static function deleteBaseGroup(Request $request)
    {
        $id = $request->id;
        if (empty($id)) {
            return response()->json(['code' => 1, 'msg' => '缺少参数']);
        }
        $result = BaseGroups::where(['id' => $id])->delete();
        if (!$result) {
            return response()->json(['code' => 1, 'msg' => '删除失败']);
        }
        return response()->json(['code' => 0, 'msg' => '删除成功']);
    }

    //内部用户管理
    public static function baseUserControl()
    {
        $data = BaseUsers::select('id', 'account', 'name', 'phone', 'sex', 'status', 'gid', 'created_at')
            ->with(['baseGroups' => function ($query) {
                $query->select('id', 'name');
            }])
            ->paginate(10);
        $groups = BaseGroups::select('id', 'name')->get();
        $sexList = config('config.sexList');
        $jobStatusList = config('config.jobStatusList');
        return view('System.baseUserControl', compact('data', 'groups', 'sexList', 'jobStatusList'));
    }

    //新增内部用户
    public static function addBaseUser(Request $request)
    {
        $name = $request->name;
        $account = $request->account;
        $password = $request->password;
        $phone = $request->phone;
        $sex = empty($request->sex) ? 0 : $request->sex;
        $status = empty($request->status) ? 0 : $request->status;
        $gid = $request->gid;
        if (!empty($name) && !empty($account) && !empty($password) && !empty($gid) && !empty($phone)) {
            $password = md5($password);
            $insertData = ['name' => $name, 'account' => $account, 'password' => $password, 'phone' => $phone,
                'sex' => $sex, 'status' => $status, 'gid' => $gid, 'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')];
            BaseUsers::insert($insertData);
        }
        return redirect('baseUserControl');
    }

    //获取内部用户
    public static function modifyBaseUser(Request $request)
    {
        $id = $request->id;
        if (empty($id)) {
            return response()->json(['code' => 1, 'msg' => '缺少参数']);
        }
        $data = BaseUsers::select('name', 'phone', 'sex', 'status', 'gid')->find($id);
        $groups = BaseGroups::select('id', 'name')->get();
        $sexList = config('config.sexList');
        $jobStatusList = config('config.jobStatusList');
        return response()->json(['code' => 0, 'data' => $data, 'groups' => $groups, 'sexList' => $sexList,
            'jobStatusList' => $jobStatusList]);
    }

    //修改内部用户
    public static function modifyBaseUserDo(Request $request)
    {
        $id = $request->id;
        $name = $request->name;
        $phone = $request->phone;
        $sex = empty($request->sex) ? 0 : $request->sex;
        $status = empty($request->status) ? 0 : $request->status;
        $gid = $request->gid;
        if (empty($id) || empty($name) || empty($phone) || empty($gid)) {
            return response()->json(['code' => 1, 'msg' => '缺少参数']);
        }
        $updateData = ['name' => $name, 'phone' => $phone, 'sex' => $sex, 'status' => $status, 'gid' => $gid,
            'updated_at' => date('Y-m-d H:i:s')];
        $result = BaseUsers::where(['id' => $id])->update($updateData);
        if (!$result) {
            return response()->json(['code' => 1, 'msg' => '修改失败']);
        }
        return response()->json(['code' => 0, 'msg' => '修改成功']);
    }

    //修改内部密码
    public static function modifyBaseUserPwdDo(Request $request)
    {
        $id = $request->id;
        $password = $request->password;
        if (empty($id) || empty($password)) {
            return response()->json(['code' => 1, 'msg' => '缺少参数']);
        }
        $password = md5($password);
        $updateData = ['password' => $password, 'updated_at' => date('Y-m-d H:i:s', time())];
        $result = BaseUsers::where(['id' => $id])->update($updateData);
        if (!$result) {
            return response()->json(['code' => 1, 'msg' => '修改失败']);
        }
        return response()->json(['code' => 0, 'msg' => '修改成功']);
    }

    //删除内部用户
    public static function deleteBaseUser(Request $request)
    {
        $id = $request->id;
        if (empty($id)) {
            return response()->json(['code' => 1, 'msg' => '缺少参数']);
        }
        $result = BaseUsers::where(['id' => $id])->delete();
        if (!$result) {
            return response()->json(['code' => 1, 'msg' => '删除失败']);
        }
        return response()->json(['code' => 0, 'msg' => '删除成功']);
    }

}
