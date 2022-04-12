<?php

namespace app\admin\controller;

use Webman\Http\Response;
use Webman\Http\Request;
use support\Redis;
use common\Helper\BlogHelper;
use support\Db;

class Blog
{
    /**
     * 更新博客
     *
     * @param Request $request
     * @return Response
     */
    public function update(Request $request): Response
    {
        $blog_idx = $request->input('blog_idx');
        $name = $request->input('name');
        $url = $request->input('url');
        $sign = $request->input('sign');
        $feed = $request->input('feed', '');
        $enabled = (int)$request->input('enabled', 0);
        $tags = $request->input('tag', '');

        // check blog exists
        if (!BlogHelper::checkBlogExistsByBlogIdx($blog_idx)) {
            return api(false, '博客不存在');
        }
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
        if ($enabled !== 0 && $enabled !== 1) {
            return api(false, 'enabled 必须为 0或1');
        }

        $url = rtrim($url, '/'); // 统一格式, 去除最右侧的 /

        try {
            Db::beginTransaction();

            Db::table('blog')->where('idx', $blog_idx)->update([
                'name' => $name,
                'url' => $url,
                'sign' => $sign,
                'feed' => $feed,
                'enabled' => $enabled,
            ]);

            Db::table('tag')->where('blog_id', $blog_idx)->delete();
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
            return api(false, '更新失败');
        }

        return api();
    }

    /**
     * 删除博客
     *
     * @param Request $request
     * @return Response
     */
    public function del(Request $request): Response
    {
        $blog_idx = $request->input('blog_idx');
        if ($blog_idx === null) {
            return api(false, 'blog_id不能为空');
        }
        if (!is_numeric($blog_idx)) {
            return api(false, '非法的blog_id');
        }

        // check blog_id exists
        if (!BlogHelper::checkBlogExistsByBlogIdx($blog_idx)) {
            return api(false, '博客不存在');
        }

        // 删除博客
        try {
            Db::beginTransaction();
            Db::table('blog')->where('idx', $blog_idx)->delete();
            Db::table('tag')->where('blog_id', $blog_idx)->delete();
            Db::commit();
        } catch (\Throwable $e) {
            Db::rollBack();
            return api(false, '删除失败');
        }

        return api();
    }
}
