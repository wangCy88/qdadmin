<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=emulateIE7"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('css/style.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('css/WdatePicker.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('css/skin_/table.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('css/skin_/index.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('css/jquery.grid.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('css/mystyle.css')}}"/>

    <title>内部用户管理</title>
</head>

<body>
<div id="container" class="position">
    <div id="hd"></div>
    <div id="bd">
        <div id="main">
            <form action="{{url('adminUserList')}}" method="post">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <div class="search-box ue-clear">

                    <div class="search-area">
                        <div class="kv-item ue-clear">
                            <label>手机号:</label>
                            <div class="kv-item-content">
                                <input type="text" style="width: 100px;height: 28px;" name="phone" value="{{$phone}}"/>
                            </div>
                        </div>
                    </div>

                    <div class="search-area">
                            <div class="kv-item ue-clear">
                                <label>时间:</label>
                                <div class="kv-item-content">
                                    <input type="date" style="width: 150px;height: 25px;" name="date1" value="{{$date1}}" />
                                    <span>~</span>
                                    <input type="date" style="width: 150px;height: 25px;" name="date2" value="{{$date2}}" />
                                </div>
                            </div>
                        </div>
            
                    <div class="search-button" style="margin-left: 20px;">
                        <input class="button" type="submit" value="查找" />
                    </div>
                </div>
            </form>
            <div class="table">
                <div class="grid">
                    <table class="stable">
                        <tr class="str">
                            <th class="sth">ID</th>
                            <th class="sth">手机号</th>
                            <th class="sth">状态</th>
                            <th class="sth">最后登陆时间</th>
                            <th class="sth">卡券余额</th>
                            <th class="sth">积分余额余额</th>
                            <th class="sth">操作</th>
                        </tr>
                        @foreach($data as $k => $v)
                            <tr class="str">
                                <td class="std">{{$v->id}}</td>
                                <td class="std">{{$v->phone}}</td>
                                <td class="">{{$authStatusList[$v -> auth_status]}}</td>
                                <td class="std">{{$v->updated_at}}</td>
                                {{--<td class="std"></td>
                                <td class="std">--}}{{--{{$jobStatusList[$v->status]}}--}}{{--</td>
                                <td class="std">--}}{{--{{$v->baseGroups->name}}--}}{{--</td>--}}
                                <td class="std">@if($v -> grabUsersWallet) {{$v -> grabUsersWallet -> card_ticket}} @endif</td>
                                <td class="std">@if($v -> grabUsersWallet) {{$v -> grabUsersWallet -> points}} @endif</td>
                                <td class="std">
                                    <a href="javascript:;" onclick="getUsers({{$v->id}})">查看</a>&nbsp;&nbsp;
                                    <a href="javascript:;" onclick="sendNews({{$v -> id}})">发送消息</a>
                                {{--<a href="javascript:;" onclick="modifyBaseUserPwd({{$v->id}})">拒绝</a>&nbsp;&nbsp;--}}
                                {{--<a href="javascript:;" onclick="deleteBaseUser({{$v->id}})">删除</a></td>--}}
                            </tr>
                        @endforeach
                    </table>
                </div>
                <div class="grid">
                    {{ $data->links() }}
                </div>
            </div>
            <div id="optable" class="optable" onclick="closeDetails()"></div>
            <div id="mutable" class="atable">
                <div style="width: 90%;height: 30px;">修改用户</div>
                <div class="utable" id="utable11"></div>
            </div>
            <div id="uptable" class="atable">
                <div style="width: 90%;height: 30px;">修改密码</div>
                <div class="utable" id="utable12"></div>
            </div>
        </div>
    </div>
</div>
</body>
<script type="text/javascript" src="{{asset('js/jquery.js')}}"></script>
<script type="text/javascript" src="{{asset('js/global.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.select.js')}}"></script>
<script type="text/javascript" src="{{asset('js/core.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.pagination.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.grid.js')}}"></script>
<script type="text/javascript" src="{{asset('js/WdatePicker.js')}}"></script>
<script type="text/javascript" src="{{asset('js/myjs.js')}}"></script>
<script type="text/javascript" src="{{asset('js/layui/layui.all.js')}}"></script>
<script type="text/javascript">
    function getUsers(id) {
        if (id.length != 0) {
            layer.open({
                type: 2,
                title: '查看经理',
                shadeClose: true,
                shade: false,
                maxmin: true, //开启最大化最小化按钮
                area: ['893px', '600px'],
                content: 'getUsers?id=' + id
            });
        }
    }

    function sendNews(id) {
        layer.open({
            type: 2,
            title: '发送消息',
            shadeClose: true,
            shade: false,
            maxmin: true, //开启最大化最小化按钮
            area: ['25%', '30%'],
            content: 'sendNews?id=' + id
        });
    }
</script>
</html>
