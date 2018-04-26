<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\Api\AuthorizationsRequest;
use App\Http\Requests\Api\AuthorizationRequest;

class AuthorizationsController extends Controller
{
    public function socialStore($type, AuthorizationsRequest $request)
    {
        if (!in_array($type, ['weixin'])){
            return $this->response->errorBadRequest();
        }

        $driver = \Socialite::driver($type);

        //获取到access_token(两种方式 1.根据code获取 2.客户端自己已经获取到access_token)
        try {
            if ($code = $request->code) {
                $response = $driver->getAccessTokenResponse($code);
                $token = array_get($response, 'access_token');
            } else {
                $token = $request->access_token;

                if ($type == 'weixin') {
                    $driver->setOpenId($request->openid);
                }
            }

            //根据access_token获取用户信息
            $oauthUser = $driver->userFromToken($token);
        } catch (\Exception $e){
            return $this->response->errorUnauthorized('参数错误,未获取到用户信息');
        }
        switch ($type){
            case 'weixin':
                $unionid = $oauthUser->offsetExists('unionid') ? $oauthUser->offsetGet('unionid') : null;

                if ($unionid) {
                    $user = User::where('weixin_unionid', $unionid)->first();
                } else {
                    $user = User::where('weixin_openid', $oauthUser->getId())->first();
                }

                //如果用户不存在就创建用户
                if (!$user) {
                    $user = User::create([
                        'name' => $oauthUser->getNickname(),
                        'avatar' => $oauthUser->getAvatar(),
                        'weixin_openid' => $oauthUser->getId(),
                        'weixin_unionid' => $unionid,
                    ]);
                }
            break;
        }

        $token = \Auth::guard('api')->fromUser($user);
        return $this->respondWithToken($token);
    }

    //用户登录
    public function store(AuthorizationRequest $request)
    {
        $username = $request->username;

        filter_var($username, FILTER_VALIDATE_EMAIL)?
            $credentials['email'] = $username :
            $credentials['phone'] = $username;

        $credentials['password'] = $request->password;

        if (!$token = \Auth::guard('api')->attempt($credentials)) {
            return $this->response->errorUnauthorized('用户名或密码错误');
        }

        return $this->respondWithToken($token)->setStatusCode(201);
    }

    /**
     * 返回客户端的信息
     */
    private function respondWithToken($token)
    {
        return $this->response->array([
        'access_token' => $token,
        'token_type' => 'Bearer',
        'expires_in' => \Auth::guard('api')->factory()->getTTL() * 60
         ]);
    }

    public function update()
    {
        $token = \Auth::guard('api')->refresh();
        return $this->respondWithToken($token);
    }

    public function delete()
    {
        \Auth::guard('api')->logout();
        return $this->response->noContent();
    }
}
