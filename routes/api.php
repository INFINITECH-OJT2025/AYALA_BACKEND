<?php

use App\Http\Controllers\PropertyController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::post('/login', [AuthController::class, 'login']);
Route::apiResource('properties', PropertyController::class);
Route::get('/users', [UserController::class, 'index']);
Route::post('/register', [UserController::class, 'register']); // Register new users
