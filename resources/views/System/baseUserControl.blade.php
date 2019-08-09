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
            <form action="{{url('addBaseUser')}}" method="post">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <div class="search-box ue-clear">
                    <div class="search-area">
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
                    </div>
                    <div class="search-area">
                        <div class="kv-item ue-clear">
                            <label>密码:</label>
                            <div class="kv-item-content">
                                <input type="text" style="width: 100px;height: 28px;" name="password" value=""/>
                            </div>
                        </div>
                    </div>
                    <div class="search-area">
                        <div class="kv-item ue-clear">
                            <label>手机号:</label>
                            <div class="kv-item-content">
                                <input type="text" style="width: 100px;height: 28px;" name="phone" value=""/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="search-box ue-clear">
                    <div class="search-area">
                        <div class="kv-item ue-clear">
                            <label>性别:</label>
                            <div class="kv-item-content">
                                <select style="width: 120px;" name="sex">
                                    <option value="">请选择</option>
                                    @foreach($sexList as $k => $v)
                                        <option value="{{$k}}">{{$v}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="search-area">
                        <div class="kv-item ue-clear">
                            <label>在职状态:</label>
                            <div class="kv-item-content">
                                <select style="width: 120px;" name="status">
                                    <option value="">请选择</option>
                                    @foreach($jobStatusList as $k => $v)
                                        <option value="{{$k}}">{{$v}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="search-area">
                        <div class="kv-item ue-clear">
                            <label>角色:</label>
                            <div class="kv-item-content">
                                <select style="width: 120px;" name="gid">
                                    <option value="">请选择</option>
                                    @foreach($groups as $v)
                                        <option value="{{$v->id}}">{{$v->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="search-button" style="margin-left: 20px;">
                        <input class="button" type="submit" value="新增用户"/>
                    </div>
                </div>
            </form>
            <div class="table">
                <div class="grid">
                    <table class="stable">
                        <tr class="str">
                            <th class="sth">编号</th>
                            <th class="sth">姓名</th>
                            <th class="sth">账号</th>
                            <th class="sth">手机号</th>
                            <th class="sth">性别</th>
                            <th class="sth">状态</th>
                            <th class="sth">角色</th>
                            <th class="sth">操作</th>
                        </tr>
                        @foreach($data as $k => $v)
                            <tr class="str">
                                <td class="std">{{$k+1}}</td>
                                <td class="std">{{$v->name}}</td>
                                <td class="std">{{$v->account}}</td>
                                <td class="std">{{$v->phone}}</td>
                                <td class="std">{{$sexList[$v->sex]}}</td>
                                <td class="std">{{$jobStatusList[$v->status]}}</td>
                                <td class="std">{{$v->baseGroups->name}}</td>
                                <td class="std"><a href="javascript:;" onclick="modifyBaseUser({{$v->id}})">修改用户</a>&nbsp;&nbsp;
                                    <a href="javascript:;" onclick="modifyBaseUserPwd({{$v->id}})">修改密码</a>&nbsp;&nbsp;
                                    <a href="javascript:;" onclick="deleteBaseUser({{$v->id}})">删除</a></td>
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
</html>
