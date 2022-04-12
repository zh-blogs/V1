<?php


return [
    "" => [
        app\middleware\AccessControl::class,
        app\middleware\RateLimit::class,
    ],
    "admin" => [
        app\admin\middleware\init::class,
    ]
];
