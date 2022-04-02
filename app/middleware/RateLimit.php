<?php

namespace app\middleware;

use common\Ip;
use Webman\MiddlewareInterface;
use Webman\Http\{Response, Request};

use RateLimit\{Rate, RateLimiter};

/**
 * 速率限制
 */
class RateLimit implements MiddlewareInterface
{
    public function process(Request $request, callable $next): Response
    {
        $redis = new \Redis();
        $redis->connect(config('redis.default.host'), config('redis.default.port'));
        $redis->select(config('redis.default.database'));

        $user_agent = $request->header('user-agent');

        $limit = Rate::perMinute(120);
        $rateLimiter = new RateLimiter($limit, $redis, 'zh:rate_limit:');
        $status = $rateLimiter->limitSilently(md5(getIp($request) . $user_agent));

        if ($status->left() === 0) return api(-1, 'Exceeded rate limit');

        return $next($request);
    }
}
