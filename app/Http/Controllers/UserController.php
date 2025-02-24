<?php

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

public function login(Request $request)
{
    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['error' => 'Invalid credentials'], 401);
    }

    // ✅ If the user is an admin, return a success response with a redirect URL
    if ($user->role === 'admin') {
        return response()->json([
            'message' => 'Login successful',
            'redirect' => '/admin/overview/listed-properties',
        ]);
    }

    return response()->json([
        'message' => 'Login successful',
        'redirect' => '/dashboard', // Default redirect for non-admins
    ]);
}


