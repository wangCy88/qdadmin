<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=emulateIE7"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('css/style.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('css/skin_/main.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('css/jquery.dialog.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('css/style.css')}}"/>
    <link rel="stylesheet" href="{{asset('css/zTreeStyle/zTreeStyle.css')}}" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{asset('css/skin_/nav.css')}}"/>
    <script type="text/javascript" src="{{asset('js/jquery.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/global.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/myjs.js')}}"></script>
    <title>抢单后台管理系统</title>
</head>

<body>
<div id="container">
    <div id="hd">
        <div class="hd-top">
            <h1 class="logo"></h1>
            <div class="user-info">
                <a href="javascript:;" class="user-avatar"><span></span></a>
                <span class="user-name">{{$name}}</span>
                <a href="javascript:;" class="more-info"></a>
            </div>
            <div class="setting ue-clear">
                <ul class="setting-main ue-clear">
                    <li><a href="javascript:;" class="close-btn exit" onclick="logout()"></a></li>
                </ul>
            </div>
        </div>
        <div class="hd-bottom">
            <i class="home"><a href="javascript:;"></a></i>
            <div class="nav-wrap">
                <ul class="nav ue-clear">
                    @foreach($data as $v)
                        <li><a href="javascript:;" onclick="changeModel({{$v->id}})">{{$v->name}}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    <div id="bd" style="width: 100%;">
        <div class="sidebar">
            <ul class="nav">

                <li class="nav-li current" id="tree">
                    <a href="javascript:;" class="ue-clear"><i class="nav-ivon"></i><span class="nav-text">首页</span></a>
                    <ul class="subnav">
                        <li class="subnav-li" href="{{url('index')}}" data-id="1"><a href="javascript:;"
                                                                                     class="ue-clear"><i
                                        class="subnav-icon"></i><span class="subnav-text">首页</span></a></li>
                    </ul>
                </li>
            </ul>
            <div class="tree-list outwindow">
                <div class="tree ztree"></div>
            </div>
        </div>
        <div class="main">
            <div class="title">
                <i class="sidebar-show"></i>
                <ul class="tab ue-clear">

                </ul>
                <i class="tab-more"></i>
                <i class="tab-close"></i>
            </div>
            <div class="content">
            </div>
        </div>
        {{--<iframe width="100%" height="100%" id="mainIframe" src="{{url('nav')}}" frameborder="0"></iframe>--}}
    </div>
</div>

{{--<div class="exitDialog">
    <div class="content">
        <div class="ui-dialog-icon"></div>
        <div class="ui-dialog-text">
            <p class="dialog-content">你确定要退出系统？</p>
            <p class="tips">如果是请点击“确定”，否则点“取消”</p>

            <div class="buttons">
                <input type="button" class="button long2 ok" value="确定" />
                <input type="button" class="button long2 normal" value="取消" />
            </div>
        </div>

    </div>
</div>--}}
</body>
<script type="text/javascript" src="{{asset('js/core.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.dialog.js')}}"></script>
<script type="text/javascript" src="{{asset('js/nav.js')}}"></script>
<script type="text/javascript" src="{{asset('js/Menu.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.ztree.core-3.5.js')}}"></script>
<script type="text/javascript">
    var menu = new Menu({
        defaultSelect: $('.nav').find('li[data-id="1"]')
    });

    // 左侧树结构加载
    var setting = {};

    var zNodes = [
        /*{ name:"新闻管理",
         children: [
         { name:"新闻视频管理",icon:'img/skin_/leftlist.png'},
         { name:"新闻频道管理",icon:'img/skin_/leftlist.png'},
         { name:"地方新闻管理",icon:'img/skin_/leftlist.png'}
         ]},
         { name:"用户信息设置", open:true,
         children: [
         { name:"首页", checked:true,icon:'img/skin_/leftlist.png'},
         { name:"表单",icon:'img/skin_/leftlist.png'},
         { name:"表格",icon:'img/skin_/leftlist.png'},
         { name:"自定义设置",icon:'img/skin_/leftlist.png'}
         ]},
         { name:"工作安排",
         children: [
         { name:"工作安排",icon:'img/skin_/leftlist.png'},
         { name:"安排管理",icon:'img/skin_/leftlist.png'},
         { name:"类型选择",icon:'img/skin_/leftlist.png'},
         { name:"自定义设置",icon:'img/skin_/leftlist.png'}
         ]},
         { name:"数据管理",
         children: [
         { name:"工作安排",icon:'img/skin_/leftlist.png'},
         { name:"安排管理",icon:'img/skin_/leftlist.png'},
         { name:"类型选择",icon:'img/skin_/leftlist.png'},
         { name:"自定义设置",icon:'img/skin_/leftlist.png'}
         ]}*/
    ];

    $.fn.zTree.init($(".tree"), setting, zNodes);


    $('.sidebar h2').click(function (e) {
        $('.tree-list').toggleClass('outwindow');
        $('.nav').toggleClass('outwindow');
    });

    $(document).click(function (e) {
        if (!$(e.target).is('.tab-more')) {
            $('.tab-more').removeClass('active');
            $('.more-bab-list').hide();
        }
    });
</script>
<script type="text/javascript">
    $("#bd").height($(window).height() - $("#hd").outerHeight() - 26);

    $(window).resize(function (e) {
        $("#bd").height($(window).height() - $("#hd").outerHeight() - 26);

    });

    /*$('.exitDialog').Dialog({
        title:'提示信息',
        autoOpen: false,
        width:400,
        height:200
    });

    $('.exit').click(function(){
        $('.exitDialog').Dialog('open');
    });

    $('.exitDialog input[type=button]').click(function(e) {
        $('.exitDialog').Dialog('close');

        if($(this).hasClass('ok')){
            window.location.href = "login.html"	;
        }
    });*/

    (function () {
        var totalWidth = 0, current = 1;

        $.each($('.nav').find('li'), function () {
            totalWidth += $(this).outerWidth();
        });

        //$('.nav').width(totalWidth);

        function currentLeft() {
            return -(current - 1) * 93;
        }

        $('.nav-btn a').click(function (e) {
            var tempWidth = totalWidth - (Math.abs($('.nav').css('left').split('p')[0]) + $('.nav-wrap').width());
            if ($(this).hasClass('nav-prev-btn')) {
                if (parseInt($('.nav').css('left').split('p')[0]) < 0) {
                    current--;
                    Math.abs($('.nav').css('left').split('p')[0]) > 93 ? $('.nav').animate({'left': currentLeft()}, 200) : $('.nav').animate({'left': 0}, 200);
                }
            } else {

                if (tempWidth > 0) {

                    current++;
                    tempWidth > 93 ? $('.nav').animate({'left': currentLeft()}, 200) : $('.nav').animate({'left': $('.nav').css('left').split('p')[0] - tempWidth}, 200);
                }
            }
        });


        $.each($('.skin-opt li'), function (index, element) {
            if ((index + 1) % 3 == 0) {
                $(this).addClass('third');
            }
            $(this).css('background', $(this).attr('attr-color'));
        });

        $('.setting-skin').click(function (e) {
            $('.skin-opt').show();
        });

        $('.skin-opt').click(function (e) {
            if ($(e.target).is('li')) {
                alert($(e.target).attr('attr-color'));
            }
        });

        $('.hd-top .user-info .more-info').click(function (e) {
            $(this).toggleClass('active');
            $('.user-opt').toggle();
        });

        $('.logo-icon').click(function (e) {
            $(this).toggleClass('active');
            $('.system-switch').toggle();
        });

        hideElement($('.user-opt'), $('.more-info'), function (current, target) {

            $('.more-info').removeClass('active');
        });

        hideElement($('.skin-opt'), $('.switch-bar'));

        hideElement($('.system-switch'), $('.logo-icon'), function (current, target) {

            $('.logo-icon').removeClass('active');
        });


    })();


</script>

</html>
