<?php

namespace app\manage\controller;

use Webman\Http\Response;
use Webman\Http\Request;
use support\Redis;

class Status
{
    public function index(Request $request): Response
    {
        $role = $request->role;
        $user_id = $request->userid;
        Redis::expire($request->redisKey, 3600 * 2);
        return api(data: [
            'role' => $role,
            'user_id' => $user_id,
        ]);
    }
}
