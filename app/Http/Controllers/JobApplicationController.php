<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApplicantNotification;
use App\Models\ApplicantAppointment;
use App\Models\Notification;

class JobApplicationController extends Controller
{
    /**
     * Store a new job application.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'job_title' => 'required|string|max:255', // ✅ Validate job title instead of job ID
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'address' => 'required|string',
            'resume' => 'required|mimes:pdf,doc,docx,txt,odt,rtf,jpg,jpeg,png', // ✅ Allows all common resume formats + images (5MB max)
        ]);
        
    
        // ✅ Check if applicant has already applied for this specific job title
        $existingApplication = JobApplication::where('email', $request->email)
            ->where('job_title', $request->job_title)
            ->first();
    
            if ($existingApplication) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have already applied for this job.'
                ], 409);
            }
    
        // ✅ Handle file upload
        if ($request->hasFile('resume')) {
            $resume = $request->file('resume');
            $resumeName = time() . '_' . $resume->getClientOriginalName();
            $resume->move(public_path('storage/job_applications'), $resumeName);
            $validated['resume_path'] = asset("storage/job_applications/$resumeName");
        }
    
        // ✅ Store job application
        $application = JobApplication::create($validated);
    
        // ✅ Create a notification
        Notification::create([
            'message' => "New job application from {$application->first_name} {$application->last_name} for {$application->job_title}.",
            'type' => 'info',
            'is_read' => 'unread',
        ]);
    
        return response()->json(['message' => 'Application submitted successfully', 'application' => $application], 201);
    }    

    /**
     * Get all job applications.
     */
    public function index()
    {
        $applicants = JobApplication::leftJoin('applicant_appointments', 'job_applications.id', '=', 'applicant_appointments.applicant_id')
            ->select(
                'job_applications.*',
                'applicant_appointments.schedule_datetime as schedule_date', // Ensure it matches frontend expectations
                'applicant_appointments.message'
            )
            ->orderBy('job_applications.created_at', 'desc')
            ->get();
    
        return response()->json($applicants);
    }
    
    

    /**
     * Get details of a single job application.
     */
    public function show($id)
    {
        $applicant = JobApplication::findOrFail($id);
        return response()->json($applicant);
    }

    /**
     * Approve a job application and send an email notification.
     */
    public function schedule(Request $request, $id)
{
    $validated = $request->validate([
        'schedule_datetime' => 'required|date_format:Y-m-d H:i:s', // ✅ Ensure valid date format
        'message' => 'nullable|string',
    ]);

    $applicant = JobApplication::findOrFail($id);

    // ✅ Create or update appointment (No restriction now)
    $appointment = ApplicantAppointment::updateOrCreate(
        ['applicant_id' => $applicant->id],
        [
            'schedule_datetime' => $validated['schedule_datetime'],
            'message' => $validated['message'],
        ]
    );

    // ✅ Update applicant status to "replied"
    $applicant->update(['status' => 'replied']);

    // ✅ Define frontend reschedule page URL
    $frontendRescheduleUrl = env('FRONTEND_URL', 'http://localhost:3000') . "/reschedule/{$applicant->id}?email=" . urlencode($applicant->email);

    // ✅ Define the message body for email
    $messageBody = "Your interview has been scheduled on {$validated['schedule_datetime']}.\n\n";
    if (!empty($validated['message'])) {
        $messageBody .= "Message from the admin: {$validated['message']}\n\n";
    }
    $messageBody .= "If you need to reschedule, click the button below:\n\n";

    // ✅ Send email notification with the frontend reschedule URL
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
     * Reject a job application and send an email notification.
     */
    public function reject(Request $request, $id)
    {
        $applicant = JobApplication::findOrFail($id);
    
        // ✅ Define message content
        $messageBody = $request->input('message', 'We regret to inform you that your application has been rejected.');
    
        // ✅ Update applicant status
        $applicant->update(['status' => 'rejected']);
    
        // ✅ Define frontend jobs page URL
        $frontendJobsUrl = env('FRONTEND_URL', 'http://localhost:3000') . "/jobs";
    
        // ✅ Send email with correct routing
        Mail::to($applicant->email)->send(new ApplicantNotification(
            "Application Rejected",
            $messageBody,
            $applicant->id,
            $frontendJobsUrl,
            $applicant->email,
            "rejected", // ✅ Add status
            null // ✅ No new schedule for rejected applications
        ));
        
        return response()->json(['message' => 'Applicant rejected and email sent successfully']);
    }
    

    /**
     * Delete a job application and its associated resume file.
     */
    public function destroy($id)
    {
        $applicant = JobApplication::findOrFail($id);

            // ✅ Delete associated appointment if exists
        \App\Models\ApplicantAppointment::where('applicant_id', $id)->delete();

        // ✅ Delete resume file if it exists
        if ($applicant->resume_path) {
            $resumePath = str_replace(asset('storage/'), 'public/', $applicant->resume_path);
            if (Storage::exists($resumePath)) {
                Storage::delete($resumePath);
            }
        }

        // ✅ Delete the applicant record
        $applicant->delete();

        return response()->json(['message' => 'Applicant and appointment deleted successfully']);
    }

    /**
     * Get job application statistics.
     */
    public function getStats()
    {
        return response()->json([
            'total' => JobApplication::count(),
            'pending' => JobApplication::where('status', 'pending')->count(),
            'approved' => JobApplication::where('status', 'approved')->count(),
            'rejected' => JobApplication::where('status', 'rejected')->count(),
            'applications' => JobApplication::select('id', 'first_name', 'last_name', 'email', 'phone', 'status', 'resume_path')
                ->orderBy('created_at', 'desc')
                ->get(),
        ]);
    }
}
