<?php

namespace App\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    public function transform(User $user)
    {
        return [
            'id'               => $user->id,
            'name'             => $user->name,
            'email'            => $user->email,
            'avatar'           => $user->avatar,
            'introduction'     => $user->introduction,
            'bound_phone'      => $user->phone ? true : false,
            'bound_weixin'     => ($user->weixin_openid || $user->weixin_unionid) ? true : false,
            'last_acticied_at' => $user->last_acticied_at->toDateTimeString(),
            'created_at'       => $user->created_at->toDateTimeString(),
            'updated_at'       => $user->updated_at->toDateTimeString(),
        ];
    }
}