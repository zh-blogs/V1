<?php

use Webman\Http\Request;
use Webman\Route;


// 404 Route
Route::fallback(function (Request $request) {
    return api(
        404,
        'Not Found',
        [
            'url' => $request->url(),
            'method' => $request->method(),
        ]
    )->withHeader('Access-Control-Allow-Origin', '*')->withStatus(404);
});
