<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;

class AuthController extends Controller
{

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


        if ($user->email !== "anyayahanjosedexter@gmail.com") {
            return response()->json(["error" => "Access denied."], 403);
        }

        Auth::login($user);

        return response()->json(["user" => $user], 200);
    }


    public function user(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(["error" => "Unauthorized"], 401);
        }

        return response()->json($user);
    }

    // ✅ Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        return response()->json(["message" => "Logged out"], 200);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            "email" => "required|email|exists:users,email",
        ]);
        $status = Password::sendResetLink($request->only("email"));
        return $status === Password::RESET_LINK_SENT
            ? response()->json(["message" => "Password reset link sent to your email."], 200)
            : response()->json(["error" => "Failed to send reset link."], 400);
    }

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
                $user->forceFill([
                    "password" => Hash::make($password),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? response()->json(["message" => "Password reset successful"], 200)
            : response()->json(["error" => "Invalid token or email"], 400);
    }
}
