<?php

/**
 * 首页置顶电影(站群)
 */
function cmsTopMovies($top = 4)
{
    //站群模式
    if (config('cms.multi_domains')) {
        if ($site = cms_get_site()) {
            if ($site->movies()->count()) {
                return $site->movies()->take($top)->get();
            }
        }
    }
    return indexTopMovies($top);
}

/**
 * 首页置顶视频(站群)
 */
function cmsTopVideos($top = 4)
{
    //站群模式
    if (config('cms.multi_domains')) {
        if ($site = cms_get_site()) {
            if ($site->posts()->count()) {
                return $site->posts()->has('video')->take($top)->get();
            }
        }
    }
    return indexTopVideos($top);
}

/**
 * 首页的专题(站群)
 * @return [category] [前几个专题的数组]
 */
function cmsTopCategories($top = 7)
{
    //站群模式
    if (config('cms.multi_domains')) {
        $site = cms_get_site();
        if ($site->categories()->count()) {
            return $site->categories()->latest('siteables.updated_at')->take($top)->get();
        }
    }
    return indexTopCategories($top);
}

/**
 * 首页的文章列表(站群)
 * @return collection([article]) 包含分页信息和移动ＶＵＥ等优化的文章列表
 */
function cmsTopArticles()
{
    //站群模式
    if (config('cms.multi_domains')) {
        $site = cms_get_site();
        if ($site->articles()->count()) {
            $qb = $site->articles()
                ->exclude(['body', 'json'])
                ->latest('siteables.updated_at');
            return smartPager($qb);
        }
    } else {
        return indexArticles();
    }
}
