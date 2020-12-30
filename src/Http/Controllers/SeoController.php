<?php

namespace Haxibiao\Cms\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;

class SeoController extends Controller
{
    public function baiduInclude()
    {
        $beian = cache()->remember('neihan_beian_baidu_include_check', 60 * 24, function () {
            $urls = "";
            foreach (neihan_beian_domains() as $domain => $name) {
                // $urls .= "\nwww." . $domain;
                $urls .= "\n" . $domain;
            }
            $urls = ltrim($urls);
            return baidu_include_check($urls);
        });
        $neihan = cache()->remember('movie_sites_baidu_include_check', 60 * 24, function () {
            $urls = "";
            foreach (neihan_sites_domains() as $domain => $name) {
                // $urls .= "\nwww." . $domain;
                $urls .= "\n" . $domain;
            }
            $urls = ltrim($urls);
            return baidu_include_check($urls);
        });
        return view('seo.baidu_include')
            ->with('beian', $beian)
            ->with('neihan', $neihan);
    }

    public function robot(){
        $domain = get_domain();
        $robotContent = <<<EOD
User-agent: *
Disallow: /*q=*
Disallow: /share/qrcode/
Sitemap: https://$domain/sitemap.xml
EOD;
        return response($robotContent)
            ->header('Content-Type', 'text/plain')
            ->header('Cache-Control', 'max-age=604800')
            ->header('Expires', Carbon::now()->addDays(7)->toRfc7231String());
    }
}
