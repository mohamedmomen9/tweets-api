<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Tweet;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class TweetApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, RefreshDatabase;

    /**
     * @test
     */
    public function test_create_tweet()
    {
        $user = factory(User::class)->create();
        Sanctum::actingAs($user,['*']);
        $tweet = factory(Tweet::class)->make()->toArray();
        $this->response = $this->json(
            'POST',
            '/api/v1/tweets', $tweet
        );

        $this->assertApiResponse($tweet);
    }

    /**
     * @test
     */
    public function test_read_tweet()
    {
        $user = factory(User::class)->create();
        Sanctum::actingAs($user,['*']);
        $tweet = factory(Tweet::class)->create();

        $this->response = $this->json(
            'GET',
            '/api/v1/tweets/'.$tweet->id
        );

        $this->assertApiResponse($tweet->toArray());
    }

    /**
     * @test
     */
    public function test_update_tweet()
    {
        $user = factory(User::class)->create();
        Sanctum::actingAs($user,['*']);
        $tweet = factory(Tweet::class)->create();
        $editedTweet = factory(Tweet::class)->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/v1/tweets/'.$tweet->id,
            $editedTweet
        );

        $this->assertApiResponse($editedTweet);
    }

    /**
     * @test
     */
    public function test_delete_tweet()
    {
        $user = factory(User::class)->create();
        Sanctum::actingAs($user,['*']);
        $tweet = factory(Tweet::class)->create();

        $this->response = $this->json(
            'DELETE',
             '/api/v1/tweets/'.$tweet->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/v1/tweets/'.$tweet->id
        );

        $this->response->assertStatus(404);
    }
}
