<?php

use Webman\Http\Request;


/**
 * API格式化输出
 *
 * @param boolean $success
 * @param string $msg
 * @param array $data
 * @return \support\Response
 */
function api(bool $success = true, string $msg = 'ok', array|object $data = []): \support\Response
{
    return json([
        'sucess' => $success,
        'msg'    => $msg,
        'data' => $data,
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}

/**
 * get user ip
 *
 * @param Request $request
 * @return String
 */
function getIp(Request $request): String
{
    $ip = $request->header('cf-connecting-ip') ?? $request->header('x-forwarded-for');
    if (!$ip) $ip = $request->header('x-real-ip');
    return $ip;
}
