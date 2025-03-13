<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inquiry;
use App\Models\Notification;
use Illuminate\Support\Facades\Mail;

class InquiryController extends Controller
{
    // ✅ Store Inquiry
    public function store(Request $request) {
        $validated = $request->validate([
            'inquiry_type' => 'required|string',
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'message' => 'required|string'
        ]);

        $inquiry = Inquiry::create($validated);

        Notification::create([
            'message' => "New general inquiry from {$inquiry->first_name} {$inquiry->last_name}.",
            'type' => 'info',
            'is_read' => 'unread', // ✅ Correct ENUM value
        ]);
        
        return response()->json(['message' => 'Inquiry submitted successfully!', 'inquiry' => $inquiry], 201);
    }

    // ✅ Fetch All Inquiries
    public function index() {
        return response()->json(Inquiry::latest()->get());
    }

    public function reply(Request $request, $id) {
        $inquiry = Inquiry::findOrFail($id);
        $messageBody = $request->input('message');
    
        // ✅ Send Email
        Mail::raw($messageBody, function ($mail) use ($inquiry) {
            $mail->to($inquiry->email)
                 ->subject("Reply to Your Inquiry");
        });

        $inquiry->status = 'replied';
        $inquiry->save();
    
        return response()->json(['message' => 'Reply sent successfully']);
    }

    public function archive($id) {
        $inquiry = Inquiry::find($id);
        if (!$inquiry) {
            return response()->json(['message' => 'Inquiry not found'], 404);
        }
    
        $inquiry->status = 'archived';
        $inquiry->save();
    
        return response()->json(['message' => 'Inquiry archived successfully']);
    }

    public function destroy($id) {
        $inquiry = Inquiry::findOrFail($id);
        $inquiry->delete();
    
        return response()->json(['message' => 'Inquiry deleted successfully']);
    }

    public function unarchive($id) {
        $inquiry = Inquiry::find($id);
        if (!$inquiry) {
            return response()->json(['message' => 'Inquiry not found'], 404);
        }
    
        $inquiry->status = 'active';
        $inquiry->save();
    
        return response()->json(['message' => 'Inquiry unarchived successfully']);
    }

    public function getInquiryStats() {
        $inquiryStats = Inquiry::selectRaw('inquiry_type, COUNT(*) as count')
            ->groupBy('inquiry_type')
            ->orderByDesc('count')
            ->get();
    
        return response()->json($inquiryStats);
    }
    
    
}
