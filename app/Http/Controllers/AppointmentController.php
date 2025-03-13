<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AppointmentController extends Controller
{
    public function index()
    {
        return response()->json(Appointment::with('property')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'date' => 'required|date',
            'time' => 'required',
            'message' => 'nullable|string',
        ]);

        $appointment = Appointment::create($validated);

        Notification::create([
            'message' => "New appointment booked by {$appointment->first_name} {$appointment->last_name}.",
            'type' => 'info',
            'is_read' => 'unread',
        ]);

        return response()->json(['message' => 'Appointment booked successfully!', 'appointment' => $appointment], 201);
    }

    public function reply(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);
        $request->validate(['message' => 'required|string']);

        // Send Email
        Mail::raw($request->message, function ($mail) use ($appointment) {
            $mail->to($appointment->email)
                ->subject("Appointment Response");
        });

        // Update status
        $appointment->status = 'replied';
        $appointment->save();
    

        return response()->json(['message' => 'Reply sent successfully!']);
    }

    public function archive($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->status = 'archived';
        $appointment->save();

        return response()->json(['message' => 'Appointment archived successfully!']);
    }

    public function unarchive($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->status = 'active';
        $appointment->save();

        return response()->json(['message' => 'Appointment unarchived successfully!']);
    }

    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();

        return response()->json(['message' => 'Appointment deleted successfully!']);
    }
}
