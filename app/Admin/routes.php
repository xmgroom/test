<?php

use Illuminate\Routing\Router;

Admin::registerHelpersRoutes();

Route::group([
    'prefix'        => config('admin.prefix'),
    'namespace'     => Admin::controllerNamespace(),
    'middleware'    => ['web', 'admin'],
], function (Router $router) {

    $router->get('/', 'HomeController@index');
    $router->resource('users', UserController::class);
    $router->resource('wechat_users', WeChatUserController::class);
    $router->resource('channels', ChannelController::class);
    $router->resource('company', CompanyController::class);
    $router->resource('user_count',UserCountController::class);
    $router->resource('channel_count',ChannelCountController::class);
    $router->resource('background', BackGroundController::class);
    $router->resource('guide', GuideController::class);
    $router->resource('video', VideoController::class);
    $router->resource('combos', CombosController::class);

});
