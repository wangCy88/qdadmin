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

< div id="hd" >

< div class="search-button" style="margin-left: 20px; margin-top:20px">
<input class="button" type="button" onclick="addCardTicket()" value ="添加"/>

</ div >
< div class="search-button" style="margin-left: 20px; margin-top:20px">
<input type="text" id='price' name ='price' value ='{{$price}}' high='40px'>
<input class="button" type="button" onclick="editPrice()" value ="修改单价"/>
        </div>
    </div>
    <div id="bd">

        <div id="main">

            <div class="table">
                <div class="grid">
                    <table class="stable">
                        <tr class="str">
                            <th class="sth">编号</th>
                            <th class="sth">面值</th>
                            <th class="sth">价格</th>
                            <th class="sth">折扣</th>
                            <th class="sth">折后价</th>
                            <th class="sth">状态</th>
                            <th class="sth">添加时间</th>
                            <th class="sth">操作</th>
                        </tr>
                        @foreach($data as $k => $v)
                            <tr class="str">
                                <td class="std">{{$k+1}}</td>
                                <td class="std">{{$v->face_value}}</td>
<td class="std">{{$v -> price}}</td>
                                <td class="std">{{$v -> rebate}}</td>
                                <td class="std">{{$v -> original_price}}</td>
                                <td class="std">{{$cardTicketStatus[$v -> status]}}</td>
                                <td class="std">{{$v->created_at}}</td>
                                {{--<td class="std"></td>
                                <td class="std">--}}{{--{{$jobStatusList[$v->status]}}--}}{{--</td>
                                <td class="std">--}}{{--{{$v->baseGroups->name}}--}}{{--</td>--}}
                                <td class="std">
                                    <a href="javascript:;" onclick="updateStatus({{$v->id}})">上/下架</a>&nbsp;&nbsp;
                                    <a href="javascript:;" onclick="updateCradTicket({{$v->id}})">编辑</a>&nbsp;&nbsp;
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
            title: '添加商品',
            shadeClose: true,
            shade: false,
            maxmin: true, //开启最大化最小化按钮
            area: ['50%', '50%'],
            content: 'addCardTicket'
})
    }

    function updateStatus(id) {
        $.get('updateStatus', {'id': id}, function (data) {
            if (data.code == 0) {
                layer.msg(data.msg, {icon: 1}, function () {
                    location.reload();
                })
            } else {
                layer.msg(data.msg, {icon: 2});
            }
        })
    }

function updateCradTicket(id){
        layer.open({
            type: 2,
            title: '编辑商品',
            shadeClose: true,
            shade: false,
            maxmin: true, //开启最大化最小化按钮
            area: ['50%', '50%'],
            content: 'updateCardTicket?id=' + id
})
}

function editPrice(){
var price = $('#price').val()
layer.confirm('您确定要修改卡券单价为'+ price +'？', {
btn:['确定', '取消'] //按钮
}, function (){
//layer.msg('的确很重要', {icon:1});
$.get('updateCardPrice', {'price' :price}, function ( data ){
if ( data.code == 0){
layer.msg( data.msg, {icon:1}, function (){
location.reload();
});
}
})
})
}
</script>
</html>
