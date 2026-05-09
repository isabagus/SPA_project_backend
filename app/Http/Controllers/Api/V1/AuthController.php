<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Handle user login and return a Sanctum token.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);


        $user = User::where('email', $request->email)->first();
        if (!$user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau Password salah.'
            ], 401);
        }

        $user->tokens()->delete();

        $token = $user->createToken('nextjs_auth_token')->plainTextToken;
                
        return response()->json([
            'success' => true,
            'message' => 'Login success',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'id' => $user->user_id,
                'username' => $user->username,
                'email' => $user->email,
                'role' => $user->role,
            ]
        ]);
    }


    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }
}
