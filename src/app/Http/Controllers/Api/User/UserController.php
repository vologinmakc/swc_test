<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Api\ApiBaseController;
use App\Http\Requests\Api\User\RegisterUserRequest;
use App\Models\User;
use App\Services\Dto\User\UserDto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends ApiBaseController
{
    public function register(RegisterUserRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = User::create(new UserDto($request->validationData()));
            Auth::login($user);
            $token = $user->createToken('api_token')->plainTextToken;
        } catch (\Throwable $exception) {
            DB::rollBack();
            $this->response(error: $exception, status: 500);
        }

        DB::commit();

        return $this->response(['token' => $token]);
    }

    public function me()
    {
        $user = Auth::user();

        return $this->response(['user' => $user]);
    }
}
