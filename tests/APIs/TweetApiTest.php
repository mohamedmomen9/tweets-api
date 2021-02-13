<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Tweet;

class TweetApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_tweet()
    {
        $tweet = factory(Tweet::class)->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/tweets', $tweet
        );

        $this->assertApiResponse($tweet);
    }

    /**
     * @test
     */
    public function test_read_tweet()
    {
        $tweet = factory(Tweet::class)->create();

        $this->response = $this->json(
            'GET',
            '/api/tweets/'.$tweet->id
        );

        $this->assertApiResponse($tweet->toArray());
    }

    /**
     * @test
     */
    public function test_update_tweet()
    {
        $tweet = factory(Tweet::class)->create();
        $editedTweet = factory(Tweet::class)->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/tweets/'.$tweet->id,
            $editedTweet
        );

        $this->assertApiResponse($editedTweet);
    }

    /**
     * @test
     */
    public function test_delete_tweet()
    {
        $tweet = factory(Tweet::class)->create();

        $this->response = $this->json(
            'DELETE',
             '/api/tweets/'.$tweet->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/tweets/'.$tweet->id
        );

        $this->response->assertStatus(404);
    }
}
