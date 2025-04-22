<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TestimonialController extends Controller
{
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
            'status' => 'unpublished',
        ]);

        return response()->json(['message' => 'Testimonial submitted successfully.']);
    }

    public function index()
    {
        $testimonials = Testimonial::all();

        $testimonials = $testimonials->map(function ($testimonial) {
            $testimonial->photo_url = $testimonial->photo ? asset('storage/' . $testimonial->photo) : null;
            $testimonial->media_urls = array_map(fn($media) => asset('storage/' . $media), $testimonial->media ?? []);

            Log::info('Returned Testimonial:', [
                'id' => $testimonial->id,
                'photo' => $testimonial->photo,
                'photo_url' => $testimonial->photo_url,
                'media' => $testimonial->media,
                'media_urls' => $testimonial->media_urls,
            ]);

            return $testimonial;
        });

        return response()->json($testimonials);
    }

    public function updateStatus($id, Request $request)
    {
        Log::info("Updating testimonial status for ID: $id");

        $testimonial = Testimonial::findOrFail($id);

        $request->validate([
            'status' => 'required|in:unpublished,published',
        ]);

        $testimonial->status = $request->status;
        $testimonial->save();

        return response()->json(['message' => 'Testimonial status updated successfully.']);
    }

    public function destroy($id)
    {
        $testimonial = Testimonial::findOrFail($id);

        if ($testimonial->photo) {
            Storage::disk('public')->delete($testimonial->photo);
        }

        if ($testimonial->media) {
            foreach ($testimonial->media as $mediaFile) {
                Storage::disk('public')->delete($mediaFile);
            }
        }

        $testimonial->delete();

        return response()->json(['message' => 'Testimonial deleted successfully.']);
    }
}
