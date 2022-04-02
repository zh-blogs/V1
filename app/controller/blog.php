<?php

namespace app\controller;

use Webman\Http\Request;
use Webman\Http\Response;
use Support\Db;

class Blog
{

    /**
     * 获取博客列表
     *
     * @param Request $request
     * @return Response
     */
    public function list(Request $request): Response
    {
        $page  = $request->input('page', 1);  // 每页显示条数
        $limit = $request->input('limit', 10);  // 偏移量
        $search = $request->input('search', ''); // 搜索关键字

        $data = Db::table('blogs')->select('*')->forPage($page, $limit)->whereRaw("MATCH (hobbies) AGAINST ('soccer' IN BOOLEAN MODE)")->get();

        return api(data: $data);
    }
}
