<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Handle user login using SPA Stateful Authentication.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::guard('web')->attempt($request->only('email', 'password'))) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau Password salah.'
            ], 401);
        }

        $request->session()->regenerate();
        $user = Auth::guard('web')->user();
                
        return response()->json([
            'success' => true,
            'message' => 'Login success',
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
        Auth::guard('web')->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }

    /**
     * Get the authenticated User with their specific role profile.
     */
    public function me(Request $request)
    {
        $user = $request->user();
        
        // Load the relationship based on the user's role
        $profile = null;
        if ($user->role === 'teacher') {
            $profile = $user->teacher;
        } elseif ($user->role === 'mentor') {
            $profile = $user->mentor;
        } elseif ($user->role === 'parent') {
            $profile = $user->parent;
        }

        return response()->json([
            'success' => true,
            'data' => [
                'user_id' => $user->user_id,
                'username' => $user->username,
                'email' => $user->email,
                'role' => $user->role,
                'profile' => $profile
            ]
        ]);
    }

}
