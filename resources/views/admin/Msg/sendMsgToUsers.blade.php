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

    <title>添加产品</title>
</head>

<body>
    <div id="container" class="position">
        <div id="hd">
            </ div>
            <div id="bd">
                <div id="main">
                    <form action="{{url('sendMsgToUsers')}}" method="post" style="width: 100%; float: left" enctype="multipart/form-data">


                        <div class="search-area">
                            <div class="kv-item ue-clear">
                                <label>内容</label>
                                <div class="kv-item-content">
                                    <textarea rows="3" cols="20" name="content"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="search-button" style="margin-left: 20px; margin-top: 30px">
                            <input class="button" type="button" onclick="add()" value="发送" />
                        </div>
                        {{--</div>--}}
                    </form>
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
<script type="text/javascript" src="{{asset('js/myjs.js')}}"></script>
<script type="text/javascript" src="{{asset('js/layui/layui.all.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.form.js')}}"></script>
<script type="text/javascript">
    // 选择图片显示
    function imgChange(obj) {
        //获取点击的文本框
        var file = document.getElementById("file");
        var imgUrl = window.URL.createObjectURL(file.files[0]);
        var img = document.getElementById('imghead');
        img.setAttribute('src', imgUrl);
        // 修改img标签src属性值
    }

    function updateStatus(status, id) {
        $.get('updateUserStatus', {
            'status': status,
            'id': id
        }, function(data) {
            if (data.code == 0) {
                layer.msg(data.msg, {
                    icon: 1
                }, function() {
                    var index = parent.layer.getFrameIndex(window.name);
                    parent.layer.close(index);
                    window.parent.location.reload();
                    //刷新父页面
                });
            } else {
                layer.msg(data.msg, {
                    icon: 2
                });
            }
        })
    }

    function add() {
        $('form').ajaxSubmit(function(data) {
            if (data.code == 0) {
                layer.msg(data.msg, {
                    icon: 1
                }, function() {
                    // var index = parent.layer.getFrameIndex(window.name);
                    // parent.layer.close(index);
                    // window.parent.location.reload();
                    location.href = '/sendMsgToUsers';
                    //刷新父页面
                });
            } else {
                layer.msg(data.msg, {
                    icon: 2
                });
            }
        });
    }
</script>

</html>