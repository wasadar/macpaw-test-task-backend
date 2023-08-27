<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);
        $user->save();

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json(['token' => $token], Response::HTTP_CREATED);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($data)) {
            $user = Auth::user();

            $token = $user->createToken('api-token')->plainTextToken;

            return response()->json(['token' => $token], Response::HTTP_OK);
        } else {
            return response()->json(['message' => 'Invalid credentials'], Response::HTTP_UNAUTHORIZED);
        }
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        $user->tokens()->delete();

        return response()->json(['message' => 'User logged out successfully'], Response::HTTP_OK);
    }
}
