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

    <title>内部角色管理</title>
</head>

<body>
<div id="container" class="position">
    <div id="hd"></div>
    <div id="bd">
        <div id="main">
            <div class="search-box ue-clear">
                <div class="search-button" style="margin-left: 20px;">
                    <input class="button" type="button" value="新增角色" onclick="addBaseGroup()"/>
                </div>
            </div>
            <div class="table">
                <div class="grid">
                    <table class="stable">
                        <tr class="str">
                            <th class="sth">编号</th>
                            <th class="sth">角色名称</th>
                            <th class="sth">创建时间</th>
                            <th class="sth">操作</th>
                        </tr>
                        @foreach($data as $v)
                            <tr class="str">
                                <td class="std">{{$v->id}}</td>
                                <td class="std">{{$v->name}}</td>
                                <td class="std">{{$v->created_at}}</td>
                                <td class="std"><a href="javascript:;" onclick="modifyBaseGroup({{$v->id}})">修改</a>&nbsp;&nbsp;<a
                                            href="javascript:;" onclick="deleteBaseGroup({{$v->id}})">删除</a></td>
                            </tr>
                        @endforeach
                    </table>
                </div>
                <div class="grid">
                    {{ $data->links() }}
                </div>
            </div>
            <div id="optable" class="optable" onclick="closeDetails()"></div>
            <div id="gtable1" class="atable">
                <div style="width: 90%;height: 30px;">新增角色</div>
                <div class="utable">
                    <div class='div'><span class='span'>角色名称:</span><input type='text' id='name' value=''/></div>
                    <ul id="utable9"></ul>
                    <div class='div center'>
                        <button type='button' class='span' onclick='addBaseGroupDo()'>新增</button>
                        <button type='button' class='span' onclick='closeDetails()'>关闭</button>
                    </div>
                </div>
            </div>
            <div id="gtable2" class="atable">
                <div style="width: 90%;height: 30px;">修改角色</div>
                <div class="utable">
                    <input type="hidden" id="id" value=""/>
                    <div class='div'><span class='span'>角色名称:</span><input type='text' id='name2' value=''/></div>
                    <ul id="utable10"></ul>
                    <div class='div center'>
                        <button type='button' class='span' onclick='modifyBaseGroupDo()'>修改</button>
                        <button type='button' class='span' onclick='closeDetails()'>关闭</button>
                    </div>
                </div>
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
</html>
