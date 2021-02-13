<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @SWG\Definition(
 *      definition="Tweet",
 *      required={"text_en", "text_ar"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="text_en",
 *          description="text_en",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="text_ar",
 *          description="text_ar",
 *          type="string"
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
class Tweet extends Model
{
    use SoftDeletes;

    public $table = 'tweets';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'text_en',
        'text_ar'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'text_en' => 'string',
        'text_ar' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'text_en' => 'required|max140',
        'text_ar' => 'required|140'
    ];

    
}
