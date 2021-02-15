<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;

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
     *      consumes={"multipart/form-data"},
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="name",
     *          in="formData",
     *          type="string",
     *          required=true,
     *          @SWG\Schema(ref="#/definitions/Register")
     *      ),
     *      @SWG\Parameter(
     *          name="email",
     *          in="formData",
     *          type="string",
     *          required=true,
     *          @SWG\Schema(ref="#/definitions/Register")
     *      ),
     *      @SWG\Parameter(
     *          name="password",
     *          in="formData",
     *          type="string",
     *          required=true,
     *          @SWG\Schema(ref="#/definitions/Register")
     *      ),
     *      @SWG\Parameter(
     *          name="confirm-password",
     *          in="formData",
     *          type="string",
     *          required=true,
     *          @SWG\Schema(ref="#/definitions/Register")
     *      ),
     *      @SWG\Parameter(
     *          name="image",
     *          in="formData",
     *          description="Upload an image",
     *          type="file",
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
        $email = User::where('email', $request->email)->first();
        if (!empty($email)) {
            return $this->sendError('User already exist!');
        }
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
     *          name="email",
     *          in="formData",
     *          type="string",
     *          required=true,
     *          @SWG\Schema(ref="#/definitions/Login")
     *      ),
     *      @SWG\Parameter(
     *          name="password",
     *          in="formData",
     *          type="string",
     *          required=true,
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



    /**
     * @param CreateLogoutApi $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/logout",
     *      summary="Logout",
     *      tags={"Register"},
     *      description="Logout",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="Authorization",
     *          in="header",
     *          description="Bearer token",
     *          required=true,
     *          type="string",
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
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function logout()
    {
        return Auth::id();
        if (Auth::id()) {
            $user = Auth::user();
            $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();
            return $this->sendSuccess('Successfully logged out');
        }
        return $this->sendError('User not logged in');
    }
}
