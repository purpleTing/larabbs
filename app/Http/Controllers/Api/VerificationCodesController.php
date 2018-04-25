<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Overtrue\EasySms\EasySms;
use App\Http\Requests\Api\VerificationCodeRequest;

class VerificationCodesController extends Controller
{
    public function store(VerificationCodeRequest $request, EasySms $easysms)
    {
        $phone = $request->phone;

        //生成4位随机数
        $code = str_pad(random_int(1, 9999), 4, 0, STR_PAD_LEFT);
        try {
            $result = $easysms->send($phone, [
                'template' => 'SMS_133215164',
                'data' => [
                    'code'=>$code
                    ]
                ]);
            } catch (\GuzzleHttp\Exception\ClientException $exception) {
                $response = $exception->getResponse();
                $result = json_decode($response->getBody()->getContents(), true);
                return $this->response->errorInternal($result['msg'] ?? '短信发送异常');
        }
        $key = 'verificationCode_'.str_random(15);
        $expiredAt = now()->addMinutes(10);
        \Cache::put($key, ['phone' => $phone, 'code' => $code], $expiredAt);
        return $this->response->array([
            'key' => $key,
            'expired_at' => $expiredAt->toDateTimeString(),
        ])->setStatusCode(201);
    }
}
