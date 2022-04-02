<?php

use Webman\Http\Request;

/**
 * 处理API返回值
 * @param int       code
 * @param string    msg
 * @param array     data
 * @return \support\Response
 */
function api(int $code = 0, string $msg = 'success', array|object $data = []): \support\Response
{
    return json([
        'code' => $code,
        'msg' => $msg,
        'data' => $data,
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}

function getIp(Request $request): String
{
    $ip = $request->header('cf-connecting-ip') ?? $request->header('x-forwarded-for');
    if (!$ip) $ip = $request->header('x-real-ip');
    return $ip;
}
