<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use App\Models\User;

class AuthController extends Controller
{
    // LOGIN
    public function login(Request $request)
    {
        $request->validate([
            "email" => "required|email",
            "password" => "required",
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(["error" => "Invalid credentials"], 401);
        }

        Auth::login($user);

        return response()->json([
            "message" => "Login successful",
            "user" => $user
        ], 200);
    }

    // GET CURRENT USER
    public function user()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(["error" => "Unauthorized"], 401);
        }

        return response()->json($user);
    }

    // LOGOUT
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(["message" => "Logged out"], 200);
    }

    // FORGOT PASSWORD
    public function forgotPassword(Request $request)
    {
        $request->validate([
            "email" => "required|email|exists:users,email",
        ]);

        $status = Password::sendResetLink(
            $request->only("email")
        );

        return $status === Password::RESET_LINK_SENT
            ? response()->json(["message" => "Reset link sent"], 200)
            : response()->json(["error" => "Failed"], 400);
    }

    // RESET PASSWORD
    public function resetPassword(Request $request)
    {
        $request->validate([
            "email" => "required|email",
            "token" => "required",
            "password" => "required|min:8|confirmed",
        ]);

        $status = Password::reset(
            $request->only("email", "token", "password", "password_confirmation"),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? response()->json(["message" => "Password reset success"], 200)
            : response()->json(["error" => "Invalid token"], 400);
    }
}
