<?php

namespace App\Transformers;

use App\Models\Topic;
use App\Models\User;
use League\Fractal\TransformerAbstract;

class TopicsTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['user', 'category', 'role'];

    public function transform(Topic $topic)
    {
        return [
            'id'                 => $topic->id,
            'title'              => $topic->title,
            'body'               => $topic->body,
            'user_id'            => $topic->user_id,
            'category_id'        => $topic->category_id,
            'reply_count'        => (int)$topic->reply_count,
            'view_count'         => (int)$topic->view_count,
            'last_reply_user_id' => (int)$topic->last_reply_user_id,
            'excerpt'            => $topic->excerpt,
            'slug'               => $topic->slug,
//            'created_at'         => $topic->created_at->toDateTimeString(),
//            'updated_at'         => $topic->updated_at->toDateTimeString(),
        ];
    }

    public function includeUser(Topic $topic)
    {
        return $this->item($topic->user, new UserTransformer());
    }

    public function includeCategory(Topic $topic)
    {
        return $this->item($topic->category, new CategoriesTransformer());
    }

    public function includeRole(Topic $topic)
    {
        return $this->collection($topic->user->roles, new RoleTransformer());
    }
}