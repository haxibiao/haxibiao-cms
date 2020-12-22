<?php

namespace Haxibiao\Cms;

use Haxibiao\Cms\Console\Commands\SitemapGenerate;
use Haxibiao\Cms\Console\InstallCommand;
use Haxibiao\Cms\Http\Middleware\SeoTraffic;
use Illuminate\Support\ServiceProvider;

class CmsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->bindPathsInContainer();

        //加载帮助函数
        $src_path = __DIR__;
        foreach (glob($src_path . '/Helper/*.php') as $filename) {
            require_once $filename;
        }

        // Register Commands
        $this->commands([
            InstallCommand::class,
            SitemapGenerate::class,
        ]);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //中间件
        app('router')->pushMiddlewareToGroup('web', SeoTraffic::class);

        //加载路由
        $this->loadRoutesFrom(
            $this->app->make('path.haxibiao-cms') . '/router.php'
        );

        //合并配置
        if (!app()->configurationIsCached()) {
            $this->mergeConfigFrom(__DIR__ . '/../config/cms.php', 'cms');
        }

        //安装时需要
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom($this->app->make('path.haxibiao-cms.migrations'));
        }
    }

    protected function bindPathsInContainer()
    {
        foreach ([
            'path.haxibiao-cms'            => $root = dirname(__DIR__),
            'path.haxibiao-cms.config'     => $root . '/config',
            'path.haxibiao-cms.database'   => $database = $root . '/database',
            'path.haxibiao-cms.migrations' => $database . '/migrations',
            'path.haxibiao-cms.seeders'    => $database . '/seeders',
            'path.haxibiao-cms.graphql'    => $root . '/graphql',
        ] as $abstract => $instance) {
            $this->app->instance($abstract, $instance);
        }
    }
}
