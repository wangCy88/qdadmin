//登录
function login() {
    var username = $('#username').val();
    var password = $('#password').val();
    var code = $('#code').val();
    if (username.length != 0 && password.length != 0 && code.length != 0) {
        $.ajax({
            url: "/login",
            data: {"username": username, "password": password, "code": code},
            type: "Post",
            dataType: "json",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function (data) {
                if (data.code === 0) {
                    window.location.href = 'main';
                } else {
                    alert(data.msg);
                }
            },
            error: function () {

            }
        });
    }
}

//登出
function logout() {
    $.ajax({
        url: "/logout",
        data: {},
        type: "Post",
        dataType: "json",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function (data) {
            if (data.code === 0) {
                window.location.href = 'login';
            }
        },
        error: function () {

        }
    });
}

//切换模块
function changeModel(id) {
    if (id.length != 0) {
        $.ajax({
            url: "/changeModel",
            data: {"id": id},
            type: "Post",
            dataType: "json",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function (data) {
                if (data.code === 0 && data.data.length > 0) {
                    $('#tree').html('');
                    var table = '';
                    table += "<a href='javascript:;' class='ue-clear'><i class='nav-ivon'></i>" +
                        "<span class='nav-text'>" + data.model + "</span></a>";
                    table += "<ul class='subnav'>";
                    for (var p in data.data) {
                        if (data.data[p].id > 0) {
                            table += "<li class='subnav-li' href='" + data.url + data.data[p].route + "' " + "data-id='" + data.data[p].id + "'>" +
                                "<a href='javascript:;' class='ue-clear'><i class='subnav-icon'></i>" +
                                "<span class='subnav-text'>" + data.data[p].name + "</span></a></li>";
                        }
                    }
                    table += "</ul>";
                    $('#tree').html(table);
                }
            },
            error: function () {

            }
        });
    }
}

//禁用滚动条
function unScroll() {
    var top = $(document).scrollTop();
    $(document).on('scroll.unable', function (e) {
        $(document).scrollTop(top);
    })
}

//解除禁用滚动条
function removeUnScroll() {
    $(document).unbind("scroll.unable");
}

//获取内部路由
function modifyBaseRoute(id) {
    if (id.length != 0) {
        $.ajax({
            url: "/modifyBaseRoute",
            data: {"id": id},
            type: "Post",
            dataType: "json",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function (data) {
                if (data.code === 0) {
                    $('#utable7').html('');
                    var table = '';
                    table += "<div class='div'> <span class='span'>路由名称:</span><input type='text' id='name' value='" + data.data.name + "'/> </div> " +
                        "<div class='div'> <span class='span'>路由路径:</span><input type='text' id='route' value='" + data.data.route + "'/> </div> " +
                        "<div class='div'> <span class='span'>上级路由:</span> <select id='upid'> <option value='0' ";
                    if (data.data.upid === 0) {
                        table += "selected='selected'";
                    }
                    table += ">父路由</option> ";
                    for (var p in data.routes) {
                        if (data.routes[p].id > 0) {
                            table += "<option value='" + data.routes[p].id + "' ";
                            if (data.routes[p].id === data.data.upid) {
                                table += "selected='selected'";
                            }
                            table += ">" + data.routes[p].name + "</option>"
                        }
                    }
                    table += "</select> </div> " +
                        "<div class='div center'> <button type='button' class='span' onclick='modifyBaseRouteDo(" + id + ")'>修改</button> " +
                        "<button type='button' class='span' onclick='closeDetails()'>取消</button> </div>";
                    $('#utable7').html(table);
                    unScroll();
                    $('#atable').css('display', 'block');
                    $('#optable').css('display', 'block');
                } else {
                    alert(data.msg);
                }
            },
            error: function () {

            }
        });
    }
}

//修改内部路由
function modifyBaseRouteDo(id) {
    var name = $('#name').val();
    var route = $('#route').val();
    var upid = $('#upid').val();
    if (id.length != 0 && name.length != 0 && route.length != 0 && upid.length != 0) {
        $.ajax({
            url: "/modifyBaseRouteDo",
            data: {"id": id, "name": name, "route": route, "upid": upid},
            type: "Post",
            dataType: "json",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function (data) {
                if (data.code === 0) {
                    alert(data.msg);
                    window.location.reload();
                } else {
                    alert(data.msg);
                }
            },
            error: function () {

            }
        });
    } else {
        alert('请填写完整');
    }
}

//删除内部路由
function deleteBaseRoute(id) {
    if (id.length != 0) {
        $.ajax({
            url: "/deleteBaseRoute",
            data: {"id": id},
            type: "Post",
            dataType: "json",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function (data) {
                if (data.code === 0) {
                    alert(data.msg);
                    window.location.reload();
                } else {
                    alert(data.msg);
                }
            },
            error: function () {

            }
        });
    }
}

//获取内部路由列表
function addBaseGroup() {
    $.ajax({
        url: "/addBaseGroup",
        data: {},
        type: "Post",
        dataType: "json",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function (data) {
            if (data.code === 0) {
                /*var json={
                 '0-0':{
                 '0-0-0':1,
                 '0-0-1':2,
                 '0-0-2':3
                 },
                 '0-1':{
                 '0-1-0':4,
                 '0-1-1':5
                 }
                 };*/
                var json = data.data;
                $('#name').val('');
                $('#utable9').html('');
                generate(json, document.getElementById('utable9'));
                unScroll();
                $('#gtable1').css('display', 'block');
                $('#optable').css('display', 'block');
            }
        },
        error: function () {

        }
    });
}

//新增内部角色
function addBaseGroupDo() {
    var name = $('#name').val();
    var routes = document.getElementsByName('routes');
    var routes_arr = [];
    for (p in routes) {
        if (routes[p].checked) {
            routes_arr.push(routes[p].value);
        }
    }
    if (name.length != 0 && routes_arr.length != 0) {
        $.ajax({
            url: "/addBaseGroupDo",
            data: {"name": name, "routes": routes_arr},
            type: "Post",
            dataType: "json",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function (data) {
                if (data.code === 0) {
                    alert(data.msg);
                    window.location.reload();
                } else {
                    alert(data.msg);
                }
            },
            error: function () {

            }
        });
    } else {
        alert('请填写参数');
    }
}

//获取内部角色
function modifyBaseGroup(id) {
    if (id.length != 0) {
        $.ajax({
            url: "/modifyBaseGroup",
            data: {"id": id},
            type: "Post",
            dataType: "json",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function (data) {
                //console.log(data);return;
                if (data.code === 0) {
                    $('#id').val(id);
                    $('#name2').val(data.name);
                    var json = data.data;
                    var info = data.route;
                    $('#utable10').html('');
                    generate2(json, document.getElementById('utable10'), info);
                    unScroll();
                    $('#gtable2').css('display', 'block');
                    $('#optable').css('display', 'block');
                } else {
                    alert(data.msg);
                }
            },
            error: function () {

            }
        });
    }
}

//修改内部角色
function modifyBaseGroupDo() {
    var id = $('#id').val();
    var name = $('#name2').val();
    var routes = document.getElementsByName('routes2');
    var routes_arr = [];
    for (p in routes) {
        if (routes[p].checked) {
            routes_arr.push(routes[p].value);
        }
    }
    if (id.length != 0 && name.length != 0 && routes_arr.length != 0) {
        $.ajax({
            url: "/modifyBaseGroupDo",
            data: {"id": id, "name": name, "routes": routes_arr},
            type: "Post",
            dataType: "json",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function (data) {
                if (data.code === 0) {
                    alert(data.msg);
                    window.location.reload();
                } else {
                    alert(data.msg);
                }
            },
            error: function () {

            }
        });
    } else {
        alert('请填写参数');
    }
}

//删除内部角色
function deleteBaseGroup(id) {
    if (id.length != 0) {
        $.ajax({
            url: "/deleteBaseGroup",
            data: {"id": id},
            type: "Post",
            dataType: "json",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function (data) {
                if (data.code === 0) {
                    alert(data.msg);
                    window.location.reload();
                } else {
                    alert(data.msg);
                }
            },
            error: function () {

            }
        });
    } else {
        alert('请填写参数');
    }
}

//获取内部用户
function modifyBaseUser(id) {
    if (id.length != 0) {
        $.ajax({
            url: "/modifyBaseUser",
            data: {"id": id},
            type: "Post",
            dataType: "json",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function (data) {
                if (data.code === 0) {
                    $('#utable11').html('');
                    var table = '';
                    table += "<div class='div'><span class='span'>姓名:</span><input type='text' id='name' value='" + data.data.name + "'/></div> " +
                        "<div class='div'><span class='span'>手机号:</span><input type='text' id='phone' value='" + data.data.phone + "'/></div> " +
                        "<div class='div'><span class='span'>性别:</span><select id='sex'>";
                    for (var p in data.sexList) {
                        if (p < data.sexList.length) {
                            table += "<option value='" + p + "'";
                            if (p.toString() === data.data.sex.toString()) {
                                table += " selected='selected'";
                            }
                            table += ">" + data.sexList[p] + "</option>";
                        }
                    }
                    table += "</select></div> <div class='div'><span class='span'>在职状态:</span><select id='status'>";
                    for (var p in data.jobStatusList) {
                        if (p < data.jobStatusList.length) {
                            table += "<option value='" + p + "'";
                            if (p.toString() === data.data.status.toString()) {
                                table += " selected='selected'";
                            }
                            table += ">" + data.jobStatusList[p] + "</option>";
                        }
                    }
                    table += "</select></div> <div class='div'><span class='span'>角色:</span><select id='gid'>";
                    for (var p in data.groups) {
                        if (data.groups[p].id > 0) {
                            table += "<option value='" + data.groups[p].id + "'";
                            if (data.groups[p].id === data.data.gid) {
                                table += " selected='selected'";
                            }
                            table += ">" + data.groups[p].name + "</option>";
                        }
                    }
                    table += "</select></div> <div class='div center'> <button type='button' class='span' onclick='modifyBaseUserDo(" + id + ")'>修改</button> " +
                        "<button type='button' class='span' onclick='closeDetails()'>取消</button> </div>";
                    $('#utable11').html(table);
                    unScroll();
                    $('#mutable').css('display', 'block');
                    $('#optable').css('display', 'block');
                } else {
                    alert(data.msg);
                }
            },
            error: function () {

            }
        });
    }
}

//修改内部用户
function modifyBaseUserDo(id) {
    var name = $('#name').val();
    var phone = $('#phone').val();
    var sex = $('#sex').val();
    var status = $('#status').val();
    var gid = $('#gid').val();
    if (id.length != 0 && name.length != 0 && phone.length != 0 && sex.length != 0 && status.length != 0 && gid.length != 0) {
        $.ajax({
            url: "/modifyBaseUserDo",
            data: {"id": id, "name": name, "phone": phone, "sex": sex, "status": status, "gid": gid},
            type: "Post",
            dataType: "json",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function (data) {
                if (data.code === 0) {
                    alert(data.msg);
                    window.location.reload();
                } else {
                    alert(data.msg);
                }
            },
            error: function () {

            }
        });
    } else {
        alert('请填写参数');
    }
}

//修改内部密码面板
function modifyBaseUserPwd(id) {
    if (id.length != 0) {
        $('#utable12').html('');
        var table = '';
        table += "<div class='div'> <span class='span'>新密码:</span><input type='text' id='npassword'/> </div> " +
            "<div class='div center'> <button type='button' class='span' onclick='modifyBaseUserPwdDo(" + id + ")'>修改</button> " +
            "<button type='button' class='span' onclick='closeDetails()'>取消</button> </div>";
        $('#utable12').html(table);
        unScroll();
        $('#uptable').css('display', 'block');
        $('#optable').css('display', 'block');
    }
}

//修改内部密码
function modifyBaseUserPwdDo(id) {
    if (id.length != 0) {
        var password = $('#npassword').val();
        if (password.length != 0) {
            $.ajax({
                url: "/modifyBaseUserPwdDo",
                data: {"id": id, "password": password},
                type: "Post",
                dataType: "json",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function (data) {
                    if (data.code === 0) {
                        alert(data.msg);
                        window.location.reload();
                    } else {
                        alert(data.msg);
                    }
                },
                error: function () {

                }
            });
        } else {
            alert('请填写新密码');
        }
    }
}

//删除内部用户
function deleteBaseUser(id) {
    if (id.length != 0) {
        $.ajax({
            url: "/deleteBaseUser",
            data: {"id": id},
            type: "Post",
            dataType: "json",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function (data) {
                if (data.code === 0) {
                    alert(data.msg);
                    window.location.reload();
                } else {
                    alert(data.msg);
                }
            },
            error: function () {

            }
        });
    } else {
        alert('请填写参数');
    }
}

//树状列表
function generate(json, par) {
    for (var attr in json) {
        var ele = document.createElement('li');
        if (!json[attr])
            ele.innerHTML = " <input type='checkbox'></input>" + attr;
        else {
            if (json[attr] instanceof Object) {
                ele.innerHTML = "<span><span class='switch-open' onclick='toggle(this)'></span><input type='checkbox' onclick='checkChange(this)'/>" + attr + "</span>";
            } else {
                ele.innerHTML = "<span style='margin-left: 10%;'><input type='checkbox' onclick='checkChange(this)' name='routes' value='" + json[attr] + "' />" + attr + "</span>";
            }
            var nextpar = document.createElement('ul');
            ele.appendChild(nextpar);
            generate(json[attr], nextpar);
        }
        par.appendChild(ele);
    }
}

function generate2(json, par, info) {
    for (var attr in json) {
        var ele = document.createElement('li');
        if (!json[attr])
            ele.innerHTML = " <input type='checkbox'></input>" + attr;
        else {
            if (json[attr] instanceof Object) {
                ele.innerHTML = "<span><span class='switch-open' onclick='toggle(this)'></span><input type='checkbox' onclick='checkChange(this)'/>" + attr + "</span>";
            } else {
                if ($.inArray(json[attr].toString(), info) >= 0) {
                    ele.innerHTML = "<span style='margin-left: 10%;'><input type='checkbox' onclick='checkChange(this)' name='routes2' value='" + json[attr] + "' checked='checked'/>" + attr + "</span>";
                } else {
                    ele.innerHTML = "<span style='margin-left: 10%;'><input type='checkbox' onclick='checkChange(this)' name='routes2' value='" + json[attr] + "' />" + attr + "</span>";
                }
            }
            var nextpar = document.createElement('ul');
            ele.appendChild(nextpar);
            generate2(json[attr], nextpar, info);
        }
        par.appendChild(ele);
    }
}

//处理展开和收起
function toggle(eve) {
    var par = eve.parentNode.nextElementSibling;
    if (par.style.display == 'none') {
        par.style.display = 'block';
        eve.className = 'switch-open';

    }
    else {
        par.style.display = 'none';
        eve.className = 'switch-close';
    }
}

//处理全部勾选和全部不选
function checkChange(eve) {
    var oul = eve.parentNode.nextElementSibling;
    if (eve.checked) {
        for (var i = 0; i < oul.querySelectorAll('input').length; i++) {
            oul.querySelectorAll('input')[i].checked = true;
        }
    }
    else {
        for (var i = 0; i < oul.querySelectorAll('input').length; i++) {
            oul.querySelectorAll('input')[i].checked = false;
        }
    }
}

//关闭所有面板
function closeDetails() {
    $('#uptable').css('display', 'none');
    $('#mutable').css('display', 'none');
    $('#gtable2').css('display', 'none');
    $('#gtable1').css('display', 'none');
    $('#atable').css('display', 'none');
    $('#optable').css('display', 'none');
    removeUnScroll();
}

function getUsers(id) {

}

