<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // ✅ Admin Login
    public function login(Request $request)
    {
        $request->validate([
            "email" => "required|email",
            "password" => "required",
        ]);
    
        // ✅ Fetch the user manually
        $user = User::where('email', $request->email)->first();
    
        // ✅ Check if user exists and verify password using Hash::check()
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(["error" => "Invalid credentials"], 401);
        }
    
        // ✅ Ensure only the predefined admin can log in
        if ($user->email !== "anyayahanjosedexter@gmail.com") {
            return response()->json(["error" => "Access denied."], 403);
        }
    
        Auth::login($user);
    
        // ❌ REMOVE session()->regenerate() - Not needed in API authentication
        // $request->session()->regenerate(); <-- REMOVE THIS
    
        return response()->json(["user" => $user], 200);
    }
    


    // ✅ Get Authenticated User
    public function user(Request $request)
{
    $user = Auth::user(); // ✅ Ensure this is not null

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
}
