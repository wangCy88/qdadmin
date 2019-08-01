<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>登录</title>
    <link rel="stylesheet" type="text/css" href="{{asset('css/mystyle.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('css/global.css')}}"/>
    <script type="text/javascript" src="{{asset('js/jquery.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/myjs.js')}}"></script>
</head>
<body>
<div class="dv">
    <div class="login">后 台</div>
    <div class="main">
        <ul>
            <li><span class="span3">用户名:&nbsp;&nbsp;&nbsp;</span><input type="text" class="input3" id="username"
                                                                        value=""/></li>
            <br/>
            <li><span class="span3">密&nbsp;&nbsp;&nbsp;码:&nbsp;&nbsp;&nbsp;</span><input type="password" class="input3"
                                                                                         id="password" value=""/></li>
            <br/>
            <li><span class="span3">验证码:&nbsp;&nbsp;&nbsp;</span><input type="text" class="input3" id="code" value=""/>
            </li>
            <br/>
            <li><span class="span3"><img style="width: 14%; height: auto;" src="{{url('createImgLoginCode/1')}}" alt=""
                                         onclick="this.src='{{url('createImgLoginCode')}}/'+Math.random()"/></span></li>
        </ul>
        <br/><br/>
        <input type="button" value="login" class="button3" onclick="login()"/>
    </div>
</div>
</body>
</html>