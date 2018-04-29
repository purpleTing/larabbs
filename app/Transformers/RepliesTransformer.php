<?php

namespace App\Transformers;

use App\Models\Reply;
use League\Fractal\TransformerAbstract;

class RepliesTransformer extends TransformerAbstract
{
    public function transform(Reply $reply)
    {
        return [
           'id' => $reply->id,
           'topic_id' => $reply->topic_id,
           'user_id' => $reply->user_id,
           'content' => $reply->content,
           'created_at' => $reply->created_at->toDateTimeString(),
           'updated_at' => $reply->updated_at->toDateTimeString(),
        ];
    }
}