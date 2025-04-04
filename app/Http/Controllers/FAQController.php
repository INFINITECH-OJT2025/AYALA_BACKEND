<?php

namespace App\Http\Controllers;

use App\Models\FAQ;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FAQController extends Controller
{
    // Fetch all FAQs
    public function index()
    {
        $faqs = FAQ::all();  // Fetch all FAQs from the database
        return response()->json($faqs);  // Return the FAQs in JSON format
    }

    // Store a new FAQ
    public function store(Request $request)
    {
        // Validate incoming request data
        $validatedData = $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
        ]);

        // Create and store the new FAQ
        $faq = FAQ::create($validatedData);

        // Return the newly created FAQ in JSON format with a 201 status code
        return response()->json($faq, Response::HTTP_CREATED);
    }

    // Update an existing FAQ
    public function update(Request $request, FAQ $faq)
    {
        // Validate incoming request data
        $validatedData = $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
        ]);

        // Update the FAQ with the validated data
        $faq->update($validatedData);

        // Return the updated FAQ in JSON format
        return response()->json($faq);
    }

    // Delete an FAQ
    public function destroy(FAQ $faq)
    {
        // Delete the FAQ from the database
        $faq->delete();

        // Return a success message upon deletion
        return response()->json(['message' => 'FAQ deleted successfully'], Response::HTTP_OK);
    }
}
