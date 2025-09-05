<?php

return [
    'api_key' => env('FIREBLOCKS_API_KEY', ''),
    'secret_key' => env('FIREBLOCKS_SECRET_KEY', ''),
    'base_path' => env('FIREBLOCKS_BASE_PATH', 'https://api.fireblocks.io/v1'),
    'is_anonymous_platform' => env('FIREBLOCKS_ANONYMOUS_PLATFORM', false),
    'user_agent' => env('FIREBLOCKS_USER_AGENT', null),
    'thread_pool_size' => env('FIREBLOCKS_THREAD_POOL_SIZE', 10),
    'default_headers' => [],
    'temp_folder_path' => env('FIREBLOCKS_TEMP_FOLDER', null),
    'timeout' => env('FIREBLOCKS_TIMEOUT', 30),
    'connect_timeout' => env('FIREBLOCKS_CONNECT_TIMEOUT', 10),
    'verify_ssl' => env('FIREBLOCKS_VERIFY_SSL', true),
    'debug' => env('FIREBLOCKS_DEBUG', false),
];