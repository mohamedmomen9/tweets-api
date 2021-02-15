<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateTweetAPIRequest;
use App\Http\Requests\API\UpdateTweetAPIRequest;
use App\Models\Tweet;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\TweetResource;
use Response;
use Illuminate\Support\Facades\Auth;
use App\Models\Follower;

/**
 * Class TweetController
 * @package App\Http\Controllers\API
 */

class TweetAPIController extends AppBaseController
{
    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/tweets",
     *      summary="Get a listing of the Tweets based on followers.",
     *      tags={"Tweet"},
     *      description="Get all Tweets based on followers",
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
     *                  type="array",
     *                  @SWG\Items(ref="#/definitions/Tweet")
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
        $followingIds = [$id];
        foreach ($following as $follow) {
            array_push($followingIds, $follow->followed_id);
        }
        $tweets = Tweet::whereIn('user', $followingIds)->get();
        return $this->sendResponse(TweetResource::collection($tweets), 'Tweets retrieved successfully');
    }

    /**
     * @param CreateTweetAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/tweets",
     *      summary="Store a newly created Tweet in storage",
     *      tags={"Tweet"},
     *      description="Store Tweet",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="Authorization",
     *          in="header",
     *          description="Bearer token",
     *          required=true,
     *          type="string",
     *          @SWG\Schema(ref="#/definitions/Tweet")
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Tweet that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Tweet")
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
    public function store(CreateTweetAPIRequest $request)
    {
        $input = $request->all();
        $input['user'] = Auth::id();
        /** @var Tweet $tweet */
        $tweet = Tweet::create($input);

        return $this->sendResponse(new TweetResource($tweet), 'Tweet saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/tweets/{id}",
     *      summary="Display the specified Tweet",
     *      tags={"Tweet"},
     *      description="Get Tweet",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="Authorization",
     *          in="header",
     *          description="Bearer token",
     *          required=true,
     *          type="string",
     *          @SWG\Schema(ref="#/definitions/Tweet")
     *      ),
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Tweet",
     *          type="integer",
     *          required=true,
     *          in="path"
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
    public function show($id)
    {
        /** @var Tweet $tweet */
        $tweet = Tweet::find($id);

        if (empty($tweet)) {
            return $this->sendError('Tweet not found');
        }

        return $this->sendResponse(new TweetResource($tweet), 'Tweet retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateTweetAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/tweets/{id}",
     *      summary="Update the specified Tweet in storage",
     *      tags={"Tweet"},
     *      description="Update Tweet",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="Authorization",
     *          in="header",
     *          description="Bearer token",
     *          required=true,
     *          type="string",
     *          @SWG\Schema(ref="#/definitions/Tweet")
     *      ),
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Tweet",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Tweet that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Tweet")
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
    public function update($id, UpdateTweetAPIRequest $request)
    {
        /** @var Tweet $tweet */
        $tweet = Tweet::find($id);

        if (empty($tweet)) {
            return $this->sendError('Tweet not found');
        }

        $tweet->fill($request->all());
        $tweet->save();

        return $this->sendResponse(new TweetResource($tweet), 'Tweet updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/tweets/{id}",
     *      summary="Remove the specified Tweet from storage",
     *      tags={"Tweet"},
     *      description="Delete Tweet",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="Authorization",
     *          in="header",
     *          description="Bearer token",
     *          required=true,
     *          type="string",
     *          @SWG\Schema(ref="#/definitions/Tweet")
     *      ),
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Tweet",
     *          type="integer",
     *          required=true,
     *          in="path"
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
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function destroy($id)
    {
        /** @var Tweet $tweet */
        $tweet = Tweet::find($id);

        if (empty($tweet)) {
            return $this->sendError('Tweet not found');
        }

        $tweet->delete();

        return $this->sendSuccess('Tweet deleted successfully');
    }
}
