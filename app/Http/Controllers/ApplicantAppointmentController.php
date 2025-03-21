<?php

namespace App\Http\Controllers;

use App\Models\ApplicantAppointment;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApplicantNotification;

class ApplicantAppointmentController extends Controller
{
    /**
     * Schedule an applicant for an appointment.
     */
    public function schedule(Request $request, $id)
    {
        $validated = $request->validate([
            'schedule_datetime' => 'required|date_format:Y-m-d H:i:s', // ✅ Ensure valid date format
            'message' => 'nullable|string',
        ]);

        $applicant = JobApplication::findOrFail($id);

        // ✅ Check if the applicant already has a scheduled appointment
        $existingAppointment = ApplicantAppointment::where('applicant_id', $applicant->id)->first();
        if ($existingAppointment) {
            return response()->json(['error' => 'This applicant already has a scheduled appointment.'], 409);
        }

        // ✅ Create a new appointment
        $appointment = ApplicantAppointment::create([
            'applicant_id' => $applicant->id,
            'schedule_datetime' => $validated['schedule_datetime'],
            'message' => $validated['message'],
        ]);

        // ✅ Update applicant status to "replied"
        $applicant->update(['status' => 'replied']);

        // ✅ Send email notification
        Mail::to($applicant->email)->send(new ApplicantNotification(
            "Interview Scheduled",
            "Your interview is scheduled on {$validated['schedule_datetime']}. {$validated['message']}"
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

        // ✅ Delete the job application as well
        $jobApplication = JobApplication::find($appointment->applicant_id);
        if ($jobApplication) {
            $jobApplication->delete();
        }

        $appointment->delete();

        return response()->json(['message' => 'Appointment and applicant deleted successfully']);
    }
}
