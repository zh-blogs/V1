<?php

use Webman\Http\Request;
use Webman\Route;

// 404 Route
Route::fallback(function (Request $request) {
    return api(
        404,
        'Not Found',
    )->withHeaders([
        'Access-Control-Allow-Origin' =>  '*',
        'Server' => getenv('SERVER_NAME', 'zhblogs'),
    ])->withStatus(404);
});
