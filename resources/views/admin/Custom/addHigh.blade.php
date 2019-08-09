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

    <title>添加产品</title>
</head>

<body>
<div id="container" class="position">
    <div id="hd"></div>
    <div id="bd">
        <div id="main">
            <form action="{{url('customDetail')}}" method="post" style="width: 100%; float: left"
                  enctype="multipart/form-data">
                <input name="id" value="{{$id}}" style="display: none">
                <h2>基本信息</h2>

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
                        <label>手机号:</label>
                        <div class="kv-item-content">
                            <input type="text" style="width: 100px;height: 28px;" name="phone" value=""/>
                        </div>
                    </div>
                </div>

                <div class="search-area">
                    <div class="kv-item ue-clear">
                        <label>性别:</label>
                        <div class="kv-item-content">
                            <select name="sex" style="width: 150px">
                                @foreach($sexList as $k => $v)
                                    <option value="{{$k}}">{{$v}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="search-area">
                    <div class="kv-item ue-clear">
                        <label>年龄:</label>
                        <div class="kv-item-content">
                            <input type="text" style="width: 100px;height: 28px;" name="age" value=""/>
                        </div>
                    </div>
                </div>

                <div class="search-area">
                    <div class="kv-item ue-clear">
                        <label>归属地:</label>
                        <div class="kv-item-content">
                            {{--<input type="text" style="width: 200px;height: 28px;" name="product_max" value=""/>--}}
                            <select id="cmbProvince" name="location[]" style="width: 100px"></select>
                            <select id="cmbCity" name="location[]" style="width: 100px"></select>
                            <select id="cmbArea" name="location[]" style="width: 100px"></select>
                            {{--<br /><br />
                            省：<select id="Select1"></select>
                            市：<select id="Select2"></select>
                            区：<select id="Select3"></select>--}}
                            {{--<script type="text/javascript">
                                addressInit('cmbProvince', 'cmbCity', 'cmbArea', '陕西', '宝鸡市', '金台区');
                                /*addressInit('Select1', 'Select2', 'Select3');*/
                            </script>--}}
                        </div>
                    </div>
                </div>

                <h2>工作信息</h2>

                <div class="search-area">
                    <div class="kv-item ue-clear">
                        <label>职业:</label>
                        <div class="kv-item-content">
                            <input type="text" style="width: 100px;height: 28px;" name="job" value=""/>
                        </div>
                    </div>
                </div>


                <div class="search-area">
                    <div class="kv-item ue-clear">
                        <label>社保:</label>
                        <div class="kv-item-content">
                            <input type="radio" name="social" value="1" checked>有
                            <input type="radio" name="social" value="0">无
                        </div>
                    </div>
                </div>

                <div class="search-area">
                    <div class="kv-item ue-clear">
                        <label>公积金:</label>
                        <div class="kv-item-content">
                            <input type="radio" name="housing_fund" value="1" checked>有
                            <input type="radio" name="housing_fund" value="0">无
                        </div>
                    </div>
                </div>

                <div class="search-area">
                    <div class="kv-item ue-clear">
                        <label>工资月发:</label>
                        <div class="kv-item-content">
                            <input type="radio" name="wages" value="1" checked>有
                            <input type="radio" name="wages" value="0">无
                        </div>
                    </div>
                </div>

                <h2>资产信息</h2>

                <div class="search-area">
                    <div class="kv-item ue-clear">
                        <label>微粒贷:</label>
                        <div class="kv-item-content">
                            <input type="radio" name="webank" value="1" checked>有
                            <input type="radio" name="webank" value="0">无
                        </div>
                    </div>
                </div>
                {{--creditList--}}

                <div class="search-area">
                    <div class="kv-item ue-clear">
                        <label>寿险保单:</label>
                        <div class="kv-item-content">
                            <input type="radio" name="human_life" value="1" checked>有
                            <input type="radio" name="human_life" value="0">无
                        </div>
                    </div>
                </div>

                <div class="search-area">
                    <div class="kv-item ue-clear">
                        <label>信用状况:</label>
                        <div class="kv-item-content">
                            <select name="credit">
                                <option value="">请选择</option>
                                @foreach($creditList as $k => $v)
                                    <option value="{{$k}}">{{$v}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="search-area">
                    <div class="kv-item ue-clear">
                        <label>房:</label>
                        <div class="kv-item-content">
                            <input type="radio" name="house" value="1" checked>有
                            <input type="radio" name="house" value="0">无
                        </div>
                    </div>
                </div>

                <div class="search-area">
                    <div class="kv-item ue-clear">
                        <label>车:</label>
                        <div class="kv-item-content">
                            <input type="radio" name="car" value="1" checked>有
                            <input type="radio" name="car" value="0">无
                        </div>
                    </div>
                </div>

                <div class="search-area">
                    <div class="kv-item ue-clear">
                        <label>借款金额:</label>
                        <div class="kv-item-content">
                            <input type="text" style="width: 100px;height: 28px;" name="withdraw_amount" value=""/>
                        </div>
                    </div>
                </div>

                <div class="search-area">
                    <div class="kv-item ue-clear">
                        <label>是否优质:</label>
                        <div class="kv-item-content">
                            <input type="radio" name="is_high" value="1" checked>优质
                            <input type="radio" name="is_high" value="0">普通
                        </div>
                    </div>
                </div>
                <div class="search-button" style="margin-left: 20px; margin-top: 30px">
                    <input class="button" type="button" onclick="add()" value="保存"/>
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
<script type="text/javascript" src="{{asset('js/address.js')}}"></script>
<script type="text/javascript">
    addressInit('cmbProvince', 'cmbCity', 'cmbArea', '陕西', '宝鸡市', '金台区');
    addressInit('Select1', 'Select2', 'Select3');

    // 选择图片显示
    function imgChange(obj) {
        //获取点击的文本框
        var file = document.getElementById("file");
        var imgUrl = window.URL.createObjectURL(file.files[0]);
        var img = document.getElementById('imghead');
        img.setAttribute('src', imgUrl); // 修改img标签src属性值
    }

    function updateStatus(status, id) {
        $.get('updateUserStatus', {'status': status, 'id': id}, function (data) {
            if (data.code == 0) {
                layer.msg(data.msg, {icon: 1}, function () {
                    var index = parent.layer.getFrameIndex(window.name);
                    parent.layer.close(index);
                    window.parent.location.reload();//刷新父页面
                });
            } else {
                layer.msg(data.msg, {icon: 2});
            }
        })
    }

    function add() {
        $('form').ajaxSubmit(function (data) {
            if (data.code == 200) {
                layer.msg(data.msg, {icon: 1}, function () {
                    var index = parent.layer.getFrameIndex(window.name);
                    parent.layer.close(index);
                    window.parent.location.reload();//刷新父页面
                });
            } else {
                layer.msg(data.msg, {icon: 2});
            }
        });
    }
</script>
</html>
