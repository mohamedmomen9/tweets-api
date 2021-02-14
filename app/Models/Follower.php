<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * @SWG\Definition(
 *      definition="Follow",
 *      required={"followed"},
 *      @SWG\Property(
 *          property="follower_id",
 *          description="follower_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="followed_id",
 *          description="followed_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="created_at",
 *          description="created_at",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="updated_at",
 *          description="updated_at",
 *          type="string",
 *          format="date-time"
 *      )
 * )
 */
class Follower extends Model
{
    public $table = 'followers';
    public $fillable = [
        'follower_id','followed_id'
    ];

    // protected $casts = [
    //     'follower_id' => 'integer',
    //     'followed_id' => 'integer',
    // ];

    public static $rules = [
        'follower_id' => 'required',
        'followed_id' => 'required',
    ];
}
