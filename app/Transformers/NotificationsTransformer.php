<?php

namespace App\Transformers;


use League\Fractal\TransformerAbstract;
use Illuminate\Notifications\DatabaseNotification;

class NotificationsTransformer extends TransformerAbstract
{
    public function transform(DatabaseNotification $notification)
    {
        return [
            'id'         => $notification->id,
            'type'       => $notification->type,
            'data'       => $notification->data,
            'read_at'    => $notification->read_at ? $notification->read_at->toDateTimeString():null,
            'created_at' => $notification->created_at->toDateTimeString(),
            'created_at' => $notification->created_at->toDateTimeString(),
        ];
    }
}