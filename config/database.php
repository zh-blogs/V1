<?php

return [
    // 默认数据库
    'default' => 'mysql',

    // 各种数据库配置
    'connections' => [
        'mysql' => [
            'driver'      => 'mysql',
            'host'        => getenv('MYSQL_HOST', '127.0.0.1'),
            'port'        => (int)getenv('MYSQL_PORT', 3306),
            'database'    => getenv('MYSQL_DB', 'zhblogs'),
            'username'    => getenv('MYSQL_USER', 'zhblogs'),
            'password'    => getenv('MYSQL_PASS', 'zhblogs'),
            'unix_socket' => '',
            'charset'     => 'utf8mb4',
            'collation'   => 'utf8mb4_unicode_ci',
            'prefix'      => '',
            'strict'      => true,
            'engine'      => null,
        ],
    ],
];
