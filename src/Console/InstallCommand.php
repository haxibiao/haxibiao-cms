<?php

namespace Haxibiao\Cms\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class InstallCommand extends Command
{

    /**
     * The name and signature of the Console command.
     *
     * @var string
     */
    protected $signature = 'cms:install {--force : 强制全新安装}';

    /**
     * The Console command description.
     *
     * @var string
     */
    protected $description = '安装 haxibiao/cms';

    /**
     * Execute the Console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->info('vendor:publish ...');
        $this->vendorPublish();

        $this->info('复制 stubs ...');
        copyStubs(__DIR__, $this->option('force'));

        $this->info('迁移数据库变化...');
        $this->call('migrate');
    }

    public function vendorPublish()
    {
        $this->call('vendor:publish', [
            '--tag'   => 'cms-config',
            '--force' => $this->option('force'),
        ]);

        $this->call('vendor:publish', [
            '--tag'   => 'cms-resources',
            '--force' => $this->option('force'),
        ]);
    }

}
