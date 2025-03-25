<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\PropertyApprovedMail;
use App\Mail\PropertyRejectedMail;
use App\Models\Notification;

class PropertyController extends Controller
{
    public function index()
    {
        return response()->json(Property::with('views')->get());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name'     => 'required|string|max:255',
            'last_name'      => 'required|string|max:255',
            'email'          => 'required|email',
            'phone_number'   => 'required|string|max:11',
            'property_name'  => 'required|string|max:255',
            'description'    => 'nullable|string',
            'location'       => 'required|string|max:255',
            'price'          => 'required|numeric|min:0',
            'square_meter'   => 'required|numeric|min:0',
            'floor_number'   => 'required|integer|min:0',
            'parking'        => 'sometimes|required|string|max:255',
            'unit_type'      => 'required|string',
            'unit_status'    => 'sometimes|required|string|max:255',
           'type_of_listing' => 'sometimes|required|string', 
            'property_image' => 'sometimes|array',  // Make images optional
            'property_image.*' => 'image|mimes:jpeg,png,jpg,gif,svg',
            'features' => 'sometimes|string', // Expect a JSON string
            'status' => 'in:pending,approved,rejected', // Ensure status is allowed
        ]);
        $validated['status'] = $request->input('status', 'pending'); // Default is pending
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        $data = $validator->validated();
        
    
        // ✅ Decode JSON features field into an array
        $data['features'] = $request->has('features') ? json_decode($request->features, true) : [];
        $data['type_of_listing'] = is_string($request->type_of_listing)
        ? json_decode($request->type_of_listing, true)
        : $request->type_of_listing;
    
        // ✅ Define all boolean fields and ensure they have a value (default: false)
        $booleanFields = [
            'pool_area', 'guest_suite', 'underground_parking', 'pet_friendly_facilities',
            'balcony_terrace', 'club_house', 'gym_fitness_center', 'elevator',
            'concierge_services', 'security'
        ];
    
        foreach ($booleanFields as $field) {
            // ✅ Check if the feature is in the selected features array
            $data[$field] = in_array(ucwords(str_replace("_", " ", $field)), $data['features']);
        }
        
        // ✅ Upload images to public storage and save paths
        $imagePaths = [];
        if ($request->hasFile('property_image')) {
            foreach ($request->file('property_image') as $image) {
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('storage/properties'), $filename);
                $imagePaths[] = asset('storage/properties/' . $filename);
            }
        }
    
        $data['property_image'] = $imagePaths; // Store as an array

        $property = Property::create($data);

        // ✅ Now, we can use $property in the notification
        Notification::create([
            'message' => "New property submitted: " . $property->property_name,
            'type' => 'success',
            'is_read' => 'unread',
        ]);

        return response()->json($property, 201);
    }
    
    public function update(Request $request, Property $property)
    {
        $validator = Validator::make($request->all(), [
            'first_name'     => 'sometimes|required|string|max:255',
            'last_name'      => 'sometimes|required|string|max:255',
            'email'          => 'sometimes|required|email',
            'phone_number'   => 'sometimes|required|string|max:11',
            'property_name'  => 'sometimes|required|string|max:255',
            'description'    => 'nullable|string',
            'location'       => 'sometimes|required|string|max:255',
            'price'          => 'sometimes|required|numeric|min:0',
            'square_meter'   => 'sometimes|required|numeric|min:0',
            'floor_number'   => 'sometimes|required|integer|min:0',
            'parking'        => 'sometimes|required|string|max:255',
            'unit_type'      => 'sometimes|required|string',
            'unit_status'    => 'sometimes|required|string|max:255',
            'type_of_listing' => 'sometimes|required|string', 
            'property_image.*' => 'image|mimes:jpeg,png,jpg,gif,svg',
            'features' => 'sometimes|string',
            'status' => 'in:pending,approved,rejected', // Ensure status is allowed
        ]);

        

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();
        $data['type_of_listing'] = is_string($request->type_of_listing)
        ? json_decode($request->type_of_listing, true)
        : $request->type_of_listing;


        // ✅ Ensure all boolean fields are properly set (default to false if not provided)
        $booleanFields = [
            'pool_area', 'guest_suite', 'underground_parking', 'pet_friendly_facilities',
            'balcony_terrace', 'club_house', 'gym_fitness_center', 'elevator',
            'concierge_services', 'security'
        ];

        foreach ($booleanFields as $field) {
            $data[$field] = filter_var($request->input($field, $property->$field ?? false), FILTER_VALIDATE_BOOLEAN);
        }

        // ✅ Delete old images if new ones are uploaded
        if ($request->hasFile('property_image')) {
            if ($property->property_images) {
                foreach ($property->property_images as $oldImage) {
                    $filePath = str_replace(asset('/'), '', $oldImage);
                    Storage::delete($filePath);
                }
            }

            // ✅ Store new images
            $imagePaths = [];
            foreach ($request->file('property_image') as $image) {
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('storage/properties'), $filename);
                $imagePaths[] = asset('storage/properties/' . $filename);
            }
            $data['property_image'] = $imagePaths;
        }

        $property->update($data);
        return response()->json($property);
    }

        public function destroy(Property $property)
        {
            if ($property->property_images) {
                foreach ($property->property_images as $image) {
                    $filePath = str_replace(asset('/'), '', $image);
                    Storage::delete($filePath);
                }
            }

            $property->delete();
            return response()->json(['message' => 'Property deleted successfully']);
        }        

        public function show($id)
        {
            $property = Property::find($id);

            if (!$property) {
                return response()->json(['message' => 'Property not found'], 404);
            }

            return response()->json($property);
        }



        public function trackView($id, Request $request)
        {
            
            $ip = $request->ip();

            // Check if the IP already exists for this property
            $existingView = PropertyView::where('property_id', $id)
                                        ->where('ip_address', $ip)
                                        ->exists();

            if (!$existingView) {
                // Save new unique view
                PropertyView::create([
                    'property_id' => $id,
                    'ip_address' => $ip,
                ]);
            }

            // Get the updated unique view count
            $uniqueViews = PropertyView::where('property_id', $id)->count();

            return response()->json(['unique_views' => $uniqueViews]);
        }

        
        public function updatePropertyStatus(Request $request, $id)
        {
            $property = Property::findOrFail($id);
            $status = $request->status;
            $reason = $request->reason ?? null;
        
            // Ensure property has a valid email
            if (!$property->email) {
                return response()->json(['message' => 'Error: Property owner email not found'], 400);
            }
        
            // Update property status
            $property->status = $status;
            $property->save();
        
            // Send email notifications
            if ($status === 'approved') {
                Mail::to($property->email)->send(new PropertyApprovedMail($property));
                $this->sendNewPropertyEmail($property);
            } elseif ($status === 'rejected' && $reason) {
                Mail::to($property->email)->send(new PropertyRejectedMail($property, $reason));
            }
        
            return response()->json(['message' => "Property $status successfully."]);
        }

        // ✅ Notify subscribers about new approved property
            private function sendNewPropertyEmail($property)
            {
                $subscribers = \App\Models\Subscriber::pluck('email');
                foreach ($subscribers as $email) {
                    Mail::to($email)->send(new \App\Mail\NewPropertyMail($property));
                }
            }

        

        // public function updateStatus($id, Request $request)
        // {
        //     $property = Property::find($id);
        //     if (!$property) {
        //         return response()->json(['message' => 'Property not found'], 404);
        //     }

        //     $request->validate([
        //         'status' => 'required|in:approved,rejected'
        //     ]);

        //     $property->status = $request->status;
        //     $property->save();

        //     return response()->json(['message' => 'Property status updated successfully']);
        // }

        public function getStats()
{
    // Get total properties
    $total = Property::count();

    // Get properties for sale
    $forSale = Property::whereJsonContains('type_of_listing', 'For Sale')->count();

    // Get properties for rent
    $forRent = Property::whereJsonContains('type_of_listing', 'For Rent')->count();

    // Get total unique views across all properties
    $uniqueViews = \App\Models\PropertyView::distinct('ip_address')->count();

    // Get total views from all IP addresses (including duplicates)
    $totalViews = \App\Models\PropertyView::count();

    // Get the most viewed property with tie-breaking logic (most recent wins if tied)
    $mostViewedProperty = Property::withCount('views')
        ->orderByDesc('views_count') // Sort by highest views
        ->orderByDesc('created_at')  // If tied, pick the most recent
        ->first();

    return response()->json([
        'total' => $total,
        'forSale' => $forSale,
        'forRent' => $forRent,
        'uniqueViews' => $uniqueViews,
        'totalViews' => $totalViews,
        'mostViewed' => $mostViewedProperty ? [
            'name' => $mostViewedProperty->property_name,
            'location' => $mostViewedProperty->location ?? 'Unknown',
            'price' => $mostViewedProperty->price ?? 0,
            'image' => is_array($mostViewedProperty->property_image) 
                ? $mostViewedProperty->property_image[0] // Get the first image if stored as an array
                : $mostViewedProperty->property_image // Otherwise, return as is
        ] : null,
    ]);
}


        
        

}

