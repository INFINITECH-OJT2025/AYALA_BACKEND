<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\Subscriber;
use Illuminate\Support\Facades\Mail;
use App\Mail\JobPostedMail;

class JobController extends Controller
{
    public function index() {
        return response()->json(Job::all());
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'type' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'salary' => 'nullable|string|max:255',
            'deadline' => 'nullable|date',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,png,jpeg',
            'slots' => 'required|integer|min:1'
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('storage/job_images'), $imageName);
            $validated['image_url'] = asset("storage/job_images/$imageName");
        }

        $job = Job::create($validated);

        // ✅ Send email to all subscribers
        $this->sendJobPostedEmail($job);

        return response()->json($job, 201);
    }

    private function sendJobPostedEmail($job) {
        $subscribers = Subscriber::pluck('email'); // Fetch all subscriber emails
        foreach ($subscribers as $email) {
            Mail::to($email)->send(new JobPostedMail($job));
        }
    }

    public function featuredJobs(Request $request)
    {
        $limit = $request->query('limit', 3); 
        return response()->json(Job::latest()->take($limit)->get());
    }
    public function fetchJobs() {
        return response()->json(Job::latest()->get()); // ✅ Fetch all jobs without limit
    }

    public function update(Request $request, $id) {
        Log::info('Update Request Data:', $request->all());

        $job = Job::findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'location' => 'sometimes|string|max:255',
            'type' => 'sometimes|string|max:255',
            'category' => 'sometimes|string|max:255',
            'salary' => 'sometimes|string|max:255',
            'deadline' => 'sometimes|date',
            'description' => 'sometimes|string',
            'image' => 'nullable|image|mimes:jpg,png,jpeg',
            'slots' => 'sometimes|integer|min:1' // ✅ Allow updating slots
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('storage/job_images'), $imageName);
            $validated['image_url'] = asset("storage/job_images/$imageName");
        }

        $job->fill($validated);
        if ($job->isDirty()) { 
            $job->save();
        }

        return response()->json([
            'message' => 'Job updated successfully',
            'job' => $job
        ]);
    }

    public function destroy($id) {
        $job = Job::findOrFail($id);

        if ($job->image_url) {
            $imagePath = str_replace(asset('storage/'), 'public/', $job->image_url);
            if (Storage::exists($imagePath)) {
                Storage::delete($imagePath);
            }
        }

        $job->delete();
        return response()->json(['message' => 'Job deleted successfully']);
    }
}
