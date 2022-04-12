<?php

namespace app\controller;

use Webman\Http\Response;
use Webman\Http\Request;
use support\Redis;
use common\Helper\UserHelper;

class Status
{
    /**
     * 获取登录状态
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $authorization  = $request->header('Authorization', '');

        preg_match('/^Bearer\s+(.*)$/', $authorization, $matches);
        $token = $matches[1] ?? '';

        if ($token == '') return api(false, 'user not login');

        // 验证token合法性
        $arr = UserHelper::status($token);
        if ($arr === false) return api(false, 'user not login');
        return api(data: [
            'role' => (int)$arr['role'],
            'user_id' => (int)$arr['userid'],
        ]);
    }
}
