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
}
