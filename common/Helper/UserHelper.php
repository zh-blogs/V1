<?php

namespace common\Helper;

use support\Redis;

class UserHelper
{
    public static $user_key_prefix = 'zh:login:';

    /**
     * 判断用户是否登录
     *
     * @param string $token
     * @return array|boolean
     */
    public static function status(string $token): array|bool
    {
        $arr = Redis::hGetAll(static::$user_key_prefix . md5($token));
        if (!$arr) return false;

        // 续期
        Redis::expire(static::$user_key_prefix . md5($token), 3600 * 2);
        return $arr;
    }
}
