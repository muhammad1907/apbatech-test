<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function getToken(Request $request)
    {

        $username = $request->header('x-username');
        $password = $request->header('x-password');

        $credentials = [
            'username' => $username,
            'password' => $password
        ];
        
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        return response()->json([
            'response' => [
                'token' => $token
            ],
            'metadata' => [
                'message' => 'Ok',
                'code' => 200
            ]
        ]);
        
    }
}
