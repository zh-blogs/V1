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
        $page   = $request->input('page', 1);    // 每页显示条数
        $limit  = $request->input('limit', 10);  // 偏移量
        $search = $request->input('search', ''); // 搜索关键字
        $tag    = $request->input('tag', '');    // 标签

        if (!is_numeric($page) || !is_numeric($limit)) {
            return api(-1, 'invlid params');
        }
        if ($page < 1 || $limit < 1) {
            return api(-1, 'invlid params');
        }
        if ($limit > 30) $limit = 30;

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

        $pages = ceil($count / $limit);

        return api(data: [
            'pages' => $pages,
            'count' => $count,
            'data' => $data,
        ]);
    }

    /**
     * 获取随机博客
     *
     * @param Request $request
     * @return Response
     */
    public static function random(Request $request): Response
    {
        $limit = $request->input('limit', 10);
        if ($limit > 20) $limit = 20;
        if (!is_numeric($limit) || $limit < 1) {
            return api(-2, 'invlid params');
        }

        $sql = Db::table('blogs')->select('*')->orderBy(DB::raw('RAND()'))->limit($limit);
        $data = $sql->get();

        foreach ($data as &$item) {
            $item->tags = implode(',', BlogHelper::getTagByBlogId($item->id));
        }

        return api(data: $data);
    }

    /**
     * 获取tags
     *
     * @param Request $request
     * @return Response
     */
    public static function tags(Request $request): Response
    {
        $page   = $request->input('page', 1);    // 每页显示条数
        $limit  = $request->input('limit', 100);  // 偏移量

        if (!is_numeric($page) || !is_numeric($limit)) {
            return api(-1, 'invlid params');
        }
        if ($page < 1 || $limit < 1) {
            return api(-2, 'invlid params');
        }
        if ($limit > 100) $limit = 100;

        $sql = Db::table('tag_map')->select('*')->forPage($page, $limit);
        $data = $sql->get();

        return api(data: $data);
    }
}
