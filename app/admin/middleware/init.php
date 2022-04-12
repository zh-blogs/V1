<?php

namespace app\admin\middleware;

use Webman\MiddlewareInterface;
use Webman\Http\{Response, Request};
use common\Helper\UserHelper;

/**
 * admin init
 */
class Init implements MiddlewareInterface
{
    public function process(Request $request, callable $next): Response
    {
        $authorization  = $request->header('Authorization', '');

        preg_match('/^Bearer\s+(.*)$/', $authorization, $matches);
        $token = $matches[1] ?? '';

        if ($token === '') return api(false, 'user not login');

        // 验证token合法性
        $arr = UserHelper::status($token);
        if ($arr === false) return api(false, 'user not login');

        $request->role = (int)$arr['role'];
        $request->userid = (int)$arr['userid'];
        $request->token = $token;
        $request->redisKey = UserHelper::$user_key_prefix . md5($token);

        // 判断是否为admin
        if ($request->role !== 1) return api(false, 'permission denied');

        return $next($request);
    }
}
