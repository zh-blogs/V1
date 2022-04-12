<?php

namespace app\admin\middleware;

use Webman\MiddlewareInterface;
use Webman\Http\{Response, Request};
use \support\Redis;

/**
 * manage init
 */
class Init implements MiddlewareInterface
{
    public function process(Request $request, callable $next): Response
    {
        $authorization  = $request->header('Authorization', '');

        preg_match('/^Bearer\s+(.*)$/', $authorization, $matches);
        $token = $matches[1] ?? '';

        if ($token == '') {
            return api(false, 'user not login');
        }

        // 验证token合法性
        $arr = Redis::hGetAll('zh:login:' . md5($token));
        if (!$arr) {
            return api(false, 'user not login');
        }
        $request->role = (int)$arr['role'] ?? -1;
        $request->userid = (int)$arr['userid'] ?? -1;
        $request->token = $token;
        $request->redisKey = 'zh:login:' . md5($token);

        return $next($request);
    }
}
