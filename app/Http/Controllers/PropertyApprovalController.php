<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;

class PropertyApprovalController extends Controller
{

    public function index()
    {
        $pendingProperties = Property::where('status', 'pending')->get();

        return response()->json([
            'count' => $pendingProperties->count(),
            'data' => $pendingProperties
        ], 200);
    }



    public function approve($id)
    {
        $property = Property::findOrFail($id);
        $property->update(['status' => 'approved']);

        return response()->json(['message' => 'Property approved successfully', 'property' => $property]);
    }


    public function reject($id)
    {
        $property = Property::findOrFail($id);
        $property->update(['status' => 'rejected']);

        return response()->json(['message' => 'Property rejected successfully', 'property' => $property]);
    }
}
