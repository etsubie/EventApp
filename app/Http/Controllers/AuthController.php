<?php

namespace App\Http\Controllers;

use App\Models\User;
use Hash;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            "name" => "required|max:255",
            "email" => "required|email|unique:users",
            "password" => "required|confirmed",
            // "role"=> "required"
        ]);
        $user = User::create($data);
        // Sync Spatie's roles with the role column
        $user->assignRole('host');
        $token = $user->createToken($request->name);
        // return [
        //     'user' =>$user,
        //     'token' => $token->plainTextToken,
        //     'role'=> $user->getRoleNames()
        // ];
        return [
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->roles[0]->name
            ],
            'token' => $token->plainTextToken,
        ];
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            "email" => "required|exists:users",
            "password" => "required"
        ]);
        $user = User::where("email", $request->email)->first();
        $token = $user->createToken($user->name);

        if (!$user || !Hash::check($request->password, $user->password)) {
            return [
                "errors" => [
                    "email" => ["Invalid Credentials"]
                ]
            ];
        }

        return [
            'user' => $user,
            'token' => $token->plainTextToken
        ];
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return ['message' => 'You are Logged out'];
    }
}