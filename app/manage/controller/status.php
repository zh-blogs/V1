<?php

namespace app\manage\controller;

use Webman\Http\Response;
use Webman\Http\Request;

class Status
{
    public function index(Request $request): Response
    {
        return api();
    }
}
