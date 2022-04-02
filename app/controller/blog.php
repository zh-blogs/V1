<?php

namespace app\controller;

use common\BlogHelper;
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
        $page   = $request->input('page', 1);  // 每页显示条数
        $limit  = $request->input('limit', 10);  // 偏移量
        $search = $request->input('search', ''); // 搜索关键字

        // get count
        $countSql = Db::table('blogs');
        if ($search !== '') {
            $countSql = $countSql->where('name', 'like', '%' . $search . '%')
                ->orWhere('url', 'like', '%' . $search . '%');
        }
        $count = $countSql->count();

        // get data
        $sql = Db::table('blogs')->select('*')->forPage($page, $limit);
        if ($search !== '') {
            $sql = $sql->where('name', 'like', '%' . $search . '%')
                ->orWhere('url', 'like', '%' . $search . '%');
        }
        $data = $sql->get();

        // getTag
        foreach ($data as &$item) {
            $item->tags = implode(',', BlogHelper::getTagByBlogId($item->id));
        }

        return api(data: [
            'data' => $data,
            'count' => $count
        ]);
    }
}
