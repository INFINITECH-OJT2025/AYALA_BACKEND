<?php

namespace App\Http\Controllers;

use App\Models\ApplicantAppointment;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApplicantNotification;
use App\Models\ApplicantReschedule;

class ApplicantAppointmentController extends Controller
{
    /**
     * Schedule an applicant for an appointment.
     */
    public function schedule(Request $request, $id)
    {
        $validated = $request->validate([
            'schedule_datetime' => 'required|date_format:Y-m-d H:i:s',
            'message' => 'nullable|string',
        ]);

        $applicant = JobApplication::findOrFail($id);

        $existingAppointment = ApplicantAppointment::where('applicant_id', $applicant->id)->first();
        if ($existingAppointment) {
            return response()->json(['error' => 'This applicant already has a scheduled appointment.'], 409);
        }


        $appointment = ApplicantAppointment::create([
            'applicant_id' => $applicant->id,
            'schedule_datetime' => $validated['schedule_datetime'],
            'message' => $validated['message'],
        ]);


        $applicant->update(['status' => 'replied']);


        $frontendRescheduleUrl = env('FRONTEND_URL', 'http://localhost:3000') . "/reschedule/{$applicant->id}?email={$applicant->email}";


        $messageBody = "Your interview has been scheduled on {$validated['schedule_datetime']}.\n\n";
        if (!empty($validated['message'])) {
            $messageBody .= "Message from the admin: {$validated['message']}\n\n";
        }
        $messageBody .= "If you need to reschedule, click the button below:\n\n";


        Mail::to($applicant->email)->send(new ApplicantNotification(
            "Interview Scheduled",
            $messageBody,
            $applicant->id,
            $frontendRescheduleUrl,
            $applicant->email,
            "scheduled",
            null
        ));


        return response()->json(['message' => 'Appointment scheduled successfully', 'appointment' => $appointment], 201);
    }



    /**
     * Get all scheduled appointments.
     */
    public function index()
    {
        return response()->json(ApplicantAppointment::with('applicant')->latest()->get());
    }

    /**
     * Get a single appointment.
     */
    public function show($id)
    {
        return response()->json(ApplicantAppointment::with('applicant')->findOrFail($id));
    }

    /**
     * Delete an appointment and its associated job application.
     */
    public function destroy($id)
    {
        $appointment = ApplicantAppointment::findOrFail($id);


        $jobApplication = JobApplication::find($appointment->applicant_id);
        if ($jobApplication) {
            $jobApplication->delete();
        }

        $appointment->delete();

        return response()->json(['message' => 'Appointment and applicant deleted successfully']);
    }

    public function approveReschedule($id)
    {
        $reschedule = ApplicantReschedule::findOrFail($id);


        $appointment = ApplicantAppointment::where('applicant_id', $reschedule->applicant_id)->first();
        if ($appointment) {
            $appointment->update(['schedule_datetime' => $reschedule->new_schedule]);
        }


        $reschedule->update(['status' => 'approved']);

        return response()->json(['message' => 'Reschedule approved and interview date updated.']);
    }
}
