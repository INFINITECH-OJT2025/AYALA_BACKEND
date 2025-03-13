<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\PropertyInquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PropertyInquiryController extends Controller {
    public function store(Request $request) {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'message' => 'required|string',
            
        ]);

        $inquiry = PropertyInquiry::create($validated);

        Notification::create([
            'message' => "New property inquiry from {$inquiry->first_name} {$inquiry->last_name}.",
            'type' => 'info',
            'is_read' => 'unread',
        ]);
        return response()->json(['message' => 'Inquiry sent successfully!', 'inquiry' => $inquiry], 201);
    }

    public function index() {
        return response()->json(PropertyInquiry::with('property')->get());
    }

    public function reply(Request $request, $id) {
        $inquiry = PropertyInquiry::findOrFail($id);
        $validated = $request->validate(['message' => 'required|string']);

        // Send email reply
        Mail::raw($validated['message'], function ($mail) use ($inquiry) {
            $mail->to($inquiry->email)
                ->subject("Reply to Your Property Inquiry")
                ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
        });

        return response()->json(['message' => 'Reply sent successfully!']);
    }

    public function archive($id) {
        $inquiry = PropertyInquiry::findOrFail($id);
        $inquiry->update(['status' => 'archived']);
        return response()->json(['message' => 'Inquiry archived successfully!']);
    }

    public function unarchive($id) {
        $inquiry = PropertyInquiry::findOrFail($id);
        $inquiry->update(['status' => 'active']);
        return response()->json(['message' => 'Inquiry unarchived successfully!']);
    }

    public function destroy($id) {
        $inquiry = PropertyInquiry::findOrFail($id);
        $inquiry->delete();
        return response()->json(['message' => 'Inquiry deleted successfully!']);
    }
}
