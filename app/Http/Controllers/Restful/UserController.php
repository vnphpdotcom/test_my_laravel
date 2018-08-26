<?php

namespace App\Http\Controllers\Restful;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    //
    use AuthenticatesUsers;

    public function Login(Request $user)
    {
        //Auth::logout();
        $login = [ 'name' => $user->input('username'), 'password' => $user->input('password') ];
        try {
            if (Auth::attempt($login,true)) {
                $user = Auth::user();
                $user['token'] =  $user->createToken(md5(time()))-> accessToken;
                return response()->json(['success' => $user], 200);
            }
            else
            {
                return response()->json(['error'=>'Unauthorised'], 401);
            }
        } catch (\Exception $e) {
            return response()->json(['error'=>'Fail to create token'], 500);
        }

    }

    public function Logged()
    {
        if(Auth::check())
        {
            $user = Auth::user();
            $user['token'] =  $user->createToken(md5(time()))-> accessToken;
            return response()->json(['success' => $user], 200);
        }
        else
        {
            return response()->json(['error'=>'Token expired'], 401);
        }

    }

    public function Logout()
    {
        if(Auth::check())
        {
            Auth::guard('web')->logout();
        }
        return response()->json(['success'=>'Logout successfully'], 200);
    }
}
