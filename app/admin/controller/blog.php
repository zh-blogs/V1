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
        // check blog exists
        if (!BlogHelper::checkBlogExistsByBlogIdx($blog_idx)) {
            return api(false, '博客不存在');
        }
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
