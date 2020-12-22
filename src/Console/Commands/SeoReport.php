<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SeoReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seo:report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '每天更新seo日报数据 抓取，提交，索引，搜索来路 ....';

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

    }
}
