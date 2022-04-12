<?php

namespace app\controller;

use common\Helper\BlogHelper;
use Webman\Http\Response;
use Webman\Http\Request;
use support\Db;

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
        $name = $request->input('name');
        $url = $request->input('url');
        $sign = $request->input('sign');
        $feed = $request->input('feed', '');
        $tags = $request->input('tag', '');

        // safe fittler
        if ($name === null || $url === null || $sign === null) {
            return api(false, '必填项不能为空');
        }
        // check is url
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return api(false, '请输入正确的b博客链接');
        }

        if (mb_strlen($name) >= 15) {
            return api(false, '博客名称不能超过15个字符');
        }
        if (mb_strlen($url) >= 255 || mb_strlen($feed) >= 255) {
            return api(false, '链接不能超过255个字符');
        }
        // insert sql

        $url = rtrim($url, '/'); // 统一格式, 去除最右侧的 /
        if (BlogHelper::checkBlogExists($url)) {
            return api(false, '该博客已经存在, 请勿重复提交');
        }

        try {
            Db::beginTransaction();

            $blog_idx = Db::table('blog')->insertGetId([
                'id' => BlogHelper::createBlogId($url),
                'name' => $name,
                'url' => $url,
                'sign' => $sign,
                'feed' => $feed,
                'status' => '',
                'enabled' => 0,
            ]);

            foreach (explode(',', $tags) as $tag) {
                if (is_numeric($tag) && BlogHelper::getTagNameByTagId($tag) !== '') {
                    Db::table('tag')->insert([
                        'tag_id' => $tag,
                        'blog_id' => $blog_idx,
                    ]);
                }
            }

            Db::commit();
        } catch (\Throwable $e) {
            Db::rollBack();
            return api(false, '提交失败');
        }
        return api();
    }
}
