<?php

namespace Haxibiao\Cms\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ArchiveTraffic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'archive:traffic';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '每天归档seo流量 日报数据 抓取，提交，索引，搜索来路 ....';

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
        //TODO: 归档并清理3天前的流量数据，保持流量记录表不过大
        DB::table('traffic')->where('created_at', '<', now()->subDays(3))->delete();
    }
}
