<?php

return [
    'paths' => ['api/*', 'job_images/*'], // Allow job_images folder
    'allowed_methods' => ['*'],
    'allowed_origins' => ['*'], // ✅ Allow Next.js frontend
    'allowed_headers' => ['*'],
    'exposed_headers' => ['*'],
    'max_age' => 0,
    'supports_credentials' => false,
];
