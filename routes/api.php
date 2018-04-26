<?php

use Illuminate\Http\Request;

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Api'
], function ($api) {
    $api->group([
        'middleware' => 'api.throttle',
        'limit'      => config('api.rate_limits.sign.limit'),
        'expires'    => config('api.rate_limits.sign.expires'),
    ], function ($api) {
        //游客可以访问的接口
        // 短信验证码
        $api->post('verificationCodes', 'VerificationCodesController@store')
            ->name('api.verificationCodes.store');

        //用户注册
        $api->post('users', 'UsersController@store')
            ->name('api.users.store');

        //需要token的接口 利用中间件来验证token
        $api->group(['middleware' => 'api.auth'], function ($api) {
            $api->get('users', 'UsersControler@me')
                ->name('api.users.show');
        });

    });
});

$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Api'
], function ($api) {

    //图片验证码
    $api->post('captchas', 'CaptchasController@store')
        ->name('api.captchas.store');

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
});

