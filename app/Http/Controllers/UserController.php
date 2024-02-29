<?php

namespace App\Http\Controllers;

use App\Http\Requests\SignInRequest;
use App\Http\Requests\SignUpRequest;
use App\Http\Resources\MessageResource;
use App\Models\User;
use App\Providers\JwtServiceProvider;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function signUp(SignUpRequest $request): Response
    {
        $data = $request->validated();
        $user = new User($data);
        $user->password = Hash::make($data["password"]);
        $user->save();
        return MessageResource::success(200, "Sign Up Success", $user);
    }

    public function signIn(SignInRequest $request): Response
    {
        $credentials = $request->validated();
        if(!Auth::attempt($credentials))
        {
            throw ValidationException::withMessages(['user' => 'Wrong username or password']);
        }
        $user = Auth::user();
        return MessageResource::success(200, "Sign In Successful", [
            "id" => $user->user_id,
            "token" => JwtServiceProvider::generateToken([
                "user_id" => $user->user_id,
                "username" => $user->username
            ]),
            "type" => "bearer",
            "username" => $user->username
        ]);
    }
}
