<?php

return [
    'api_key' => env('FIREBLOCKS_API_KEY', ''),
    'secret_key' => env('FIREBLOCKS_SECRET_KEY', ''),
    'base_path' => env('FIREBLOCKS_BASE_PATH', 'https://api.fireblocks.io/v1'),

    'additional_options' => [
        'is_anonymous_platform' => env('FIREBLOCKS_ANONYMOUS_PLATFORM', false),
        'user_agent' => env('FIREBLOCKS_USER_AGENT', null),
        'thread_pool_size' => env('FIREBLOCKS_THREAD_POOL_SIZE', 10),
    ],

    'http_options' => [
        'timeout' => env('FIREBLOCKS_TIMEOUT', 30),
        'connect_timeout' => env('FIREBLOCKS_CONNECT_TIMEOUT', 10),
        'verify' => env('FIREBLOCKS_VERIFY_SSL', true),
        'debug' => env('FIREBLOCKS_DEBUG', false),
    ],
];