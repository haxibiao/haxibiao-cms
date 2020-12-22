<?php

namespace Haxibiao\Cms\Http\Controllers;

use App\Http\Controllers\Controller;

class SeoController extends Controller
{
    public function baiduInclude()
    {
        $items = cache()->remember('neihan_sites_baidu_include_check', 60 * 24, function () {
            $urls = "";
            foreach (neihan_sites_domains() as $domain => $name) {
                $urls .= "\nwww." . $domain;
                $urls .= "\n" . $domain;
            }
            $urls = ltrim($urls);
            return baidu_include_check($urls);
        });
        return view('seo.baidu_include')->with('items', $items);
    }
}
