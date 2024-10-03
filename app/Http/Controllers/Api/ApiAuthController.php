<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ApiAuthController extends Controller
{
    public function me()
    {
        return response()->json(auth()->user());
    }

    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if (!$user->is_active) {
                return response()->json(['error' => 'User is not active'], 401);
            }

            $accessToken = $user->createToken('AuthToken')->accessToken;

            return response()->json(['access_token' => $accessToken]);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function getAllTokens(Request $request)
    {
        $tokens = $request->user()->tokens->map(function ($token) {
            return [
                'id' => $token->id,
                'name' => $token->name,
                'last_used_at' => $token->last_used_at,
            ];
        });

        return response()->json(['tokens' => $tokens]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens->each(function ($token) {
            $token->delete();
        });

        return response()->json(['message' => 'Logged out']);
    }
}
