<?php

namespace common\Helper;

use support\Db;

class BlogHelper
{
    /**
     * 通过 blogId 获取 tag 数组
     *
     * @param integer $blogId
     * @return array
     */
    public static function getTagByBlogId(int $blogId): array
    {
        $tag_ids = Db::table('tag')->select('tag_id')->where('blog_id', $blogId)->get();
        if (!$tag_ids) return [];

        $tags = [];
        foreach ($tag_ids as $k => $item) {
            $tags[$k] = self::getTagNameByTagId($item->tag_id);
        }

        return $tags;
    }

    /**
     * 通过 tagId 获取 tagName
     *
     * @param integer $tagId
     * @return string
     */
    public static function getTagNameByTagId(int $tagId): string
    {

        $s = Db::table('tag_map')->select('name')->where('id', $tagId)->first();
        if (!$s->name) return '';
        return $s->name;
    }

    /**
     * 通过链接判断博客是否存在
     *
     * @param string $blog_url
     * @return bool
     */
    public static function checkBlogExists(string $blog_url): bool
    {
        return Db::table('blog')->select('idx')->where('url', $blog_url)->exists();
    }

    /**
     * 通过blog_id判断博客是否存在
     *
     * @param int $blog_id
     * @return bool
     */
    public static function checkBlogExistsByBlogIdx(int $blog_idx): bool
    {
        return Db::table('blog')->select('idx')->where('idx', $blog_idx)->exists();
    }

    /**
     * 创建博客唯一ID
     * 
     * @param string $url
     * @return string
     */
    public static function createBlogId(string $url): string
    {
        $md5 = md5($url);
        $id = substr($md5, 0, 4) . "-" . substr($md5, 6, 4) . "-" . substr($md5, 12, 4) . "-" . substr(md5(microtime()), 0, 4);
        return $id;
    }
}
