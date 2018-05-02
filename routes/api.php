<?php

use Illuminate\Http\Request;

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Api'
], function ($api) {
    $api->group([
//        'middleware' => 'api.throttle',
//        'limit'      => config('api.rate_limits.sign.limit'),
//        'expires'    => config('api.rate_limits.sign.expires'),
    ], function ($api) {
        //游客可以访问的接口


        //用户注册
        $api->post('users', 'UsersController@store')
            ->name('api.users.store');
        //需要token的接口 利用中间件来验证token
//        $api->group(['middleware' => 'api.auth'], function ($api) {
//            $api->get('user', 'UsersController@me')
//                ->name('api.user.show');
//        });

    });
});


$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Api',
    'middleware' => ['serializer:array', 'bindings', 'change-locale']
], function ($api) {

    //图片验证码
    $api->post('captchas', 'CaptchasController@store')
        ->name('api.captchas.store');

    // 短信验证码
    $api->post('verificationCodes', 'VerificationCodesController@store')
        ->name('api.verificationCodes.store');

    //第三方登录
    $api->post('socials/{social_type}/authorizations', 'AuthorizationsController@socialStore')
        ->name('api.socials.authorizations.store');

    //登录
    $api->post('authorizations', 'AuthorizationsController@store')
        ->name('api.authorizations.store');

    //刷新token
    $api->put('authorizations/current', 'AuthorizationsController@update')
        ->name('api.authorizations.current.update');

    //删除token
    $api->delete('authorizations/current', 'AuthorizationsController@delete')
        ->name('api.authorizations.current.delete');

    //话题分类列表(游客可以访问 不需要验证token)
    $api->get('categories', 'CategoriesController@index')
        ->name('api.categories.index');

    //话题列表
    $api->get('topics', 'TopicsController@index')
        ->name('api.topics.index');

    //话题详情
    $api->get('topics/{topic}', 'TopicsController@show')
        ->name('api.topics.show');

    //某个用户发表所有的话题
    $api->get('users/{user}/topics', 'TopicsController@userIndex')
        ->name('api.users.topics.userIndex');

    //推荐资源
    $api->get('links', 'LinksController@index')
        ->name('api.links.index');

    //活跃用户
    $api->get('actived/users', 'UsersController@activedIndex')
        ->name('api.actived.users.activedIndex');

    //需要验证token的接口
        $api->group(['middleware' => 'api.auth'], function($api) {

            // 当前登录用户信息
            $api->get('user', 'UsersController@me')
                ->name('api.user.show');

            //编辑登录用户的信息
            $api->patch('user', 'UsersController@update')
                ->name('api.user.update');

            //图片资源
            $api->post('images', 'ImagesController@store')
                ->name('api.images.store');

            //发布话题
            $api->post('topics', 'TopicsController@store')
                ->name('api.topics.store');

                //修改话题
            $api->patch('topics/{topic}', 'TopicsController@update')
                ->name('api.topics.update');
            });

            $api->delete('topics/{topic}', 'TopicsController@destroy')
                ->name('api.topics.destroy');

            $api->post('topics/{topic}/replies', 'RepliesController@store')
                ->name('api.topics.replies.store');

            //删除某个话题下的某个回复
            $api->delete('topics/{topics}/reply/{reply}', 'RepliesController@destroy')
                ->name('api.topics.reply.destroy');

            //话题的回复列表
            $api->get('topics/{topic}/replies', 'RepliesController@index')
                ->name('api.topics.replies.index');

            //某个用户的回复列表
            $api->get('users/{user}/replies', 'RepliesController@userIndex')
                ->name('api.users.replies.userIndex');

            //查看自己收到的通知
            $api->get('user/notifications', 'NotificationsController@index')
                ->name('api.user.notifications.index');

            //查看未读的消息
            $api->get('user/notifications/stats', 'NotificationsController@stats')
                ->name('api.user.notifications.stats');

            // 标记消息通知为已读
            $api->patch('user/read/notifications', 'NotificationsController@read')
                ->name('api.user.notifications.read');

            //当前登录用户的权限
            $api->get('user/permissions', 'PermissionsController@index')
                ->name('api.user.permissions.index');

});

