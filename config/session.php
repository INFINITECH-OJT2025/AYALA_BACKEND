<?php
use Illuminate\Support\Str;

return [
    'driver' => env('SESSION_DRIVER', 'file'), // Use 'cookie' or 'database' in production

    'lifetime' => env('SESSION_LIFETIME', 360), // ✅ Set session lifetime to 6 hours (360 minutes)

    'expire_on_close' => false, // ❌ Prevents logout when the browser is closed

    'encrypt' => true, // ✅ Encrypt session data for security

    'files' => storage_path('framework/sessions'),

    'connection' => env('SESSION_CONNECTION', null),

    'table' => 'sessions', // ✅ Required if using 'database' driver

    'store' => env('SESSION_STORE', null),

    'lottery' => [2, 100],

    'cookie' => env(
        'SESSION_COOKIE',
        Str::slug(env('APP_NAME', 'laravel'), '_') . '_session'
    ),

    'path' => '/',

    'domain' => env('SESSION_DOMAIN', null),

    'secure' => env('SESSION_SECURE_COOKIE', true), // ✅ Use secure cookies (HTTPS required in production)

    'http_only' => true, // ✅ Prevents JavaScript access to session cookies

    'same_site' => 'lax', // ✅ Allows cross-site requests but protects against CSRF
];
