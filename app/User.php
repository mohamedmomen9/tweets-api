<?php

namespace App;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
    /**
     * @SWG\Definition(
     *      definition="Login",
     *      required={"email","password"},
     *      @SWG\Property(
     *          property="email",
     *          description="user email",
     *          type="string",
     *          format="email"
     *      ),
     *      @SWG\Property(
     *          property="password",
     *          description="password",
     *          type="string",
     *          format="string"
     *      )
     * )
     *
     * @SWG\Definition(
     *      definition="Register",
     *      required={"email","name","password","confirm_password"},
     *      @SWG\Property(
     *          property="name",
     *          description="user name",
     *          type="string",
     *          format="string"
     *      ),
     *      @SWG\Property(
     *          property="email",
     *          description="user email",
     *          type="string",
     *          format="email"
     *      ),
     *      @SWG\Property(
     *          property="password",
     *          description="password",
     *          type="string",
     *          format="password"
     *      ),
     *      @SWG\Property(
     *          property="confirm_password",
     *          description="password",
     *          type="string",
     *          format="string"
     *      ),
     *      @SWG\Property(
     *          property="image",
     *          description="upload an image",
     *          type="file",
     *          format="binary"
     *      )
     * )
     */

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'remember_token', 'image'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
