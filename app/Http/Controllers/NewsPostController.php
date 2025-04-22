<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NewsPost;
use App\Models\Subscriber;
use Illuminate\Support\Facades\Mail;
use App\Mail\FeaturedNewsMail;
use Illuminate\Support\Facades\Validator;

class NewsPostController extends Controller
{
    public function index() {
        return response()->json(NewsPost::orderBy('published_at', 'desc')->get());
    }

    public function show($id) {
        $news = NewsPost::find($id);
        if (!$news) {
            return response()->json(['message' => 'News post not found'], 404);
        }
        return response()->json($news);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'category' => 'required|string',
            'is_featured' => 'boolean',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->all();

        if ($request->hasFile('image')) {
            $filename = time() . '_' . uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(public_path('storage/news_images'), $filename);
            $data['image'] = asset('storage/news_images/' . $filename);
        }

        $news = NewsPost::create($data);

        if ($news->is_featured && $news->status === "published") {
            $this->sendFeaturedNewsEmail($news);
        }

        return response()->json($news, 201);
    }

    public function update(Request $request, $id) {
        $news = NewsPost::find($id);
        if (!$news) {
            return response()->json(['message' => 'News post not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category' => 'sometimes|required|string',
            'is_featured' => 'boolean',
            'status' => 'sometimes|required|in:draft,published',
            'published_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->all();

        if ($request->hasFile('image')) {
            $filename = time() . '_' . uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(public_path('storage/news_images'), $filename);
            $data['image'] = asset('storage/news_images/' . $filename);
        }

        $wasPreviouslyPublished = $news->status === "published";
        $news->update($data);

        if ($news->is_featured && $news->status === "published" && !$wasPreviouslyPublished) {
            $this->sendFeaturedNewsEmail($news);
        }

        return response()->json($news);
    }

    public function destroy($id) {
        $news = NewsPost::find($id);
        if (!$news) {
            return response()->json(['message' => 'News post not found'], 404);
        }

        $news->delete();
        return response()->json(['message' => 'News post deleted successfully']);
    }

    private function sendFeaturedNewsEmail($news) {
        $subscribers = Subscriber::pluck('email');
        foreach ($subscribers as $email) {
            Mail::to($email)->send(new FeaturedNewsMail($news));
        }
    }
}
