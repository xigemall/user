<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PassportController extends Controller
{
    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ];
        if (Auth::guard('web')->attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('myApp')->accessToken;
            $data['token'] = $token;
            return response()->json($data, 201);
        }
        return response()->json(['error' => 'Unauthorised'], 401);
    }

    public function register(Request $request)
    {
        $message = [
            'name' => '名称',
            'email' => '邮箱',
            'password' => '密码',
        ];
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:5|confirmed',
        ], [], $message);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        try {
            $request->offsetSet('password', bcrypt($request->input('password')));
            $user = User::create($request->all());
            $accessToken = $user->createToken('myApp')->accessToken;
            $user->token = $accessToken;
            return response()->json($user, 201);
        } catch (\Exception $e) {
            return response($e->getMessage(), 400);
        }
    }
}
