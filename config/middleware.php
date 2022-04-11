<?php


return [
    "" => [
        app\middleware\AccessControl::class,
        app\middleware\RateLimit::class,
    ],
    "manage" => [
        app\manage\middleware\init::class,
    ]
];
