<?php

use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\PropertyController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\PropertyInquiryController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\NewsPostController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SubscriberController;
use Illuminate\Http\Request;

Route::options('/{any}', function (Request $request) {
    return response()->json(['status' => 'OK'], 200);
})->where('any', '.*');

Route::post('/subscribe', [SubscriberController::class, 'subscribe']);

Route::get('/about-us', [AboutUsController::class, 'index']); // ✅ Fetch About Us
Route::post('/about-us', [AboutUsController::class, 'store']); // ✅ Create or Update About Us
Route::delete('/about-us', [AboutUsController::class, 'destroy']); // ✅ Delete About Us

Route::get('/job-applications/stats', [JobApplicationController::class, 'getStats']);
Route::get('/properties/stats', [PropertyController::class, 'getStats']);
Route::get('/inquiries/stats', [InquiryController::class, 'getInquiryStats']);

Route::post('/properties', [PropertyController::class, 'store']);
Route::get('/notifications', [NotificationController::class, 'getNotifications']);
Route::post('/notifications', [NotificationController::class, 'store']);
Route::post('/notifications/{id}/mark-read', [NotificationController::class, 'markAsRead']);
Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead']);
Route::delete('/notifications/{id}', [NotificationController::class, 'deleteNotification']);
Route::post('/notifications/restore', [NotificationController::class, 'restoreNotification']);

Route::get('/notifications/appointments', [NotificationController::class, 'getAppointmentNotifications']);
Route::get('/notifications/job-applications', [NotificationController::class, 'getJobApplicationNotifications']);
Route::get('/notifications/property-inquiries', [NotificationController::class, 'getPropertyInquiryNotifications']);
Route::get('/notifications/inquiries', [NotificationController::class, 'getInquiryNotifications']);

Route::get('/services', [ServiceController::class, 'index']); // Fetch all services
Route::post('/services', [ServiceController::class, 'store']); // Create a service
Route::put('/services/{id}', [ServiceController::class, 'update']); // Update a service
Route::delete('/services/{id}', [ServiceController::class, 'destroy']); // Delete a service

Route::get('/news', [NewsPostController::class, 'index']);
Route::get('/news/{id}', [NewsPostController::class, 'show']);
Route::post('/news', [NewsPostController::class, 'store']);
Route::post('/news/{id}', [NewsPostController::class, 'update']);
Route::delete('/news/{id}', [NewsPostController::class, 'destroy']);

Route::get('/appointments', [AppointmentController::class, 'index']);
Route::post('/appointments', [AppointmentController::class, 'store']);
Route::post('/appointments/{id}/reply', [AppointmentController::class, 'reply']);
Route::post('/appointments/{id}/archive', [AppointmentController::class, 'archive']);
Route::post('/appointments/{id}/unarchive', [AppointmentController::class, 'unarchive']);
Route::delete('/appointments/{id}', [AppointmentController::class, 'destroy']);

Route::post('/property-inquiries/{id}/reply', [PropertyInquiryController::class, 'reply']); // Reply via email
Route::post('/property-inquiries/{id}/archive', [PropertyInquiryController::class, 'archive']); // Archive inquiry
Route::post('/property-inquiries/{id}/unarchive', [PropertyInquiryController::class, 'unarchive']); // Unarchive inquiry
Route::delete('/property-inquiries/{id}', [PropertyInquiryController::class, 'destroy']); // Delete inquiry
Route::post('/property-inquiries', [PropertyInquiryController::class, 'store']);
Route::get('/property-inquiries', [PropertyInquiryController::class, 'index']);

Route::put('/inquiries/{id}/unarchive', [InquiryController::class, 'unarchive']);
Route::post('/inquiries/{id}/reply', [InquiryController::class, 'reply']);
Route::put('/inquiries/{id}/archive', [InquiryController::class, 'archive']);
Route::delete('/inquiries/{id}', [InquiryController::class, 'destroy']);
Route::post('/inquiries', [InquiryController::class, 'store']); // Store Inquiry
Route::get('/inquiries', [InquiryController::class, 'index']); // Fetch All Inquiries

Route::post('/job-applicants/{id}/approve', [JobApplicationController::class, 'approve']);
Route::post('/job-applicants/{id}/reject', [JobApplicationController::class, 'reject']);
Route::delete('/job-applicants/{id}', [JobApplicationController::class, 'destroy']);
Route::get('/job-applicants', [JobApplicationController::class, 'index']);
Route::post('/job-applications', [JobApplicationController::class, 'store']);
Route::get('/job-applications', [JobApplicationController::class, 'index']);

Route::post('/jobs', [JobController::class, 'store']);
Route::get('/jobs', [JobController::class, 'index']);
Route::post('/jobs/{id}', [JobController::class, 'update']);
Route::delete('/jobs/{id}', [JobController::class, 'destroy']);
Route::get('/jobs', [JobController::class, 'featuredJobs']);
Route::get('/jobs/all', [JobController::class, 'fetchJobs']); // ✅ New API endpoint

// ✅ Admin Login
Route::middleware("guest")->post("/login", [AuthController::class, "login"]);

// ✅ Forgot Password (Public Route)
Route::post("/forgot-password", [AuthController::class, "forgotPassword"]);

Route::post("/reset-password", [AuthController::class, "resetPassword"]);

// ✅ Apply session timeout middleware to protected routes
Route::middleware(['auth:sanctum', 'session.timeout'])->group(function () {
    Route::get("/user", [AuthController::class, "user"]);
    Route::post("/logout", [AuthController::class, "logout"]);
});

Route::apiResource('properties', PropertyController::class);
Route::patch('/properties/{id}/update-status', [PropertyController::class, 'updatePropertyStatus']);
Route::get('/properties/{id}', [PropertyController::class, 'show']);
Route::post('/properties/{id}/track-view', [PropertyController::class, 'trackView']); // ✅ NEW Route

