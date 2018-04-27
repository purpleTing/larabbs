<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Topic;
use App\Http\Requests\Api\TopicsRequest;
use App\Transformers\TopicsTransformer;

class TopicsController extends Controller
{
    public function store(TopicsRequest $request, Topic $topic)
    {
        $topic->fill($request->all());
        $topic->user_id = $this->user->id;
        $topic->save();

        return $this->response->item($topic, new TopicsTransformer())
            ->setStatusCode(201);
    }
}
