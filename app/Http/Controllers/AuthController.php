<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\StaffAuth; // Import your StaffAuth model

class AuthController extends Controller
{
    /**
     * Handle an incoming authentication request and return an API token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        // 1. Validate the incoming request data
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // 2. Attempt to find the user by username
        $user = StaffAuth::where('username', $request->username)->first();

        // 3. Check if user exists and password is correct
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401); // 401 Unauthorized
        }

        // 4. If authentication is successful, generate a Sanctum token
        // The token name helps identify where the token was issued (e.g., 'auth_token', 'mobile_app_token')
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'name' => $user->name, // Uses the accessor from StaffAuth model
                'is_active' => $user->is_active,
            ],
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 200);
    }

    /**
     * Log the user out (Invalidate the token).
     * This method revokes the current API token using Laravel Sanctum.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        // This revokes the token that was used to authenticate the current request.
        // It requires the 'auth:sanctum' middleware to be applied to the logout route.
        if ($request->user()) {
            $request->user()->currentAccessToken()->delete();
            return response()->json([
                'message' => 'Successfully logged out'
            ]);
        }

        return response()->json([
            'message' => 'No authenticated user to log out.'
        ], 401);
    }
}
