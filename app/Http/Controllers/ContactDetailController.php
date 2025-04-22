<?php

namespace App\Http\Controllers;

use App\Models\ContactDetail;
use Illuminate\Http\Request;

class ContactDetailController extends Controller
{

    public function index()
    {
        $contact = ContactDetail::first();
        return response()->json($contact);
    }


    public function update(Request $request)
    {
        $validated = $request->validate([
            'phones' => 'nullable|array',
            'email' => 'nullable|email',
            'social_media' => 'nullable|array',
            'location' => 'nullable|string|max:255',
        ]);


        $contact = ContactDetail::first();
        if (!$contact) {
            $contact = new ContactDetail();
        }

        $contact->fill($validated);
        $contact->save();

        return response()->json(['message' => 'Contact details updated successfully', 'contact' => $contact]);
    }
}
