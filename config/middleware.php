<?php


return [
    "" => [
        app\middleware\AccessControl::class,
        app\middleware\RateLimit::class,
    ]
];
