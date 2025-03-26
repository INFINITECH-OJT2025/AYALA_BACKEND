<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApplicantReschedule;
use App\Models\JobApplication;
use App\Models\ApplicantAppointment;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApplicantNotification;
use Illuminate\Support\Facades\Log;

class ApplicantRescheduleController extends Controller
{
    /**
     * Get the admin's scheduled interview details for an applicant.
     */
    public function show(Request $request, $id)
    {
        // Fetch applicant details
        $applicant = JobApplication::find($id);
        if (!$applicant) {
            return response()->json(['error' => 'Applicant not found.'], 404);
        }
    
        // Fetch reschedule request (if exists)
        $reschedule = ApplicantReschedule::where('applicant_id', $id)->first();
    
        // Fetch admin's scheduled interview (if exists)
        $appointment = ApplicantAppointment::where('applicant_id', $id)->first();
    
        // Ensure a structured response
        return response()->json([
            'reschedule' => $reschedule ? [
                'id' => $reschedule->id,
                'applicant_id' => $reschedule->applicant_id,
                'new_schedule' => $reschedule->new_schedule ?? null,
                'applicant_message' => $reschedule->applicant_message ?? null,
                'file_path' => $reschedule->file_path ?? null,
                'status' => $reschedule->status ?? "pending",
            ] : [
                'id' => null,
                'applicant_id' => $id,
                'new_schedule' => null,
                'applicant_message' => null,
                'file_path' => null,
                'status' => "no request",
            ],
            'appointment' => $appointment ? [
                'admin_schedule' => $appointment->schedule_datetime ?? null,
                'admin_message' => $appointment->message ?? null,
            ] : [
                'admin_schedule' => null,
                'admin_message' => "No scheduled interview",
            ],
        ]);
    }
    
    
    
    /**
     * Store a reschedule request made by the applicant.
     */
    public function store(Request $request)
{
    try {
        $validated = $request->validate([
            'applicant_id' => 'required|exists:job_applications,id',
            'email' => 'required|email',
            'new_schedule' => 'required|date',
            'message' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,jpg,png',
        ]);

        $newSchedule = date('Y-m-d H:i:s', strtotime($validated['new_schedule']));

        $applicant = JobApplication::where('id', $validated['applicant_id'])
            ->where('email', $validated['email'])
            ->first();

        if (!$applicant) {
            return response()->json(['error' => 'Unauthorized access.'], 403);
        }

        $filePath = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/reschedule_files'), $fileName);
            $filePath = asset("storage/reschedule_files/$fileName");
        }

        $reschedule = ApplicantReschedule::create([
            'applicant_id' => $validated['applicant_id'],
            'email' => $validated['email'],
            'new_schedule' => $newSchedule,
            'applicant_message' => $validated['message'],
            'file_path' => $filePath,
            'status' => 'pending',
        ]);

        return response()->json(['message' => 'Reschedule request submitted successfully.', 'reschedule' => $reschedule], 201);
    } catch (\Exception $e) {
        Log::error('Error in store method: ' . $e->getMessage()); // Log the error
        return response()->json(['error' => 'Failed to process request.', 'details' => $e->getMessage()], 500);
    }
}

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);
    
        // ✅ Find reschedule request by `id`
        $reschedule = ApplicantReschedule::find($id);
    
        if (!$reschedule) {
            return response()->json(['error' => 'Reschedule request not found.'], 404);
        }
    
        // ✅ Update status
        $reschedule->status = $request->status;
        $reschedule->save();
        // ✅ Prepare email content
        $subjectText = "Reschedule Request " . ucfirst($request->status);
        $messageContent = $request->status === 'approved' 
            ? "Your reschedule request has been approved. Your new schedule is on " . \Carbon\Carbon::parse($reschedule->new_schedule)->format('F d, Y h:i A') . "."
            : "Sorry, we need to proceed with the original schedule.";
        
        $frontendUrl = env('FRONTEND_URL') . "/reschedule/{$reschedule->applicant_id}";

        // ✅ Send Email
        Mail::to($reschedule->email)->send(new ApplicantNotification(
            $subjectText,
            $messageContent,
            $reschedule->applicant_id,
            $frontendUrl,
            $reschedule->email,
            $request->status,
            $reschedule->new_schedule
        ));

        return response()->json([
            'message' => "Schedule request has been {$request->status}.",
            'reschedule' => $reschedule
        ], 200);
    }
    

    
    

    
}
