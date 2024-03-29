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
    <div id="hd" style="margin-top: 20px">
        <div class="search-button" style="margin-left: 20px;">
            <input class="button" type="button" onclick="addCardTicket()" value="添加"/>
        </div>
    </div>
    <div id="bd">

        <div id="main">

            <div class="table">
                <div class="grid">
                    <table class="stable">
                        <tr class="str">
                            <th class="sth">编号</th>
                            <th class="sth">名称</th>
                            <th class="sth">用户名</th>
                            <th class="sth">邀请码</th>
                            <th class="sth">UV转化率</th>
                            <th class="sth">注册转化率</th>
                            <th class="sth">状态</th>
                            <th class="sth">添加时间</th>
                            <th class="sth">操作时间</th>
                            <th class="sth">操作</th>
                        </tr>
                        @foreach($data as $k => $v)
                            <tr class="str">
                                <td class="std">{{$k+1}}</td>
                                <td class="std">{{$v->name}}</td>
                                <td class="std">{{$v -> username}}</td>
                                <td class="std">{{$v -> code}}</td>
                                <td class="std">{{$v -> uv_ratio}}%</td>
                                <td class="std">{{$v -> reg_ratio}}%</td>
                                <td class="std">{{$formStatus[$v -> status]}}</td>
                                <td class="std">{{$v->created_at}}</td>
                                <td class="std">{{$v->updated_at}}</td>
                                <td class="std">
<a href="javascript:;" onclick="updateStatus({{$v->id}})">开启/关闭</a>
<a href="javascript:;" onclick="updateCradTicket({{$v->id}})">编辑</a>
                            </tr>
                        @endforeach
                    </table>
                </div>
                <div class="grid">
                    {{ $data ->links() }}
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
    function addCardTicket() {
        layer.open({
            type: 2,
            title: '添加渠道',
            shadeClose: true,
            shade: false,
            maxmin: true, //开启最大化最小化按钮
            area: ['50%', '50%'],
            content: 'customFormAdd'
        });
    }

    function updateStatus(id) {
        $.get('customFormStatus', {'id': id}, function (data) {
            if (data.code == 0) {
                layer.msg(data.msg, {icon: 1}, function () {
                    location.reload();
                })
            } else {
                layer.msg(data.msg, {icon: 2});
            }
        })
    }

    function updateCradTicket(id) {
        layer.open({
            type: 2,
            title: '编辑商品',
            shadeClose: true,
            shade: false,
            maxmin: true, //开启最大化最小化按钮
            area: ['50%', '50%'],
            content: 'customFormEdit?id=' + id
        });
    }
</script>
</html>
