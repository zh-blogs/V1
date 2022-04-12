<?php

namespace app\admin\controller;

use Webman\Http\Response;
use Webman\Http\Request;
use support\Redis;

class Blog
{
    /**
     * 删除博客
     *
     * @param Request $request
     * @return Response
     */
    public function del(Request $request): Response
    {
        $role = $request->role;
        $user_id = $request->userid;
        Redis::expire($request->redisKey, 3600 * 2);
        return api(data: [
            'role' => 111111,
            'user_id' => $user_id,
        ]);
    }

    /**
     * 更新博客
     *
     * @param Request $request
     * @return Response
     */
    public function update(Request $request): Response
    {
        $role = $request->role;
        $user_id = $request->userid;
        Redis::expire($request->redisKey, 3600 * 2);
        return api(data: [
            'role' => 111111,
            'user_id' => $user_id,
        ]);
    }
}
