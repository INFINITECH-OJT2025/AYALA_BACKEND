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
    public function index()
    {
        return response()->json(Job::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|',
            'location' => 'required|string|',
            'type' => 'nullable|string|',
            'category' => 'nullable|string|',
            'salary' => 'nullable|string|',
            'deadline' => 'nullable|date',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,png,jpeg',
            'slots' => 'required|integer|min:1',
            'qualification' => 'nullable|string|',
            'seniority_level' => 'nullable|string|',
            'job_function' => 'nullable|string|',
        ]);


        $existingJob = Job::where('title', $request->title)
            ->where('location', $request->location)
            ->first();

        if ($existingJob) {
            return response()->json([
                'success' => false,
                'message' => 'A job with this title and location already exists.'
            ], 409);
        }


        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('storage/job_images'), $imageName);
            $validated['image_url'] = asset("storage/job_images/$imageName");
        }


        $job = Job::create($validated);


        $this->sendJobPostedEmail($job);

        $existingJob = Job::where('title', $request->title)
            ->where('location', $request->location)
            ->first();

        return response()->json([
            'success' => true,
            'message' => 'Job posted successfully!',
            'job' => $job
        ], 201);
    }


    private function sendJobPostedEmail($job)
    {
        $subscribers = Subscriber::pluck('email');
        foreach ($subscribers as $email) {
            Mail::to($email)->send(new JobPostedMail($job));
        }
    }

    public function featuredJobs(Request $request)
    {
        $limit = $request->query('limit', 3);
        $today = now()->startOfDay();


        $jobs = Job::where(function ($query) use ($today) {
            $query->whereNull('deadline')
                ->orWhere('deadline', '>=', $today);
        })
            ->latest()
            ->take($limit)
            ->get();

        return response()->json($jobs);
    }

    public function fetchJobs()
    {
        return response()->json(Job::latest()->get());
    }

    public function update(Request $request, $id)
    {
        Log::info('Update Request Data:', $request->all());

        $job = Job::findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|required|string|',
            'location' => 'sometimes|required|string|',
            'type' => 'sometimes|string|',
            'category' => 'sometimes|string|',
            'salary' => 'sometimes|string|',
            'deadline' => 'sometimes|date',
            'description' => 'sometimes|string',
            'image' => 'nullable|image|mimes:jpg,png,jpeg',
            'slots' => 'sometimes|integer|min:1',
            'qualification' => 'sometimes|string|',
            'seniority_level' => 'sometimes|string|',
            'job_function' => 'sometimes|string|',
        ]);

        if (($request->title !== $job->title || $request->location !== $job->location) && $request->has(['title'])) {
            $existingJob = Job::where('title', $request->title)
                ->where('id', '!=', $id)
                ->first();

            if ($existingJob) {
                return response()->json([
                    'success' => false,
                    'message' => "A job titled '{$request->title}' is already listed in '{$request->location}'. Please modify the job title or location."
                ], 409);
            }
        }


        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('storage/job_images'), $imageName);
            $validated['image_url'] = asset("storage/job_images/$imageName");
        }

        $job->fill($validated);

        if ($job->isDirty()) {
            $job->save();
            return response()->json([
                'success' => true,
                'message' => 'Job updated successfully!',
                'job' => $job
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No changes were made to the job.',
        ], 400);
    }



    public function destroy($id)
    {
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
