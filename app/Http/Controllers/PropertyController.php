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
use Illuminate\Support\Facades\Log;

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
            'type_of_listing' => 'sometimes|required|string|max:255',
            'property_image' => 'sometimes|array',
            'property_image.*' => 'image|mimes:jpeg,png,jpg,gif,svg',
            'features' => 'sometimes|string',
            'status' => 'in:pending,approved,rejected',
            'other_details' => 'sometimes|nullable|json', 
            'other_details.*' => 'string|max:255', 
        ]);

        $data['type_of_listing'] = $request->type_of_listing; 

        $validated['status'] = $request->input('status', 'pending'); 

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        $data['other_details'] = is_string($request->other_details)
            ? json_decode($request->other_details, true) 
            : $request->other_details; 


    
        $data['features'] = $request->has('features') ? json_decode($request->features, true) : [];


       
        $booleanFields = [
            'pool_area',
            'guest_suite',
            'underground_parking',
            'pet_friendly_facilities',
            'balcony_terrace',
            'club_house',
            'gym_fitness_center',
            'elevator',
            'concierge_services',
            'security'
        ];

        foreach ($booleanFields as $field) {
          
            $data[$field] = in_array(ucwords(str_replace("_", " ", $field)), $data['features']);
        }

     
        $imagePaths = [];
        if ($request->hasFile('property_image')) {
            foreach ($request->file('property_image') as $image) {
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('storage/properties'), $filename);
                $imagePaths[] = asset('storage/properties/' . $filename);
            }
        }

        $data['property_image'] = $imagePaths; 

        $property = Property::create($data);

      
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
            'type_of_listing' => 'sometimes|required|string|max:255',
            'property_image.*' => 'image|mimes:jpeg,png,jpg,gif,svg',
            'features' => 'sometimes|string',
            'status' => 'in:pending,approved,rejected', 
            'other_details' => 'sometimes|required|string', 
            'other_details.*' => 'string|max:255', 
        ]);



        $data['type_of_listing'] = $request->type_of_listing; 
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();


        $data['other_details'] = is_string($request->other_details)
            ? json_decode($request->other_details, true) 
            : $request->other_details; 




 
        $booleanFields = [
            'pool_area',
            'guest_suite',
            'underground_parking',
            'pet_friendly_facilities',
            'balcony_terrace',
            'club_house',
            'gym_fitness_center',
            'elevator',
            'concierge_services',
            'security'
        ];

        foreach ($booleanFields as $field) {
            $data[$field] = filter_var($request->input($field, $property->$field ?? false), FILTER_VALIDATE_BOOLEAN);
        }

        if ($request->hasFile('property_image')) {
            if ($property->property_images) {
                foreach ($property->property_images as $oldImage) {
                    $filePath = str_replace(asset('/'), '', $oldImage);
                    Storage::delete($filePath);
                }
            }

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

        $existingView = PropertyView::where('property_id', $id)
            ->where('ip_address', $ip)
            ->exists();

        if (!$existingView) {
            PropertyView::create([
                'property_id' => $id,
                'ip_address' => $ip,
            ]);
        }

        $uniqueViews = PropertyView::where('property_id', $id)->count();

        return response()->json(['unique_views' => $uniqueViews]);
    }


    public function updatePropertyStatus(Request $request, $id)
    {
        $property = Property::findOrFail($id);
        $status = $request->status;
        $reason = $request->reason ?? null;

        if (!$property->email) {
            return response()->json(['message' => 'Error: Property owner email not found'], 400);
        }

        $property->status = $status;
        $property->save();

        if ($status === 'approved') {
            Mail::to($property->email)->send(new PropertyApprovedMail($property));
            $this->sendNewPropertyEmail($property);
        } elseif ($status === 'rejected' && $reason) {
            Mail::to($property->email)->send(new PropertyRejectedMail($property, $reason));
        }

        return response()->json(['message' => "Property $status successfully."]);
    }

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
        $total = Property::count();

        $forSale = Property::where('type_of_listing', 'LIKE', '%For Sale%')->count();
        $forRent = Property::where('type_of_listing', 'LIKE', '%For Rent%')->count();

        $uniqueViews = \App\Models\PropertyView::distinct('ip_address')->count();

        $totalViews = \App\Models\PropertyView::count();

        $mostViewedProperty = Property::withCount('views')
            ->orderByDesc('views_count') 
            ->orderByDesc('created_at')  
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
                    ? ($mostViewedProperty->property_image[0] ?? null) 
                    : $mostViewedProperty->property_image 
            ] : null,
        ]);
    }

    public function suggestProperties(Request $request)
{
    $validator = Validator::make($request->all(), [
        'budget' => 'required|numeric|min:0',
        'unit_type' => 'nullable|string' // e.g., 'Studio Type', '1BR', etc.
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    $budget = $request->input('budget');
    $unitType = $request->input('unit_type');

    $query = Property::query()
        ->where('status', 'approved')
        ->where('price', '<=', $budget);

    if (!empty($unitType)) {
        $query->where('unit_type', 'LIKE', "%$unitType%");
    }

    $suggestedProperties = $query->orderBy('price', 'asc')
        ->limit(5)
        ->get();

    if ($suggestedProperties->isEmpty()) {
        return response()->json([
            'message' => 'No matching properties found. Try adjusting your filters.'
        ], 404);
    }

    return response()->json($suggestedProperties);
}


}
