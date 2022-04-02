<?php

namespace  Blogs\Controller;

use Webman\Http\Request;
use Webman\Http\Response;
use Support\Db;

class Get
{


    /**
     * 获取博客列表
     *
     * @param Request $request
     * @return Response
     */
    public function list(Request $request): Response
    {
        return api();
    }
}
