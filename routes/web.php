<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


//测试
Route::any('test', 'TestController@test');

//前端用

Route::any('createImgCode', 'ClientController@createImgCode'); //图片验证码
Route::any('register', 'ClientController@register'); //用户注册
Route::any('clientLogin', 'ClientController@clientLogin'); //用户登录
Route::any('clientCodeLogin', 'ClientController@clientCodeLogin'); //用户验证码登录
Route::any('baseAuth', 'ClientController@baseAuth'); //基础认证
Route::any('getUserInfo', 'ClientController@getUserInfo'); //获取用户基础数据
Route::any('changePassword', 'ClientController@changePassword'); //修改密码
Route::any('retrievePassword', 'ClientController@retrievePassword'); //找回密码
Route::any('clientSendMsg', 'ClientController@clientSendMsg'); //发送短信验证码
Route::any('createImgCode', 'ClientController@createImgCode'); //图片验证码
Route::any('ClientIndex', 'ClientController@ClientIndex'); //首页图片
Route::any('userRepeatRegister', 'ClientController@userRepeatRegister'); //判断用户重复注册


//用户模块
Route::any('userIndex', 'UserController@index'); //用户首页
Route::any('userInfo', 'UserController@userInfo'); // 用户实名认证 / 用户信息
Route::any('cardTicketList', 'UserController@cardTicketList'); // 卡券商品列表
Route::any('pointsList', 'UserController@pointsList'); //积分商品列表
Route::any('bankList', 'UserController@bankList'); //银行列表
Route::any('bindBankCardCode', 'UserController@bindBankCardCode'); //发送绑定银行卡验证码
Route::any('bindBankCard', 'UserController@bindBankCard'); //绑定银行卡
Route::any('userBankList', 'UserController@userBankList'); //银行卡列表
Route::any('updateMainCard', 'UserController@updateMainCard'); //更换主卡
Route::any('cardTicketPay', 'UserController@cardTicketPay'); //购买卡券
Route::any('pointsPay', 'UserController@pointsPay'); //购买积分
Route::any('payCallBack', 'JdController@payCallBack'); //京东支付回调
Route::any('aliCallBack', 'AliController@aliCallBack'); //支付宝支付回调
Route::any('userOpen', 'UserController@userOpen'); // 开启/关闭 自动抢单
Route::any('inviteUrl', 'UserController@inviteUrl');
Route::any('consumeDetail', 'UserController@consumeDetail'); //流水
Route::any('getOrder', 'UserController@getOrder'); //获取支付订单号
Route::any('getOrderResult', 'UserController@getOrderResult'); // 获取支付结果

//意见反馈
Route::any('feedbackType', 'MsgController@feedbackType'); //意见反馈类型
Route::any('submitFeedback', 'MsgController@submitFeedback'); //提交意见反馈
Route::any('getFeedback', 'MsgController@getFeedback'); //获取意见反馈
Route::any('getFeedbackDetail', 'MsgController@getFeedbackDetail'); //获取意见反馈详情
Route::any('msgList', 'MsgController@msgList'); //消息列表
Route::any('isRead', 'MsgController@isRead'); //是否存在未读消息

//客户模块

Route::post('customList', 'CustomController@customList'); //普通客户列表
Route::get('gouNhCustom', 'CustomController@gouNhCustom'); //获取够你花客户
Route::get('xiaohuaCustom', 'CustomController@xiaohuaCustom'); // 获取小麦花用户
Route::get('gouNhHigh', 'CustomController@gouNhHigh'); // 补充资料
Route::get('customAssign', 'CustomController@customAssign'); //自动分配客户给信贷经理
Route::any('highCustomList', 'CustomController@highCustomList'); //优质客户列表
Route::any('orderDetail', 'CustomController@orderDetail'); //客户详情
Route::any('robOrder', 'CustomController@robOrder'); // 抢单
Route::any('userOrder', 'CustomController@userOrder'); // 我的订单
Route::any('exitOrderAccount', 'CustomController@exitOrderAccount'); //退单理由
Route::any('exitOrder', 'CustomController@exitOrder'); //退单
Route::any('customRepeatRegister', 'ClientController@customRepeatRegister'); //客户重复注册
//H5推广
Route::any('customRegister', 'ClientController@customRegister'); // 客户申请H5注册
Route::post('perfectData', 'ClientController@perfectData'); // 客户完善资料

//统计
Route::any('userFormTotal', 'ClientController@userFormTotal'); //用户 H5 注册统计
Route::any('customFormTotal', 'ClientController@customFormTotal'); //客户统计
//后端用
Route::any('login', 'IndexController@login'); //登陆
Route::any('logout', 'IndexController@logout'); //登出
Route::any('createImgLoginCode/{rand}', 'IndexController@createImgLoginCode'); //图片验证码
Route::group(['middleware' => ['login']], function () {
    Route::any('main', 'IndexController@main'); //页面顶部显示
    Route::any('changeModel', 'IndexController@changeModel'); //切换模块
    /*Route::any('nav', 'IndexController@nav');//页面底部显示*/
    Route::any('index', 'IndexController@index'); //首页


    Route::any('baseRouteControl', 'SystemController@baseRouteControl'); //内部路由管理
    Route::any('addBaseRoute', 'SystemController@addBaseRoute'); //新增内部路由
    Route::any('modifyBaseRoute', 'SystemController@modifyBaseRoute'); //获取内部路由
    Route::any('modifyBaseRouteDo', 'SystemController@modifyBaseRouteDo'); //修改内部路由
    Route::any('deleteBaseRoute', 'SystemController@deleteBaseRoute'); //删除内部路由
    Route::any('baseGroupControl', 'SystemController@baseGroupControl'); //内部角色管理
    Route::any('addBaseGroup', 'SystemController@addBaseGroup'); //获取内部路由列表
    Route::any('addBaseGroupDo', 'SystemController@addBaseGroupDo'); //新增内部角色
    Route::any('modifyBaseGroup', 'SystemController@modifyBaseGroup'); //获取内部角色
    Route::any('modifyBaseGroupDo', 'SystemController@modifyBaseGroupDo'); //修改内部角色
    Route::any('deleteBaseGroup', 'SystemController@deleteBaseGroup'); //删除内部角色
    Route::any('baseUserControl', 'SystemController@baseUserControl'); //内部用户管理
    Route::any('addBaseUser', 'SystemController@addBaseUser'); //新增内部用户
    Route::any('modifyBaseUser', 'SystemController@modifyBaseUser'); //获取内部用户
    Route::any('modifyBaseUserDo', 'SystemController@modifyBaseUserDo'); //修改内部用户
    Route::any('modifyBaseUserPwdDo', 'SystemController@modifyBaseUserPwdDo'); //修改内部密码
    Route::any('deleteBaseUser', 'SystemController@deleteBaseUser'); //删除内部用户

    //信贷经历
    Route::any('adminUserList', 'admin\UserController@userList'); //信贷经历列表
    Route::get('getUsers', 'admin\UserController@getUsers'); // 获取经理详细资料
    Route::get('updateUserStatus', 'admin\UserController@updateUserStatus'); //认证
    Route::any('sendNews', 'admin\UserController@sendNews'); //发送消息
    Route::any('userFormList', 'admin\UserController@userFormList'); //信贷经理
    Route::any('userFormSee', 'admin\UserController@userFormSee'); //用户渠道监控
    Route::any('userFormStatus', 'admin\UserController@userFormStatus'); //
    Route::any('userFormEdit', 'admin\UserController@userFormEdit');
    Route::any('userFromAdd', 'admin\UserController@userFromAdd');

    //客户管理
    Route::any('adminCustomList', 'admin\CustomController@customList'); //客户列表
    Route::any('customDetail', 'admin\CustomController@customDetail'); //客户详情
    Route::any('userAssign', 'admin\CustomController@userAssign'); //订单分派
    Route::any('customOrder', 'admin\CustomController@customOrder'); //订单管理
    Route::any('exitCustomOrder', 'admin\CustomController@exitCustomOrder'); //处理退单
    Route::any('customFormList', 'admin\CustomController@customFormList'); //客户渠道列表
    Route::any('customFormAdd', 'admin\CustomController@customFormAdd'); //添加客户渠道
    Route::any('customFormEdit', 'admin\CustomController@customFormEdit'); //编辑客户渠道
    Route::any('customFormStatus', 'admin\CustomController@customFormStatus'); //开启/关闭
    Route::any('customFormSee', 'admin\CustomController@customFormSee'); //客户渠道监控


    //商品管理
    Route::any('adminCardTicketList', 'admin\GoodsController@cardTicketList'); //卡券管理
    Route::any('addCardTicket', 'admin\GoodsController@addCardTicket'); //添加卡券
    Route::any('updateStatus', 'admin\GoodsController@updateStatus'); //卡券上下架
    Route::any('updateCardTicket', 'admin\GoodsController@updateCardTicket'); //编辑卡券
    Route::any('adminPointsList', 'admin\GoodsController@pointsList'); // 积分产品列表
    Route::any('addPoints', 'admin\GoodsController@addPoints'); //添加积分产品
    Route::any('updatePoints', 'admin\GoodsController@updatePoints'); //编辑积分产品
    Route::any('pointsStatus', 'admin\GoodsController@pointsStatus'); // 积分上下架

    //消息管理
    Route::any('adminMsgList', 'admin\MsgController@msgList'); //反馈列表
    Route::any('sendMsgToUser', 'admin\MsgController@sendMsgToUser'); //回复消息
    Route::any('sendMsgToUsers', 'admin\MsgController@sendMsgToUsers'); //发送多人消息

});
