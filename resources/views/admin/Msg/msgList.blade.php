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
            <div class="table">
                <div class="grid">
                    <table class="stable">
                        <tr class="str">
                            <th class="sth">编号</th>
                            <th class="sth">手机号</th>
                            <th class="sth">类型</th>
                            <th class="sth">内容</th>
                            <th class="sth">回复</th>
                            <th class="sth">状态</th>
                            <th class="sth">操作时间</th>
                            <th class="sth">操作</th>
                        </tr>
                        @foreach($data as $k => $v)
                            <tr class="str">
                                <td class="std">{{$k+1}}</td>
                                <td class="std">@if($v -> grabUsersPre){{$v -> grabUsersPre->phone}}@endif</td>
                                <td class="std">{{$v -> grabFeedbackType -> type_name}}</td>
                                <td class="std">{{$v -> remark}}</td>
                                <td class="std">{{$v -> answer}}</td>
                                <td class="">{{$msgStatus[$v -> status]}}</td>
                                <td class="std">{{$v->updated_at}}</td>
                                <td class="std">
                                    <a href="javascript:;" onclick="getUsers({{$v->id}})">查看</a>&nbsp;&nbsp;
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
                title: '回复',
                shadeClose: true,
                shade: false,
                maxmin: true, //开启最大化最小化按钮
                area: ['15%', '30%'],
                content: 'sendMsgToUser?id=' + id
            });
        }
    }
</script>
</html>
