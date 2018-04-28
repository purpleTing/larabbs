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
    'middleware' => ['serializer:array', 'bindings']
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

    $api->get('topics', 'TopicsController@index')
        ->name('api.topics.index');

    //某个用户发表所有的话题
    $api->get('users/{user}/topics', 'TopicsController@userIndex')
        ->name('api.users.topics.userIndex');

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

});

