<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\ApiBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends ApiBaseController
{
    public function token(Request $request)
    {
        $credentials = $request->only('login', 'password');

        if (!Auth::attempt($credentials)) {
            return $this->response(error: 'Invalid credentials', status: 401);
        }

        $token = $request->user()->createToken('api_token')->plainTextToken;

        return $this->response(['token' => $token]);
    }
}
