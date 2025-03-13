<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

Route::get('/', function () {
    return view('welcome');
});

// ✅ Serve images from `public/storage/properties/` with CORS headers
Route::get('/storage/properties/{filename}', function (Request $request, $filename) {
    $path = public_path('storage/properties/' . $filename);

    if (!file_exists($path)) {
        return response()->json(['error' => 'File not found'], 404);
    }

    // ✅ Get file contents
    $file = file_get_contents($path);

    // ✅ Force CORS headers for images
    return response($file, 200, [
        'Content-Type' => mime_content_type($path),
        'Content-Length' => filesize($path),
        'Access-Control-Allow-Origin' => '*',
        'Access-Control-Allow-Methods' => 'GET, OPTIONS',
        'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
        'Access-Control-Expose-Headers' => 'Content-Disposition',
    ]);
});
