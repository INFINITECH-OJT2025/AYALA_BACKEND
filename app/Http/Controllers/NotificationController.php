<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\Property;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{

    public function getNotifications()
    {
        $notifications = Notification::orderBy('created_at', 'desc')->get();

        return response()->json(
            $notifications->map(function ($notif) {
                return [
                    'id' => $notif->id,
                    'message' => $notif->message,
                    'type' => $notif->type,
                    'is_read' => $notif->is_read,
                    'created_at' => $notif->created_at,
                ];
            })
        );
    }



    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->update(['is_read' => 'read']);

        return response()->json(['message' => 'Notification marked as read.']);
    }

    public function markAllAsRead()
    {
        $updated = Notification::where('is_read', '!=', 'read')->update(['is_read' => 'read']);

        return response()->json([
            'message' => 'All notifications marked as read.',
            'updated_count' => $updated
        ]);
    }



    public function deleteNotification($id)
    {
        Notification::findOrFail($id)->delete();
        return response()->json(['message' => 'Notification deleted.']);
    }

    public function restoreNotification(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'type' => 'required|string|in:success,error,info',
            'is_read' => 'nullable|string|in:read,unread',
        ]);

        $notification = Notification::create([
            'message' => $request->message,
            'type' => $request->type,
            'is_read' => $request->is_read ?? 'unread',
        ]);

        return response()->json($notification, 201);
    }




    public function store(Request $request)
    {
        // ✅ Validate input
        $request->validate([
            'property_name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'price' => 'required|numeric',
        ]);

        $property = Property::create([
            'property_name' => $request->property_name,
            'location' => $request->location,
            'price' => $request->price,
            'status' => 'pending',
        ]);

        Notification::create([
            'message' => "New property submitted: {$property->property_name}",
            'type' => 'success',
            'is_read' => false,
        ]);

        return response()->json([
            'message' => 'Property submitted successfully!',
            'property' => $property
        ], 201);
    }

    public function getJobApplicationNotifications()
    {
        return response()->json(
            Notification::where('message', 'LIKE', '%job application%')
                ->orderBy('created_at', 'desc')
                ->get()
        );
    }

    public function getPropertyInquiryNotifications()
    {
        return response()->json(
            Notification::where('message', 'LIKE', '%property inquiry%')
                ->orderBy('created_at', 'desc')
                ->get()
        );
    }

    public function getAppointmentNotifications()
    {
        return response()->json(
            Notification::where('message', 'LIKE', '%appointment booked%')
                ->orderBy('created_at', 'desc')
                ->get()
        );
    }

    public function getInquiryNotifications()
    {
        return response()->json(
            Notification::where('message', 'LIKE', '%general inquiry%')
                ->orderBy('created_at', 'desc')
                ->get()
        );
    }
}
