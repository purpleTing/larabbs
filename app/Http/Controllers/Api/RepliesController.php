<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\Api\RepliesRequest;
use App\Models\Reply;
use App\Models\Topic;
use App\Transformers\RepliesTransformer;

class RepliesController extends Controller
{
    public function store(RepliesRequest $request, Reply $reply, Topic $topic)
    {
        $reply->user_id  = $this->user->id;
        $reply->topic_id = $topic->id;
        $reply->content = $request->content;
        $reply->save();

        return $this->response->item($reply, new RepliesTransformer())
            ->setStatusCode(201);
    }
}
