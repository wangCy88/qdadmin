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

    <title>内部路径管理</title>
</head>

<body>
<div id="container" class="position">
    <div id="hd"></div>
    <div id="bd">
        <div id="main">
            <form action="{{url('addBaseRoute')}}" method="post">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <div class="search-box ue-clear">
                    <div class="search-area">
                        <div class="kv-item ue-clear">
                            <label>路由名称:</label>
                            <div class="kv-item-content">
                                <input type="text" style="width: 100px;height: 28px;" name="name" value=""/>
                            </div>
                        </div>
                    </div>
                    <div class="search-area">
                        <div class="kv-item ue-clear">
                            <label>路由路径:</label>
                            <div class="kv-item-content">
                                <input type="text" style="width: 100px;height: 28px;" name="route" value=""/>
                            </div>
                        </div>
                    </div>
                    <div class="search-area">
                        <div class="kv-item ue-clear">
                            <label>上级路由:</label>
                            <div class="kv-item-content">
                                <select style="width: 120px;" name="upid">
                                    <option value="0">父路由</option>
                                    @foreach($routes as $v)
                                        <option value="{{$v->id}}">{{$v->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="search-button" style="margin-left: 20px;">
                        <input class="button" type="submit" value="新增路由"/>
                    </div>
                </div>
            </form>
            <div class="table">
                <div class="grid">
                    <table class="stable">
                        <tr class="str">
                            <th class="sth">编号</th>
                            <th class="sth">路由名称</th>
                            <th class="sth">路由路径</th>
                            <th class="sth">上级名称</th>
                            <th class="sth">操作</th>
                        </tr>
                        @foreach($data as $v)
                            <tr class="str">
                                <td class="std">{{$v->id}}</td>
                                <td class="std">{{$v->name}}</td>
                                <td class="std">{{$v->route}}</td>
                                <td class="std">
                                    @if(!empty($v->upid))
                                        @foreach($routes as $vv)
                                            @if($vv->id == $v->upid){{$vv->name}}@endif
                                        @endforeach
                                    @endif
                                </td>
                                <td class="std"><a href="javascript:;" onclick="modifyBaseRoute({{$v->id}})">修改</a>&nbsp;&nbsp;<a
                                            href="javascript:;" onclick="deleteBaseRoute({{$v->id}})">删除</a></td>
                            </tr>
                        @endforeach
                    </table>
                </div>
                <div class="grid">
                    {{ $data->links() }}
                </div>
            </div>
            <div id="optable" class="optable" onclick="closeDetails()"></div>
            <div id="atable" class="atable">
                <div style="width: 90%;height: 30px;">修改路由</div>
                <div class="utable" id="utable7"></div>
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
