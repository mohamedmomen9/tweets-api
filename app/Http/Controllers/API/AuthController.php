<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Http\Controllers\AppBaseController;

class AuthController extends AppBaseController
{
    /**
     * @param  CreateRegisterApi $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/register",
     *      summary="Register new user",
     *      tags={"Register"},
     *      description="Create new user",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Register new user data",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Register")
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
     *                  ref="#/definitions/Register"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function register(Request $request)
    {
        $email = User::where('email',$request->email)->first()->get();
        if(count($email)){
            return $this->sendError('User already exist!');
        }
        return $request->all();
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);
        if (isset($request->image)) {
            $imageName = $request->email . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
        } else {
            $imageName = null;
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request['password']),
            'image' => $imageName,
        ]);
        return response()->json($user);
    }


    /**
     * @param  CreateLoginApi $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/login",
     *      summary="login",
     *      tags={"Register"},
     *      description="login",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Register new user data",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Login")
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
     *                  ref="#/definitions/Login"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        $credentials = request(['email', 'password']);
        if (!auth()->attempt($credentials)) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'password' => [
                        'Invalid credentials'
                    ],
                ]
            ], 422);
        }

        $user = User::where('email', $request->email)->first();
        $authToken = $user->createToken($user->name)->plainTextToken;

        return response()->json([
            'access_token' => $authToken,
        ]);
    }
}
