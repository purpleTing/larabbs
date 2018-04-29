<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\Api\RepliesRequest;
use App\Models\Reply;
use App\Models\Topic;
use App\Models\User;
use App\Transformers\RepliesTransformer;

class RepliesController extends Controller
{
    public function store(RepliesRequest $request, Reply $reply, Topic $topic)
    {
        $reply->user_id  = $this->user->id;
        $reply->topic_id = $topic->id;
//        $reply->content = $request->content;
        $reply->save();

        return $this->response->item($reply, new RepliesTransformer())
            ->setStatusCode(201);
    }

    public function destroy(Topic $topic, Reply $reply)
    {
        if ($reply->topic_id != $topic->id) {
            return $this->response->errorBadRequest();
        }

        $this->authorize('destroy', $reply);
        $reply->delete();

        return $this->response->noContent();
    }

    public function index(Topic $topic)
    {
        $replies = $topic->replies()->paginate(20);

        return $this->response->paginator($replies, new RepliesTransformer());
    }

    public function userIndex(Reply $reply, User $user)
    {
        $replies = $user->replies()->paginate(20);

        return $this->response->paginator($replies, new RepliesTransformer());
    }
}
