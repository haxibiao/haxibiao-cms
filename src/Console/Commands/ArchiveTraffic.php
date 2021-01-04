<?php

namespace Haxibiao\Cms\Console\Commands;

use App\Dimension;
use App\Traffic;
use Illuminate\Console\Command;

class ArchiveTraffic extends Command
{

    protected $signature   = 'archive:traffic';
    protected $description = '每天归档seo流量 日报数据 抓取，提交，索引，搜索来路 ....';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        Traffic::where('created_at', '<=', today()->subday(3)->toDateString())->chunk(1000, function ($traffics) {
            foreach ($traffics as $traffic) {
                if ($traffic->bot) {
                    $this->track(
                        $traffic->bot . '爬取数',
                        1,
                        $this->getDomainName($traffic->domain) ?? $traffic->domain,
                        $traffic->created_at->toDateString()
                    );
                }
                if ($traffic->engine) {
                    $this->track(
                        $traffic->engine . '搜索量',
                        1,
                        $this->getDomainName($traffic->domain) ?? $traffic->domain,
                        $traffic->created_at->toDateString()
                    );
                }
            }
        });
        //清理3天前的流量记录
        Traffic::where('created_at', '<=', today()->subday(3)->toDateString())->delete();
    }

    public function track($name, $value, $group, $date)
    {
        $dimension = Dimension::whereGroup($group)
            ->whereName($name)
            ->where('date', $date)
            ->first();
        if (!$dimension) {
            $dimension = Dimension::create([
                'date'  => $date,
                'group' => $group,
                'name'  => $name,
                'value' => $value,
            ]);
        } else {
            //更新数值和统计次数
            $dimension->value = $dimension->value + $value;
            $dimension->save();
        }
    }

    public function getDomainName($domain)
    {
        $names = neihan_sites_domains();
        return array_get($names, $domain);
    }
}
