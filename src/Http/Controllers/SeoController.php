<?php

namespace Haxibiao\Cms\Http\Controllers;

use App\Dimension;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Haxibiao\Cms\Site;
use Illuminate\Http\Request;

class SeoController extends Controller
{
    public function baiduInclude()
    {
        //今天的索引
        $today = cache()->remember('baidu_include_' . today()->toDateString(), 60 * 72, function () {
            $res = [];
            $k = 0;
            $dimensions = Dimension::where('name', '百度索引量')->where('date', today()->toDateString())->get();
            foreach ($dimensions as $dimension) {
                $res[$k]['url'] = $dimension->group;
                $res[$k++]['收录'] = $dimension->value;
            }
            return $res;
        });

        //昨天的索引
        $yesterday = cache()->remember('baidu_include_' . today()->subDay()->toDateString(), 60 * 72, function () {
            $res = [];
            $k = 0;
            $dimensions = Dimension::where('name', '百度索引量')->where('date', today()->subDay()->toDateString())->get();
            foreach ($dimensions as $dimension) {
                $res[$k]['url'] = $dimension->group;
                $res[$k++]['收录'] = $dimension->value;
            }
            return $res;
        });

        //前天的索引
        $third = cache()->remember('baidu_include_' . today()->subDay(2)->toDateString(), 60 * 72, function () {
            $res = [];
            $k = 0;
            $dimensions = Dimension::where('name', '百度索引量')->where('date', today()->subDay(2)->toDateString())->get();
            foreach ($dimensions as $dimension) {
                $res[$k]['url'] = $dimension->group;
                $res[$k++]['收录'] = $dimension->value;
            }
            return $res;
        });

        for($k=0;$k<count($today);$k++){
            $todayValue = Dimension::where('group',$today[$k]['url'])
            ->where('name', '百度索引量')
            ->where('date', today()->toDateString())
            ->first()->value;
            $yesterdayValue = Dimension::where('group',$today[$k]['url'])
            ->where('name', '百度索引量')
            ->where('date', today()->subDay(1)->toDateString())
            ->first()->value;
            if($todayValue > $yesterdayValue){
                $today[$k]['up'] = 1;
            }else if($todayValue < $yesterdayValue){
                $today[$k]['up'] = -1;
            }else{
                $today[$k]['up'] = 0;
            }
        }

        for($k=0;$k<count($yesterday);$k++){
            $yesterdayValue = Dimension::where('group',$today[$k]['url'])
            ->where('name', '百度索引量')
            ->where('date', today()->subDay(1)->toDateString())
            ->first()->value;
            $thirdValue = Dimension::where('group',$today[$k]['url'])
            ->where('name', '百度索引量')
            ->where('date', today()->subDay(2)->toDateString())
            ->first()->value;
            if($yesterdayValue > $thirdValue){
                $yesterday[$k]['up'] = 1;
            }else if($yesterdayValue < $thirdValue){
                $yesterday[$k]['up'] = -1;
            }else{
                $yesterday[$k]['up'] = 0;
            }
        }

        return view('seo.baidu_include')
            ->with('today', $today)
            ->with('yesterday', $yesterday)
            ->with('third', $third);
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
        $site = cms_get_site();

        $api = $request->get('api') ?? null;
        if (empty($api)) {
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

                //更新站点配额

                $data                  = $site->data ?? [];
                $data['baidu_remain']  = $result->remain;
                $data['baidu_success'] = $result->success;
                $site->data            = $data;
                $site->save();
            } else {
                $info = "查询失败! 推送接口调用错误! 请联系站长检查token?";
            }
        } else {
            $info = "推送接口调用地址为NULL!请联系站长!";
        }
        return $info;
    }
}
