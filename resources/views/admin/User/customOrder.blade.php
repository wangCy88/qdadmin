<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=emulateIE7" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" type="text/css" href="{{asset('css/style.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('css/WdatePicker.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('css/skin_/table.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('css/skin_/index.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('css/jquery.grid.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('css/mystyle.css')}}" />

    <title>内部用户管理</title>
</head>

<body>
    <div id="container" class="position">
        <div id="hd"></div>
        <div id="bd">
            <div id="main">
                <form action="{{url('customOrder')}}" method="post">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">

                    <div class="search-box ue-clear">
                        {{--<div class="search-area">
                        <div class="kv-item ue-clear">
                            <label>姓名:</label>
                            <div class="kv-item-content">
                                <input type="text" style="width: 100px;height: 28px;" name="name" value=""/>
                            </div>
                        </div>
                    </div>
                    <div class="search-area">
                        <div class="kv-item ue-clear">
                            <label>账号:</label>
                            <div class="kv-item-content">
                                <input type="text" style="width: 100px;height: 28px;" name="account" value=""/>
                            </div>
                        </div>
                    </div>--}}
                        <div class="search-area">
                            <div class="kv-item ue-clear">
                                <label>手机号:</label>
                                <div class="kv-item-content">
                                    <input type="text" style="width: 100px;height: 28px;" name="phone" value="" />
                                </div>
                            </div>
                        </div>
                        <div class="search-area">
                            <div class="kv-item ue-clear">
                                <label>反馈时间:</label>
                                <div class="kv-item-content">
                                    <input type="date" style="width: 150px;height: 25px;" name="date1" value="{{$date1}}" />
                                    <span>~</span>
                                    <input type="date" style="width: 150px;height: 25px;" name="date2" value="{{$date2}}" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="search-box ue-clear">
                        <div class="search-area">
                            <div class="kv-item ue-clear">
                                <label>状态:</label>
                                <div class="kv-item-content">
                                    <select style="width: 120px;" name="status">
                                        <option value="">请选择</option>
                                        @foreach($borrowStatus as $k => $v)
                                        <option value="{{$k}}" @if($status==$k) selected @endif>{{$v}}</option>
                                        @endforeach
                                    </select>
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
                                <th class="sth">订单号</th>
                                <th class="sth">信贷经理</th>
                                <th class="sth">申请人</th>
                                <th class="sth">申请金额</th>
                                <th class="sth">状态</th>
                                <th class="sth">退单理由</th>
                                <th class="sth">添加时间</th>
                                <th class="sth">最后操作时间</th>
                                <th class="sth">操作</th>
                            </tr>
                            @foreach($data as $k => $v)
                            <tr class="str">
                                <td class="std">{{$v -> id}}</td>
                                <td class="std">{{$v->order_no}}</td>
                                <td class="std">@if($v -> grabUsers){{$v -> grabUsers -> name}}-{{$v -> grabUsers -> phone}}@endif</td>
                                <td class="std">@if($v -> grabCustom){{$v -> grabCustom -> name}}-{{$v -> grabCustom -> phone}}@endif</td>
                                <td class="std">{{$v -> withdraw_amount}}</td>
                                <td class="std">{{$borrowStatus[$v -> status]}}</td>
                                <td class="std">{{$v -> account}}</td>
                                <td class="std">{{$v->created_at}}</td>
                                <td class="std">{{$v -> updated_at}}</td>
                                <td class="std">
                                    @if($v -> status == 1)
                                    <a href="javascript:;" onclick="exitCustomOrder('{{$v->id}}' , 2)">同意退单</a>
                                    &nbsp;&nbsp;<br />
                                    <a href="javascript:;" onclick="exitCustomOrder('{{$v->id}}' , 0)">拒绝退单</a>
                                    &nbsp;&nbsp;
                                    @endif
                            </tr>
                            @endforeach
                        </table>
                    </div>
                    <div class="grid">
                        {{ $data -> appends([ 'date1' => $date1 , 'date2' => $date2 , 'status' => $status]) ->links() }}
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
        $.get('exitCustomOrder', {
            'id': id,
            'status': status
        }, function(data) {
            if (data.code == 0) {
                layer.msg(data.msg, {
                    icon: 1
                }, function() {
                    location.reload();
                })
            } else {
                layer.msg(data.msg, {
                    icon: 2
                });
            }
        })
    }
</script>

</html>