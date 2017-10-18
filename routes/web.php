<?php

// API
// 
$this->get('/yzm/{token}','ApiloginController@yzm');//验证码生成
$this->post('/apilogin','ApiloginController@apilogin');
$this->get('/channellist/{page}/{companyid}/{userid}/{token}','ApiHomeController@list');//频道列表
$this->get('/channellisttime/{companyid}/{da}/{userid}/{token}','ApiHomeController@listtime');//频道列表
$this->post('/channellistsearch/{companyid}/{userid}/{token}','ApiHomeController@listsearch');//按条件查找频道列表

$this->get('/channellistnopage/{companyid}/{userid}/{token}','ApiHomeController@listnopage');
$this->post('/postchannelcreate/{uid}/{companyid}/{ak}/{as}/{token}','ApiChannelController@postchannelcreate');//创建频道
$this->get('/channeldata/{channel_id}/{ak}/{as}/{token}','ApiChannelController@channeldata');//修改时获取频道信息
$this->post('/channelalert/{channel_id}/{ak}/{as}/{token}','ApiChannelController@channelalert');//修改频道信息
$this->post('/channelguide/{channel_id}/{ak}/{as}/{token}','ApiChannelController@channelguide');//新增频道引导页
$this->get('/channelguideinfo/{channel_id}/{ak}/{as}/{token}','ApiChannelController@channelguideinfo');//频道引导页信息
$this->get('/apicompany/{id}/{token}','CompanyController@apishow');//展示公司所属
$this->get('/qr/{channel_id}/{ak}/{as}/{token}','ApiChannelController@qr');//生成观看二维码
$this->get('/channelproblemlist/{channel_id}/{ak}/{as}/{token}','ApiChannelController@channelproblemlist');//问卷列表
$this->post('/channelproblem/{channel_id}/{ak}/{as}/{token}','ApiChannelController@channelproblem');//添加问卷
$this->get('/channelproblemdata/{wenjuan_id}/{ak}/{as}/{token}','ApiChannelController@channelproblemdata');//问卷信息
$this->get('/channel/{channel_id}/danmu_open/{ak}/{as}/{token}','ApiChannelController@channeldanmuopen');//频道弹幕开
$this->get('/channel/{channel_id}/danmu_close/{ak}/{as}/{token}','ApiChannelController@channeldanmuclose');//频道弹幕关
$this->get('/channel/{channel_id}/danmu_checko/{ak}/{as}/{token}','ApiChannelController@channeldanmuchecko');//频道弹幕审核开
$this->get('/channel/{channel_id}/danmu_checkc/{ak}/{as}/{token}','ApiChannelController@channeldanmucheckc');//频道弹幕审核关
$this->get('/channel/{channel_id}/privatemessage_open/{ak}/{as}/{token}','ApiChannelController@privatemessageo');//频道私信开
$this->get('/channel/{channel_id}/privatemessage_close/{ak}/{as}/{token}','ApiChannelController@privatemessagec');//频道私信关
$this->get('/channel/{danmu_id}/danmupass/{token}','ApiChannelController@danmupass');//弹幕通过
$this->get('/channel/{danmu_id}/danmunotpass/{token}','ApiChannelController@danmunot');//弹幕不通过
$this->get('/apichanneluserslist/{token}','ApiChannelController@userslist');//管理员列表
$this->get('/apichannel/{id}/count/{token}','ApiChannelController@apicount');
$this->get('/apichannel/{channel_id}/{ak}/{as}/start/{token}','ApiChannelController@start');//直播间打开
$this->get('/apichannel/{channel_id}/{ak}/{as}/stop/{token}','ApiChannelController@stop');//直播间关闭
$this->get('/apichannel/{channelid}/start_record/{ak}/{as}/{token}','ApiChannelController@startRecord');//直播开始录制
$this->get('/apichannel/{channelid}/stop_record/{ak}/{as}/{token}','ApiChannelController@stopRecord');//直播开始录制
$this->get('/apichannel/{channel_id}/{ak}/{as}/delete/{token}','ApiChannelController@delete');//删除直播间
$this->get('/apichannel/{channel_id}/{ak}/{as}/control/{token}','ApiChannelController@apicontrol');//频道编辑的数据
$this->get('/apichannel/{channel_idid}/{ak}/{as}/openlogo/{token}','ApiChannelController@apilogoopen');//频道编辑引导页开闭
$this->get('/apichannel/{channel_id}/{ak}/{as}/closelogo/{token}','ApiChannelController@apilogoclose');//频道编辑引导页开闭
// $this->post('/api/rm','ApiChannelController@removeImage');
$this->get('/apichannel/{channel_id}/see/{token}','ApiChannelController@see');//频道观看方式信息
$this->post('/apichannel/{channel_id}/waysee/{token}','ApiChannelController@waysee');//频道观看方式

$this->get('/apichannel/{channel_id}/foretell/{token}','ApiChannelController@foretell');//频道预告信息
$this->get('/apichannel/{channel_id}/foretell_close/{token}','ApiChannelController@foretellclose');//频道预告信息关
$this->get('/apichannel/{channel_id}/foretell_open/{token}','ApiChannelController@foretellopen');//频道预告信息开
$this->post('/apichannel/{channel_id}/foretell_alert/{token}','ApiChannelController@video');//频道预告信息修改


$this->get('/channel/{id}/{ak}/{as}','WechatPageController@channel');
$this->get('/live/{id}/{ak}/{as}','WechatPageController@live');
$this->get('/lists/{id}','WechatPageController@lists');
$this->get('/play/{id}','WechatPageController@play');
// API
// 
$this->post('/apichannel/{user_id}/company/{token}','ApiChannelController@usercompany');
// 
// 
// $this->get('/db','AuthController@db');
$this->get('/yz','AuthController@yz');
$this->get('/login',['as'=>'login','uses'=>'AuthController@showLogin']);
$this->post('/login','AuthController@login');
$this->get('/sign','AuthController@showSign');
$this->post('/sign','AuthController@sign');
$this->get('/forget','AuthController@showForget');
$this->post('/forget','AuthController@forget');

$this->get('/forget_change','AuthController@showForgetChange');
$this->post('/forget_change','AuthController@forgetChange');
$this->get('/law','AuthController@law');

Route::group(['middleware' => 'auth'], function () {
    $this->get('/logout','AuthController@logout');
    $this->get('/change_password','AuthController@showChangePassword');
    $this->post('/change_password','AuthController@changePassword');

    $this->get('/','HomeController@index');
    
    $this->get('/type/{type}','HomeController@type');
    $this->post('/','HomeController@search');

    $this->get('/channel/{id}/control','ChannelController@control');
    $this->get('/channel/{id}/count','ChannelController@count');
    $this->post('/channel/create','ChannelController@create');
    $this->get('/channel/{id}/start','ChannelController@start');
    $this->get('/channel/{id}/stop','ChannelController@stop');
    $this->get('/channel/{id}/stop_record','ChannelController@stopRecord');
    $this->get('/channel/{id}/delete','ChannelController@delete');
    $this->get('/channel/{id}/countdown','ChannelController@showCountdown');
    $this->post('/channel/{id}/countdown','ChannelController@countdown');
    $this->get('/channel/{id}/guide','ChannelController@showGuide');
    $this->post('/channel/{id}/guide','ChannelController@guide');
    $this->get('/channel/{id}/background','ChannelController@showBackground');
    $this->post('/channel/{id}/background','ChannelController@background');
    $this->get('/channel/{id}/live_logo','ChannelController@showLive_logo');
    $this->post('/channel/{id}/live_logo','ChannelController@live_logo');
    $this->get('/channel/{id}/logo','ChannelController@show_logo');
    $this->get('/channel/{id}/chatroom','ChannelController@changeChatRoom');
    $this->post('/channel/{id}/logo','ChannelController@logo');

    $this->get('/material','MaterialController@index');
    $this->get('/material/{type}','MaterialController@type');
    $this->post('/material/search','MaterialController@search');

    $this->get('/company','CompanyController@index');
    $this->get('/company/{id}','CompanyController@show');
    $this->post('/company/update','CompanyController@update');
    $this->post('/company/delete','CompanyController@delete');

    $this->get('/list','ListController@index');
    $this->get('/list/{id}','ListController@show');
    $this->post('/list','ListController@store');
    $this->post('/list/{id}','ListController@update');
    $this->delete('/list/{id}','ListController@delete');

    $this->get('/set_callback','Api\VideoController@test');
});



//删除图片
$this->post('/api/rm','ChannelController@removeImage');

$this->post('/api/channel/message','Api\ChannelController@getMessage');
$this->get('/api/channel/export_message/{id}','Api\ChannelController@exportMessage');
$this->post('/api/channel/clean_message','Api\ChannelController@cleanMessage');
$this->post('/api/channel/blacklist','Api\ChannelController@blacklist');
$this->post('/api/channel/cancel_blacklist','Api\ChannelController@cancel_blacklist');

$this->post('/api/channel/config/chatroom','Api\ChannelController@chatroom');
$this->post('/api/channel/config/chatroom_any','Api\ChannelController@chatroom_any');
$this->post('/api/channel/config/chatroom_audit','Api\ChannelController@chatroom_audit');
$this->post('/api/channel/config/chatroom_danmu','Api\ChannelController@chatroom_danmu');

$this->post('/api/channel/remove','Api\ChannelController@remove');
$this->post('/api/channel/send','Api\ChannelController@send');
$this->post('/api/channel/send_danmu','Api\ChannelController@sendDanmu');
$this->post('/api/channel/sendAdmin','Api\ChannelController@sendAdmin');
$this->post('/api/channel/upload_guide/{id}','Api\ChannelController@upload_guide');
$this->post('/api/channel/upload_background/{id}','Api\ChannelController@upload_background');

$this->get('/api/video/{id}','Api\VideoController@preview');
$this->post('/api/video/upload_info/{id}/{user_id}','Api\VideoController@upload_info');
$this->post('/api/video/upload_callback','Api\VideoController@upload_callback');
$this->post('/api/video/delete','Api\VideoController@deleteVideos');

$this->get('/api/captcha/{tmp}','Api\AuthController@captcha');
$this->post('/api/verify_code','Api\AuthController@sendVerifyCode');



//开放平台授权

$this->get('/public/authorization','OpenPlatform\PublicController@authorization');
$this->get('/public/binding','OpenPlatform\PublicController@binding');
$this->post('/open/messages','OpenPlatform\OpenController@messages');
$this->get('/wechat/callback','WechatPageController@callback');
