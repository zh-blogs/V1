<?php

namespace common;

use support\Db;

class GithubHelper
{
    /**
     * 通过GithubId 获取 用户Id
     *
     * @param integer $github_id
     * @return integer|bool
     */
    public static function getUserId(int $github_id): int|bool
    {
        $user_id = Db::table('github')->select('user_id')->where('github_id', $github_id)->first();
        return $user_id ? $user_id->user_id : false;
    }

    /**
     * 获取用户信息
     *
     * @param integer $userId
     * @return object|boolean
     */
    public static function getUserInfo(int $userId): object|bool
    {
        $user = Db::table('user')->select('*')->where('id', $userId)->first();
        return $user ? $user : false;
    }

    /**
     * 用户注册
     *
     * @param integer $github_id
     * @return boolean
     */
    public static function register(int $github_id): bool
    {
        if (static::getUserId($github_id)) {
            return false;
        }
        try {
            Db::beginTransaction();

            Db::table('user')->insert([
                'site' => '',
                'role' => 0,
                'time' => time(),
            ]);
            //get lastinsertid
            $user_id = Db::getPdo()->lastInsertId();

            Db::table('github')->insert([
                'github_id' => $github_id,
                'user_id'   => $user_id,
            ]);

            Db::commit();
        } catch (\Throwable $e) {
            Db::rollBack();
            return false;
        }
        return true;
    }
}
