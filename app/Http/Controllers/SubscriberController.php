<?php 

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SubscriberController extends Controller {
    public function subscribe(Request $request): JsonResponse {
        $validated = $request->validate([
            'email' => 'required|email|unique:subscribers,email'
        ]);

        Subscriber::create($validated);

        return response()->json(['success' => true, 'message' => 'Subscription successful!']);
    }
}
