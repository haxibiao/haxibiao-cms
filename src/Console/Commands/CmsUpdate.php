<?php

namespace Haxibiao\Cms\Console\Commands;

use Haxibiao\Cms\Site;
use Illuminate\Console\Command;

class CmsUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cms:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '每天为cms站群下的站点自动更新首页资源 专题 电影 视频 文章 动态 ....';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        foreach (Site::all() as $site) {
            //FIXME: 需要小蔡实现 https://pm.haxifang.com/browse/HXB-29
            //自动给当前站视图关联的内容，更新时间最早的更新为当日凌晨更新，让首页内容每天更新
            //如果已有24小时内手动更新的，跳过
            //自动优先更新当前站点专注的专题下的内容
            //站点管理下，允许站长设置当前站点专注的专题(电影，视频，文章，动态)
        }
    }
}
