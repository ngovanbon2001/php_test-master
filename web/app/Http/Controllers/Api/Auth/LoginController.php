<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token ' . $e], 500);
        }
        
        return response()->json([
            'access_token' => $token,
            'refresh_token' => JWTAuth::fromUser(Auth::user())
        ]);
    }
    
    public function refresh()
    {
        $token = JWTAuth::getToken();
        
        try {
            $newToken = JWTAuth::refresh($token);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not refresh token ' . $e], 500);
        }
        
        return response()->json([
            'access_token' => $newToken,
            'refresh_token' => $token
        ]);
    }

    public function listUser()
    {
        $data = User::all()->toArray();
        return $data;
    }
}
