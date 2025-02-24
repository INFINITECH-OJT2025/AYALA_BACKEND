<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // ✅ Log the request data (FOR DEBUGGING)
        Log::info('Login attempt', $request->all());

        // ✅ Find user by email
        $user = User::where('email', $request->email)->first();

        // ❌ No token, just check credentials
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Invalid username or password'], 401);
        }

        // ✅ Redirect based on role
        return response()->json([
            'message' => 'Login successful',
            'redirect' => $user->role === 'admin' 
                ? '/admin/overview/listed-properties' 
                : '/dashboard',
        ], 200);
    }

    public function register(Request $request)
    {
        // ✅ Log the request data (FOR DEBUGGING)
        Log::info('Registration attempt', $request->all());

        // ✅ Validate input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:buyer,seller',
            'business_name' => 'nullable|string|max:255' // Only for sellers
        ]);

        // ✅ Create user (WITH PASSWORD HASHING)
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Secure hashing
            'role' => $request->role,
            'business_name' => $request->role === 'seller' ? $request->business_name : null
        ]);

        return response()->json([
            'message' => 'Registration successful',
            'redirect' => '/auth/login'
        ], 201);
    }
}
