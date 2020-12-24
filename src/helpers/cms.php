<?php

use Haxibiao\Cms\Site;

/*****************************
 * *****cms站群模式TKD和站长验证*********
 * ***************************
 */
function cms_seo_title()
{
    //站群模式
    if (config('cms.multi_domains')) {
        if ($site = cms_get_site()) {
            return $site->title;
        }
    }
    return get_seo_title();
}

function cms_seo_keywords()
{
    //站群模式
    if (config('cms.multi_domains')) {
        if ($site = cms_get_site()) {
            return $site->keywords;
        }
    }
    return get_seo_keywords();
}

function cms_seo_description()
{
    //站群模式
    if (config('cms.multi_domains')) {
        if ($site = cms_get_site()) {
            return $site->description;
        }
    }
    return get_seo_description();
}

function cms_seo_meta()
{
    //站群模式
    if (config('cms.multi_domains')) {
        if ($site = cms_get_site()) {
            return $site->verify_meta;
        }
    }
    return get_seo_meta();
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
    if ($site = Site::whereDomain(get_domain())->first()) {
        return $site;
    }
    abort(404, "没有配置当前的cms站点");
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
