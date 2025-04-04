<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TestimonialController extends Controller
{
    // Store a new testimonial
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'experience' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg',
            'media.*' => 'nullable|file|mimes:jpeg,png,jpg,mp4,mov,avi',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('testimonials/photos', 'public');
        }

        $mediaPaths = [];
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $mediaPaths[] = $file->store('testimonials/media', 'public');
            }
        }

        Testimonial::create([
            'name' => $request->name,
            'rating' => $request->rating,
            'experience' => $request->experience,
            'photo' => $photoPath,
            'media' => $mediaPaths,
            'status' => 0, // default to not featured
        ]);

        return response()->json(['message' => 'Testimonial submitted successfully.']);
    }

    // Fetch all testimonials
    public function index()
{
    $testimonials = Testimonial::all();

    // Optional: If you want to include the media URLs and the full photo URL, you can format them like so:
        $testimonials = $testimonials->map(function ($testimonial) {
            $testimonial->photo_url = $testimonial->photo ? Storage::url($testimonial->photo) : null;
            $testimonial->media_urls = array_map(fn($media) => Storage::url($media), $testimonial->media);
            
            // Debugging: Log the URLs
            Log::info('Photo URL: ' . $testimonial->photo_url);
            Log::info('Media URLs: ' . json_encode($testimonial->media_urls));
            
            return $testimonial;
        });

    return response()->json($testimonials);
}

}
