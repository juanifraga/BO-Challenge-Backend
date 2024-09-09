<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * Handle an authentication attempt.
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            $token = $user->createToken('Personal Access Token');

            return response()->json([
                'success' => true,
                'token' => $token->plainTextToken,
                'user' => $user,
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        $user->tokens()->delete();

        return response()->json(['success' => true, 'message' => 'Logged out successfully']);
    }
}