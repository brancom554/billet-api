<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Session;

class UserLogoutApiController extends ApiBaseController
{

    protected $auth;

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Log a user out
     *
     * @return mixed
     */
    public function doLogout()
    {
        
        $user = $this->auth->user();
        $user->token = null;
        $user->save();
        Session::flush();
        $this->auth->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }
   
}
