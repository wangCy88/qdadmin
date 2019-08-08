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
                            <th class="sth">渠道</th>
                            <th class="sth">日期</th>
                            <th class="sth">点击数</th>
                            <th class="sth">有效点击数</th>
                            <th class="sth">注册数</th>
                            <th class="sth">状态</th>
                            <th class="sth">更新时间</th>
                            <th class="sth">最后操作时间</th>
                        </tr>
                        @foreach($data as $k => $v)
                            <tr class="str">
                                <td class="std">{{$v -> id}}</td>
                                <td class="std">{{$v->grabUserFrom-> name}}</td>
                                <td class="std">{{$v -> date}}</td>
                                <td class="std">{{$v -> click}}</td>
                                <td class="std">{{$v -> valid_click}}</td>
                                <td class="std">{{$v -> register}}</td>
                                <td class="std">{{$formStatus[$v -> status]}}</td>
                                <td class="std">{{$v->created_at}}</td>
                                <td class="std">{{$v -> updated_at}}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
                <div class="grid">
                    {{ $data  ->links() }}
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
                area: ['50%', '85%'],
                content: 'customDetail?custom_id=' + id
            });
        }
    }

    function exitCustomOrder(id, status) {
        $.get('exitCustomOrder', {'id': id, 'status': status}, function (data) {
            if (data.code == 0) {
                layer.msg(data.msg, {icon: 1}, function () {
                    location.reload();
                })
            } else {
                layer.msg(data.msg, {icon: 2});
            }
        })
    }
</script>
</html>
