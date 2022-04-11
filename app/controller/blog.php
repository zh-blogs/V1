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

        $tag    = $request->input('tag');    // 标签
        $tags   = explode(',', $tag);            // tags

        // fittler
        if (!is_numeric($page) || !is_numeric($limit) || $page < 1 || $limit < 1) {
            return api(false, 'invlid params');
        }
        if ($limit > 30) $limit = 30;

        // get data
        $sql = Db::table('blog')->select('*')->forPage($page, $limit);
        if ($search !== '') {
            $sql = $sql->where('name', 'like', '%' . $search . '%')
                ->orWhere('url', 'like', '%' . $search . '%');
        }
        $data = $sql->get();

        // process
        foreach ($data as &$item) {
            $item->tags = BlogHelper::getTagByBlogId($item->idx);
            $item->enabled = $item->enabled === 1 ? true : false;
        }

        return api(data: $data);
    }

    /**
     * 获取博客数量
     *
     * @param Request $request
     * @return Response
     */
    public function count(Request $request): Response
    {
        $search = $request->input('search', ''); // 搜索关键字

        $tag    = $request->input('tag', '');    // 标签
        $tags   = explode(',', $tag);            // tags

        // get count
        $countSql = Db::table('blog');
        if ($search !== '') {
            $countSql = $countSql->where('name', 'like', '%' . $search . '%')
                ->orWhere('url', 'like', '%' . $search . '%');
        }
        $count = $countSql->count();

        return api(data: [
            'count' =>  $count
        ]);
    }

    /**
     * 获取随机博客
     *
     * @param Request $request
     * @return Response
     */
    public function random(Request $request): Response
    {
        $limit = $request->input('limit', 10);
        if ($limit > 20) $limit = 20;
        if (!is_numeric($limit) || $limit < 1) {
            return api(false, 'invlid params');
        }

        $data = Db::table('blog')->select('*')->inRandomOrder()->take($limit)->get();

        foreach ($data as &$item) {
            $item->tags = BlogHelper::getTagByBlogId($item->idx);
            $item->enabled = $item->enabled === 1 ? true : false;
        }

        return api(data: $data);
    }

    /**
     * 获取tags
     *
     * @param Request $request
     * @return Response
     */
    public function tag(Request $request): Response
    {
        $data = Db::table('tag_map')->select('*')->get();

        return api(data: $data);
    }
}
