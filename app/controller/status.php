<?php

namespace app\controller;

use Webman\Http\Response;
use Webman\Http\Request;
use support\Redis;

class Status
{
    public function index(Request $request): Response
    {
        $authorization  = $request->header('Authorization', '');

        preg_match('/^Bearer\s+(.*)$/', $authorization, $matches);
        $token = $matches[1] ?? '';

        if ($token == '') return api(false, 'user not login');

        // 验证token合法性
        $arr = Redis::hGetAll('zh:login:' . md5($token));
        if (!$arr) {
            return api(false, 'user not login');
        }
        Redis::expire('zh:login:' . md5($token), 3600 * 2);
        return api(data: [
            'role' => (int)$arr['role'] ?? -1,
            'user_id' => (int)$arr['userid'] ?? -1,
        ]);
    }
}
