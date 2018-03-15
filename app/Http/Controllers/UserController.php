<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;
// use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
// use Illuminate\Foundation\Auth\RegistersUsers;
// use Illuminate\Http\Request;

class UserController extends Controller
{
    // By this moment, ignore this shit
    // use RegistersUsers;

    // public function __construct()
    // {
    //     $this->middleware('guest');
    // }

    protected function validateNew(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            // 'avatar' => 'string|max:255',
            'username' => 'required|string|max:17|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    protected function validateUpdate(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            // 'avatar' => 'string|max:255',
            'username' => 'required|string|max:17',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    public function register()
    {
        $validator = $this->validateNew(request()->all());

        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->messages()
            ], 401);
        }

        $user = User::create([
            'name' => request('name'),
            'email' => request('email'),
            'avatar' => "https://randomuser.me/api/portraits/men/".rand(0, 99).".jpg",
            'username' => request('username'),
            'password' => Hash::make(request('password')),
        ]);

        return \App\Http\Controllers\AuthController::respondWithToken(auth()->login($user));
    }

    public function edit()
    {
        $id = auth()->user()['id'];

        if (!$id) {
            return response()->json([
                "message" => "Unauthorized",
            ], 401);
        }

        $validator = $this->validateUpdate(request()->all());

        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->messages(),
            ], 401);
        }

        $user = User::findOrFail($id);
        $user->name = request('name');
        $user->email = request('email');
        $user->username = request('username');
        $user->password = Hash::make(request('password'));
        $user->save();

        return response()->json([
            "message" => "Success",
            "data" => [
                "user" => $user
            ],
        ], 200);
    }

    public function delete()
    {
        $id = auth()->user()['id'];

        if (!$id) {
            return response()->json([
                "message" => "Unauthorized",
            ], 401);
        }

        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json([
                'message' => 'Unauthorized',
                'data' => [
                    'details' => 'Try again, credentials are wrong...',
                ],
            ], 401);
        }

        $user = User::find($id);
        $user->delete();

        auth()->logout();

        return response()->json([
            'message' => 'Success',
            'data' => [
                'details' => 'Deleted completely, It must have a 14 days time but okay :D',
            ],
        ]);
    }
}
