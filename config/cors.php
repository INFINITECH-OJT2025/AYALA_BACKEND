<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'], // ✅ Ensure Sanctum CSRF is included
    'allowed_methods' => ['*'],
    'allowed_origins' => ['*'], // ✅ Allow all origins (only in development)
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true, // ✅ Important for session authentication
];
