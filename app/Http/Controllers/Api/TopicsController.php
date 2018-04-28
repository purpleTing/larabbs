<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Topic;
use App\Models\User;
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

    public function update(TopicsRequest $request, Topic $topic)
    {
        $this->authorize('update', $topic);
        $topic->update($request->all());

        return $this->response->item($topic, new TopicsTransformer());
    }

    public function destroy(Topic $topic)
    {
        $this->authorize('update', $topic);
        $topic->delete();

        return $this->response->noContent();
    }

    public function index(Request $request, Topic $topic)
    {
        $query = $topic->query();

        if ($category_id = $request->category_id){
            $query->where('category', $category_id);
        }

        switch($request->order){
            case 'recent':
                $query->recent();
                break;
            default:
                $query->recentReplied();
                break;
        }
        $topics = $query->paginate(20);
        return $this->response->paginator($topics, new TopicsTransformer());
    }

    public function userIndex(Request $request, User $user)
    {
        $topics = $user->topics()->recent()
            ->paginate(20);

        return $this->response->item($topics, new TopicsTransformer());
    }
}
