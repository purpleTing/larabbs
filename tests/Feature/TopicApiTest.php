<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Traits\ActingJWTUser;

class TopicApiTest extends TestCase
{
    use ActingJWTUser;

    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }

    public function testStoreTopic()
    {
        $data = ['category_id' => 1, 'body' => 'test body', 'title' => 'test title'];

        $token = \Auth::guard('api')->fromUser($this->user);
        $response = $this->JWTActingAs($this->user)
            ->json('POST', '/api/topics', $data);

        $assertData = [
            'category_id' => 1,
            'user_id' => $this->user->id,
            'title' => 'test title',
            'body' => clean('test body', 'user_topic_body'),
        ];

        $response->assertStatus(201)
            ->assertJsonFragment($assertData);
    }

    public function testDeleteTopic()
    {
        $topic = $this->makeTopic();
        $response = $this->JWTActingAs($this->user)
            ->json('DELETE', '/api/topics/'.$topic->id);
        $response->assertStatus(204);

        $response = $this->json('GET', '/api/topics/'.$topic->id);
        $response->assertStatus(404);
    }
}
