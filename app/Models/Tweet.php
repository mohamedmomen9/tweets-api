<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @SWG\Definition(
 *      definition="Tweet",
 *      required={"text_en", "text_ar"},
 *      @SWG\Property(
 *          property="text_en",
 *          description="text_en",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="text_ar",
 *          description="text_ar",
 *          type="string"
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
        'text_ar',
        'user'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'text_en' => 'string',
        'text_ar' => 'string',
        'user'=>'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'text_en' => 'required|max:140',
        'text_ar' => 'required|max:140'
    ];


}
