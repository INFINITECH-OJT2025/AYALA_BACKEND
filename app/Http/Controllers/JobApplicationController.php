<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApplicantNotification;
use App\Models\Notification;

class JobApplicationController extends Controller
{
    public function store(Request $request) {
        $validated = $request->validate([
            'id' => 'required|exists:jobs,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'address' => 'required|string',
            'resume' => 'required|mimes:pdf,doc,docx|max:2048'
        ]);

        // Handle file upload to public/storage/job_applications
        if ($request->hasFile('resume')) {
            $resume = $request->file('resume');
            $resumeName = time() . '_' . $resume->getClientOriginalName();

            // ✅ Store directly in `public/storage/job_applications`
            $resume->move(public_path('storage/job_applications'), $resumeName);

            // ✅ Set the correct public URL
            $validated['resume_path'] = asset("storage/job_applications/$resumeName");
        }
        $application = JobApplication::create($validated);
        // ✅ Create Notification
        Notification::create([
            'message' => "New job application from {$application->first_name} {$application->last_name}.",
            'type' => 'info',
           'is_read' => 'unread',
        ]);

    
        return response()->json(['message' => 'Application submitted successfully', 'application' => $application], 201);
    }

    public function index() {
        return response()->json(JobApplication::all());
    }

    public function approve(Request $request, $id) {
        $applicant = JobApplication::findOrFail($id);
        $messageBody = $request->input('message', 'Your application has been approved.');
    
        // ✅ Update applicant status
        $applicant->update(['status' => 'approved']);
    
        // ✅ Send email using the existing Mailable
        Mail::to($applicant->email)->send(new ApplicantNotification("Application Approved", $messageBody));
    
        return response()->json(['message' => 'Applicant approved and email sent successfully']);
    }
    
    public function reject(Request $request, $id) {
        $applicant = JobApplication::findOrFail($id);
        $messageBody = $request->input('message', 'We regret to inform you that your application has been rejected.');
    
        // ✅ Update applicant status
        $applicant->update(['status' => 'rejected']);
    
        // ✅ Send email using the existing Mailable
        Mail::to($applicant->email)->send(new ApplicantNotification("Application Rejected", $messageBody));
    
        return response()->json(['message' => 'Applicant rejected and email sent successfully']);
    }

    public function destroy($id) {
        $applicant = JobApplication::findOrFail($id);
    
        // ✅ Delete associated resume file
        if ($applicant->resume_path) {
            $resumePath = str_replace('storage/', 'public/', $applicant->resume_path);
            if (Storage::exists($resumePath)) {
                Storage::delete($resumePath);
            }
        }
    
        // ✅ Delete the applicant record
        $applicant->delete();
    
        return response()->json(['message' => 'Applicant deleted successfully']);
    }

    public function getStats() {
        $applications = JobApplication::select('id', 'first_name', 'last_name', 'email', 'phone', 'status', 'resume_path')
            ->orderBy('created_at', 'desc')
            ->get();
    
        return response()->json([
            'total' => JobApplication::count(),
            'pending' => JobApplication::where('status', 'pending')->count(),
            'approved' => JobApplication::where('status', 'approved')->count(),
            'rejected' => JobApplication::where('status', 'rejected')->count(),
            'applications' => $applications // ✅ Include applications data for the DataTable
        ]);
    }

    
    
    


}
