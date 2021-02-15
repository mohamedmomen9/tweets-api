<?php


use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Follower;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class FollowApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, RefreshDatabase;

    /**
     * @test
     */
    public function test_view_followers()
    {
        $user = factory(User::class)->create();
        Sanctum::actingAs($user, ['*']);
        $follow = factory(Follower::class)->make()->toArray();
        $this->response = $this->json(
            'POST',
            '/api/v1/follow',
            $follow
        );
        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/v1/follow'
        );
        $this->assertApiSuccess($this->response['data']);
    }

    /**
     * @test
     */
    public function test_follow_user()
    {
        $user = factory(User::class)->create();
        Sanctum::actingAs($user, ['*']);
        $follow = factory(Follower::class)->make()->toArray();
        $this->response = $this->json(
            'POST',
            '/api/v1/follow',
            $follow
        );
        $this->assertApiSuccess($follow);
    }

    /**
     * @test
     */
    public function test_unfollow_user()
    {
        $user = factory(User::class)->create();
        Sanctum::actingAs($user, ['*']);
        $follow = factory(Follower::class)->make()->toArray();
        $this->response = $this->json(
            'POST',
            '/api/v1/follow',
            $follow
        );
        $this->assertApiSuccess();
        $this->response = $this->json(
            'DELETE',
            '/api/v1/unfollow/' . $this->response['data']['followed_id']
        );
        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/v1/follow'
        );
        $this->assertApiSuccess($this->response['data']);
    }
}
