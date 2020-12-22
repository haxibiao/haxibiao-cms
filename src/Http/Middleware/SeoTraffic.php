<?php

namespace Haxibiao\Cms\Http\Middleware;

use Closure;
use Haxibiao\Cms\Model\Traffic;
use Jenssegers\Agent\Facades\Agent;

class SeoTraffic
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $traffic = [];
        //蜘蛛抓取
        if (Agent::isRobot()) {
            $bot = strtolower(Agent::robot());
            if (in_array($bot, ['baidu', 'google'])) {
                $traffic['bot'] = $bot;
            }
        }

        //搜索来路
        $referer = $request->get('referer') ?? $request->header('referer');
        if ($referer) {
            if (str_contains($referer, 'baidu.com')) {
                $engine = 'baidu';
            }
            if (str_contains($referer, 'google.com')) {
                $engine = 'google';
            }
            if (isset($engine)) {
                $traffic['engine']  = $engine;
                $traffic['referer'] = $referer;
            }

        }

        //如果seo有效流量
        if (!empty($traffic)) {
            $traffic['url']    = $request->url();
            $traffic['domain'] = get_domain();

            //记录流量
            Traffic::create($traffic);
        }

        return $next($request);
    }
}
