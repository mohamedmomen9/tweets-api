<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * @SWG\Definition(
 *      definition="Follow",
 *      required={"followed_id"},
 *      @SWG\Property(
 *          property="followed_id",
 *          description="followed_id",
 *          type="integer",
 *          format="int32"
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
