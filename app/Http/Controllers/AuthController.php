<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['message' => 'Login failed, you are unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function me()
    {
        return response()->json([
            "data" => [
                "user" => auth()->user(),
            ],
        ]);
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(["message" => "Success"]);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    static function respondWithToken($token)
    {
        return response()->json([
            "message" => "Success",
            "data" => [
                "access_token" => $token,
                "token_type" => "bearer",
                "expires_in" => auth()->factory()->getTTL() * 60
            ],
        ]);
    }
}
