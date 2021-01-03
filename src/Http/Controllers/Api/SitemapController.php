<?php

namespace Haxibiao\Cms\Http\Controllers\Api;

use App\Site;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SitemapController extends Controller
{
    /**
     * 查询推送api反馈结果
     * 参数：api（为推送接口调用地址）(选传)
     * 格式：http://ainicheng.com/api/site/pushResult?api=http://data.zz.baidu.com/urls?site=https://dongdaima.com&token=mTsKBsNnvGSmGuFd
     */
    public function pushResult(Request $request)
    {
        $data = "今日剩余可推送URL条数:0";

        $api = $request->get('api') ?? null;
        if (empty($api)) {
            $name = seo_site_name();
            $site = Site::where('name', $name)->first();
            if ($site) {
                $api = 'http://data.zz.baidu.com/urls?site=' . $site->domain . '&token=' . $site->token;
            }
        } else {
            $api = urldecode(str_after($request->url . $request->getUri(), 'api='));
        }

        if ($api) {
            $result = pushSeoUrl(['www.baidu.com'], $api);
            if (str_contains($result, "success")) {
                $result = json_decode($result);
                $data   = "今日剩余可推送URL条数:" . $result->remain;
            } else {
                $data = "查询失败!推送接口调用地址错误!请联系站长!";
            }
        } else {
            $data = "推送接口调用地址为NULL!请联系站长!";
        }
        return $data;
    }
}
