<?php

return [
    'default' => [
        'host' =>  getenv('REDIS_HOST', '127.0.0.1'),
        'password' => getenv('REDIS_PASS', '127.0.0.1'),
        'port' => (int)getenv('REDIS_PORT', 6379),
        'database' => (int)getenv('REDIS_DB', 0),
    ],
];
