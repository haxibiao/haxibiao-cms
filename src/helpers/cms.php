<?php

use Haxibiao\Cms\Siteable;
use Illuminate\Support\Str;

/**
 * 站群资源可以置顶的位置对照表
 */
function stickable_areas($type = null)
{
    $areas = [
        'movies'      => [
            '视频页-电影' => '视频页-电影',
            '首页-电影'  => '首页-电影',
        ],
        'posts'       => [
            '首页-视频' => '首页-视频',
        ],
        'articles'    => [
            '首页-文章列表'  => '首页-文章列表',
            '视频页-电影图解' => '视频页-电影图解',
        ],
        'categories'  => [
            '首页-专题'    => '首页-专题',
            '视频页-视频专题' => '视频页-视频专题',
            '视频页-图解专题' => '视频页-图解专题',
        ],
        'collections' => [
            '视频页-热门合集' => '视频页-热门合集',
        ],
    ];
    if ($type) {
        return $areas[$type] ?? null;
    }
    return $areas;
}

function cms_url($model, $site)
{
    $url = sprintf('https://%s/%s/%s',
        data_get($site, 'domain'),
        Str::singular($model->getTable()),
        $model->id
    );
    return $url;
}

function cms_morph_map()
{
    return [
        'categories' => 'Haxibiao\Cms\Category',
        'articles'   => 'Haxibiao\Cms\Article',
        'posts'      => 'Haxibiao\Cms\Post',
        'movies'     => 'Haxibiao\Cms\Movie',
    ];
}

/**
 * 更新百度提交时间
 */
function update_baidu_pushed_at($model, $site)
{
    $morphType = null;
    foreach (cms_morph_map() as $type => $model_class) {
        if (get_class($model) == $model_class) {
            $morphType = $type;
        }
    }
    if ($morphType) {
        if ($pivot = Siteable::firstWhere([
            'siteable_type' => $morphType,
            'siteable_id'   => $model->id,
            'site_id'       => $site->id,
        ])) {
            $pivot->update(['baidu_pushed_at' => now()]);
        }
    }
}

/*****************************
 * *****cms站群模式TKD和站长验证*********
 * ***************************
 */
function cms_seo_title()
{
    //站群模式
    if (config('cms.multi_domains')) {
        if ($site = cms_get_site()) {
            if ($site->title) {
                return $site->title;
            }
        }
    }
    return get_seo_title();
}

function cms_seo_keywords()
{
    //站群模式
    if (config('cms.multi_domains')) {
        if ($site = cms_get_site()) {
            if ($site->keywords) {
                return $site->keywords;
            }
        }
    }
    return get_seo_keywords();
}

function cms_seo_description()
{
    //站群模式
    if (config('cms.multi_domains')) {
        if ($site = cms_get_site()) {
            if ($site->description) {
                return $site->description;
            }
        }
    }
    return get_seo_description();
}

function cms_seo_meta()
{
    //站群模式
    if (config('cms.multi_domains')) {
        if ($site = cms_get_site()) {
            if ($site->verify_meta) {
                return $site->verify_meta;
            }
        }
    }
    return get_seo_meta();
}

/**
 * 返回站点ICP备案号
 */
function cms_icp_info()
{
    //站群模式
    if (config('cms.multi_domains')) {
        if ($site = cms_get_site()) {
            if ($site->icp) {
                return $site->icp;
            }
        }
    }
    return seo_value('站长', 'ICP');
}

/**
 * cms底部js
 */
function cms_seo_js()
{
    //站群模式
    if (config('cms.multi_domains')) {
        if ($site = cms_get_site()) {
            return $site->footer_js;
        }
    }
    //兼容旧版本seo配置里统计 matomo
    return get_seo_tj();
}

/**
 * 获得当前cms的站点
 */
function cms_get_site()
{
    return app('cms_site');
}

/**
 * 获得当前cms的主题
 */
function cms_seo_theme()
{
    //站群模式
    if (config('cms.multi_domains')) {
        if ($site = cms_get_site()) {
            return $site->theme;
        }
    }
    //兼容旧版本seo配置里统计 matomo
    return null;
}

/**
 * 首页置顶电影(站群)
 */
function cmsTopMovies($top = 4)
{
    //站群模式
    if (config('cms.multi_domains')) {
        if ($site = cms_get_site()) {
            if ($site->stickyMovies()->byStickableName('首页-电影')->count()) {
                return $site->stickyMovies()
                    ->byStickableName('首页-电影')
                    ->latest('stickables.updated_at')
                    ->take($top)
                    ->get();
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
            if ($site->stickyPosts()->byStickableName('首页-视频')->count()) {
                return $site->stickyPosts()
                    ->byStickableName('首页-视频')
                    ->latest('stickables.updated_at')
                    ->take($top)->get();
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
        if ($site = cms_get_site()) {
            if ($site->stickyCategories()->byStickableName('首页-专题')->count()) {
                return $site->stickyCategories()
                    ->byStickableName('首页-专题')
                    ->latest('stickables.updated_at')
                    ->take($top)->get();
            }
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
        if ($site->stickyArticles()->byStickableName('首页-文章列表')->count()) {
            $qb = $site->stickyArticles()
                ->byStickableName('首页-文章列表')
                ->exclude(['body', 'json'])
                ->latest('stickables.updated_at');
            return smartPager($qb);
        }
    }
    return indexArticles();
}

/**
 * 百度收录批量查询
 *
 * @param string $urls
 * @return array
 */
function baidu_include_check($urls)
{
    // $check_url = "https://www.baidu.com/s?wd=site:diudie.com&rn=3&tn=json&ie=UTF-8&cl=3&f=9";
    // $json = @file_get_contents($check_url);

    //批量检查
    if ($urls) {
        $sites = explode("\n", $urls);
        $res   = [];
        foreach ($sites as $k => $site) {
            $res[$k]['url']    = $site;
            $res[$k]['收录'] = 0;
            //单个检查
            $check_url = "http://www.baidu.com/s?wd=site:" . $site . "&rn=3&tn=json&ie=UTF-8&cl=3&f=9";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $check_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $json = curl_exec($ch);
            curl_close($ch);
            if ($json = @json_decode($json, true)) {
                if ($feed = $json['feed']) {
                    $res[$k]['收录'] = $feed['all'] ?? 0;
                }
            }
        }
        return $res;
    }
    dd("未找到有效urls来查询百度收录");
}
