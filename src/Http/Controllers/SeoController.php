<?php

namespace Haxibiao\Cms\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Haxibiao\Cms\Site;
use Illuminate\Http\Request;

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

    public function robot()
    {
        $domain       = get_domain();
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

    /**
     * 查询百度推送api反馈结果
     * 参数：api（为推送接口调用地址）(选传)
     * 格式：http://ainicheng.com/api/site/pushResult?api=http://data.zz.baidu.com/urls?site=https://dongdaima.com&token=mTsKBsNnvGSmGuFd
     */
    public function pushResult(Request $request)
    {
        $info = "今日剩余可推送URL条数:0";

        $api = $request->get('api') ?? null;
        if (empty($api)) {
            $name = seo_site_name();
            $site = Site::where('name', $name)->first();
            if ($site) {
                $api = 'http://data.zz.baidu.com/urls?site=' . $site->domain . '&token=' . $site->ziyuan_token;
            }
        } else {
            $api = urldecode(str_after($request->url . $request->getUri(), 'api='));
        }

        if ($api) {
            $result = pushSeoUrl(['www.baidu.com'], $api);
            if (str_contains($result, "success")) {

                $result = json_decode($result);
                $info   = "今日剩余可推送URL条数: " . $result->remain;
                $info .= "\n 今日成功推送URL条数: " . $result->success;
            } else {
                $info = "查询失败! 推送接口调用错误! 请联系站长检查token?";
            }
        } else {
            $info = "推送接口调用地址为NULL!请联系站长!";
        }
        return $info;
    }
}
