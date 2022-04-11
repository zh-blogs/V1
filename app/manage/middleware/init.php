<?php

namespace app\manage\middleware;

use Webman\MiddlewareInterface;
use Webman\Http\{Response, Request};

/**
 * manage init
 */
class Init implements MiddlewareInterface
{
    public function process(Request $request, callable $next): Response
    {
        $authorization  = $this->request->header('Authorization', '');

        $token = '';
        if (preg_match('/^Bearer\s+(.*)$/', $authorization, $matches)) {
            $token = $matches[1];
        }
        if ($token === '') {
            $request->isLogin = false;
            $request->role = '';
            $request->userid = -1;
            return $next($request);
        }

        return $next($request);
    }
}
