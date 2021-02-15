<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Follower;
use App\Http\Resources\FollowResource;
use App\Http\Controllers\AppBaseController;
use App\Models\Tweet;
use App\User;

class FollowerAPIController extends AppBaseController
{
    /**
     * @param CreateFollowAPIRequest $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/follow",
     *      summary="View all followed users",
     *      tags={"Follow"},
     *      description="All followed users",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="Authorization",
     *          in="header",
     *          description="Bearer token",
     *          required=true,
     *          type="string",
     *          @SWG\Schema(ref="#/definitions/Follow")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Tweet"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function index()
    {
        $id = Auth::id();
        $following = Follower::select('followed_id')->where('follower_id', $id)->get();
        $followingIds = [];
        foreach ($following as $followedId) {
            array_push($followingIds, $followedId->followed_id);
        }
        $followed = User::whereIn('id', $followingIds)->get();
        return $this->sendResponse($followed, 'successfully followed');
    }

    /**
     * @param CreateFollowerAPIController $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/follow",
     *      summary="Follow user using id",
     *      tags={"Follow"},
     *      description="Store Follow",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="Authorization",
     *          in="header",
     *          description="Bearer token",
     *          required=true,
     *          type="string",
     *          @SWG\Schema(ref="#/definitions/Follow")
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="User that would be followed",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Follow")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Follow"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function follow(Request $request)
    {
        $id = Auth::id();
        if ($id == $request->followed_id || !count(User::where('id', $request->followed_id)->get())) {
            return $this->sendError('user does not exist');
        }
        $input = ['follower_id' => $id, 'followed_id' => $request->followed_id];
        $followed = Follower::where('follower_id', $id)->where('followed_id', $request->followed_id)->get();
        if (count($followed)) {
            return $this->sendError('already following this user');
        }
        /** @var Follower $follow */

        $follow = Follower::create($input);
        return $this->sendResponse(new FollowResource($follow), 'successfully followed');
    }

    /**
     * @param DeleteFollowerAPIController $request
     * @return Response
     *
     * @SWG\Delete(
     *      path="/unfollow/{id}",
     *      summary="Unfollow user using id",
     *      tags={"Follow"},
     *      description="Store Follow",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="Authorization",
     *          in="header",
     *          description="Bearer token",
     *          required=true,
     *          type="string",
     *          @SWG\Schema(ref="#/definitions/Follow")
     *      ),
     *      @SWG\Parameter(
     *          name="id",
     *          in="path",
     *          type="string",
     *          required=true,
     *          @SWG\Schema(ref="#/definitions/Follow")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Follow"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function unfollow($id)
    {

        $followed = Follower::where('followed_id', $id);
        if (empty($followed)) {
            return $this->sendError('User not found');
        }
        $followed->delete();

        return $this->sendSuccess('Unfollowed successfully');
    }
}
