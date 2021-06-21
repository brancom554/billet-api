<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use JWTAuth;  
use Exception;
use App\Models\User;



class UserLoginApiController extends ApiBaseController
{
    protected $captchaService;

    public function __construct()
    {
        $captchaConfig = config('attendize.captcha');
        if ($captchaConfig["captcha_is_on"]) {
            $this->captchaService = Factory::create($captchaConfig);
        }

        $this->middleware(['guest','api']);

    }

    /**
     * Handles the login request.
     *
     * @param  Request  $request
     *
     * @return mixed
     */
    public function postLogin(Request $request, User $user)
    {

        $credentials = request(['email', 'password']);

        $validator = Validator::make($credentials, [
                'email' => 'required',
                'password' => 'required|string|min:6',
            ]);

            if ($validator->fails()) {
                    return response()->json($validator->errors(), 422);
                }

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if($token = JWTAuth::attempt($credentials)){
            try{

                $user = auth()->user();
                $user->update([
                'token' => $token
            ]);

            return $this->respondWithToken($token);
            
            }
            catch (Exception $e){
                if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                   
                    return response()->json([
                        'data' => null,
                        'status' => false,
                        'err_' => [
                            'message' => 'Token Expired',
                            'code' =>1
                            ]
                        ]
                    );
                }
            }

        } 
        // return redirect()->intended(route('showSelectOrganiser'));
     }

     protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
