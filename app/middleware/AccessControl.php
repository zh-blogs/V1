<?php

namespace app\middleware;

use Webman\MiddlewareInterface;
use Webman\Http\Response;
use Webman\Http\Request;

/**
 * 设置跨域请求
 */
class AccessControl implements MiddlewareInterface
{
    public function process(Request $request, callable $next): Response
    {
        $response = $request->method() == 'OPTIONS' ? new Response(204, [], '') : $next($request);

        $response->withHeaders([
            'Access-Control-Allow-Origin' => '*',
            'Content-Type' => "Application/json; charset=utf-8",
            'Server' => 'zhblog'
        ]);

        return $response;
    }
}
