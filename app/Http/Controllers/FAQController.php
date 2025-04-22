<?php

namespace App\Http\Controllers;

use App\Models\FAQ;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FAQController extends Controller
{

    public function index()
    {
        $faqs = FAQ::all();
        return response()->json($faqs);
    }


    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
        ]);

        $faq = FAQ::create($validatedData);


        return response()->json($faq, Response::HTTP_CREATED);
    }


    public function update(Request $request, FAQ $faq)
    {

        $validatedData = $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
        ]);

        $faq->update($validatedData);


        return response()->json($faq);
    }


    public function destroy(FAQ $faq)
    {
        $faq->delete();

        return response()->json(['message' => 'FAQ deleted successfully'], Response::HTTP_OK);
    }
}
