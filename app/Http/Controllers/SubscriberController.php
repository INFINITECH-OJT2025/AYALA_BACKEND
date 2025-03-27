<?php 

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use App\Mail\SubscriptionConfirmation;

class SubscriberController extends Controller {
    public function subscribe(Request $request): JsonResponse {
        $validated = $request->validate([
            'email' => 'required|email|unique:subscribers,email'
        ]);

        // Create subscriber
        $subscriber = Subscriber::create($validated);

        // Send confirmation email
        Mail::to($subscriber->email)->send(new SubscriptionConfirmation());

        return response()->json(['success' => true, 'message' => 'Subscription successful! A confirmation email has been sent.']);
    }

    public function unsubscribe(Request $request): JsonResponse {
        $validated = $request->validate([
            'email' => 'required|email|exists:subscribers,email'
        ]);
    
        $deleted = Subscriber::where('email', $validated['email'])->delete();
    
        if ($deleted) {
            return response()->json(['success' => true, 'message' => 'You have been unsubscribed successfully.']);
        }
    
        return response()->json(['success' => false, 'message' => 'Unsubscription failed. Please try again.'], 500);
    }
    
}
