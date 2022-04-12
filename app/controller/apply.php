<?php

namespace app\controller;

use Webman\Http\Response;
use Webman\Http\Request;
use support\Redis;

class Apply
{
    /**
     * 申请加入
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $id = '';

        return api();
    }
}

/* 
 
    "blog": {
        "id": "6106151d-4536-43e3-85e6-9d1a0294b2e6",
        "name": "https://exmaple.com",
        "url": "htt://exmaple.com",
        "sign": "https://exmaple.com",
        "logo": "https://exmaple.com",
        "feed": "https://exmaple.com",
        "status": "unknown",
        "repeat": false,
        "enabled": false
        }
    }
 */