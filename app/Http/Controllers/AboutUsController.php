<?php

namespace App\Http\Controllers;

use App\Models\AboutUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AboutUsController extends Controller
{
    // ✅ Get About Us Content
    public function index()
    {
        try {
            $aboutUs = AboutUs::first();
    
            if (!$aboutUs) {
                return response()->json(["message" => "No About Us content found"], 404);
            }
    
            // ✅ Ensure `history` is properly decoded
            $aboutUs->history = !empty($aboutUs->history) ? json_decode($aboutUs->history, true) : [];
            $aboutUs->programs = !empty($aboutUs->programs) ? json_decode($aboutUs->programs, true) : [];
            // ✅ Convert hero media (image/video) to full URL
            if ($aboutUs->hero_image) {
                $aboutUs->hero_image = asset("storage/" . $aboutUs->hero_image);
            }
    
            return response()->json($aboutUs);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    // ✅ Create or Update About Us Content
    public function store(Request $request)
    {
        $request->validate([
            'hero_title' => 'required|string',
            'hero_subtitle' => 'required|string',
            'mission_title' => 'required|string',
            'mission_description' => 'required|string',
            'vision_title' => 'required|string',
            'vision_description' => 'required|string',
            'history' => 'nullable|array',
            'history.*.title' => 'required|string',
            'history.*.description' => 'required|string',
            'history.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'hero_image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,mp4', // ✅ Allows image and video

            'programs' => 'nullable|array',
            'programs.*.title' => 'required|string',
            'programs.*.description' => 'required|string',
            'programs.*.link' => 'required|url', // ✅ Ensure valid URL
        ]);

        $aboutUs = AboutUs::firstOrNew([]);

        // ✅ Handle hero media upload (image or video)
        if ($request->hasFile('hero_image')) {
            if ($aboutUs->hero_image) {
                Storage::delete('public/' . $aboutUs->hero_image);
            }
            $aboutUs->hero_image = $request->file('hero_image')->store('about_us', 'public');
        }

        // ✅ Handle multiple history images
        $historyData = [];
        if ($request->has('history')) {
            foreach ($request->history as $index => $historyItem) {
                $imagePath = null;
                if (isset($historyItem['image']) && $historyItem['image']->isValid()) {
                    $imagePath = $historyItem['image']->store('about_us', 'public');
                }

                $historyData[] = [
                    'title' => $historyItem['title'],
                    'description' => $historyItem['description'],
                    'image' => $imagePath,
                ];
            }
        }

        $programsData = [];
    if ($request->has('programs')) {
        foreach ($request->programs as $program) {
            $programsData[] = [
                'title' => $program['title'],
                'description' => $program['description'],
                'link' => $program['link'],
            ];
        }
    }

    $aboutUs->fill($request->except(['hero_image', 'history', 'programs']));
    $aboutUs->history = json_encode($historyData);
    $aboutUs->programs = json_encode($programsData);
    $aboutUs->save();

    return response()->json(['message' => 'About Us updated successfully!', 'data' => $aboutUs]);
}

    // ✅ Delete About Us Content
    public function destroy()
    {
        $aboutUs = AboutUs::first();
        if (!$aboutUs) {
            return response()->json(['message' => 'No About Us content found'], 404);
        }

        // ✅ Delete hero media (image/video)
        if ($aboutUs->hero_image) {
            Storage::delete('public/' . $aboutUs->hero_image);
        }

        // ✅ Delete history images
        $history = json_decode($aboutUs->history, true);
        if (is_array($history)) {
            foreach ($history as $historyItem) {
                if (!empty($historyItem['image'])) {
                    Storage::delete('public/' . $historyItem['image']);
                }
            }
        }

        // ✅ Delete the record
        $aboutUs->delete();

        return response()->json(['message' => 'About Us deleted successfully']);
    }
}
