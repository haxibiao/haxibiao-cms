<?php

namespace Haxibiao\Cms;

use Haxibiao\Cms\Console\Commands\ArchiveTraffic;
use Haxibiao\Cms\Console\Commands\CmsUpdate;
use Haxibiao\Cms\Console\Commands\SeoWorker;
use Haxibiao\Cms\Console\Commands\SitemapGenerate;
use Haxibiao\Cms\Console\InstallCommand;
use Haxibiao\Cms\Http\Middleware\SeoTraffic;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Database\Eloquent\Relations\Relation;
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
        //帮助函数
        $src_path = __DIR__;
        foreach (glob($src_path . '/helpers/*.php') as $filename) {
            require_once $filename;
        }

        $this->bindPathsInContainer();

        // Register Commands
        $this->commands([
            InstallCommand::class,
            SitemapGenerate::class,
            ArchiveTraffic::class,
            SeoWorker::class,
            CmsUpdate::class,
        ]);

		$this->mergeConfigFrom(__DIR__ . '/../config/cms.php', 'cms');
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

        //安装时需要
        if ($this->app->runningInConsole()) {
            //数据库
            $this->loadMigrationsFrom($this->app->make('path.haxibiao-cms.migrations'));

            //发布 -配置文件
            $this->publishes([
                __DIR__ . '/../config/cms.php' => config_path('cms.php'),
            ], 'cms-config');

            //发布 -resoucres
            $this->publishes([
                __DIR__ . '/../resources/views' => base_path('resources/views'),
            ], 'cms-resources');

        }

        //cms站点
        $this->app->singleton('cms_site', function ($app) {

            $modelStr = '\Haxibiao\Cms\Site';
            if (class_exists('\App\Site')) {
                // \App\Site 是 \Haxibiao\Cms\Site 的子类
                $modelStr = '\App\Site';
            }
            if ($site = $modelStr::whereDomain(get_domain())->first()) {
                return $site;
            }
            //默认返回最后一个站点
            return $modelStr::latest('id')->first();
        });

        $this->registerMorphMap();

        if (config('cms.multi_domains')) {
            $this->app->booted(function () {
                $schedule = $this->app->make(Schedule::class);
                // 每天定时归档seo流量
                $schedule->command('archive:traffic')->dailyAt('1:00');

                // 自动更新站群首页资源
                $schedule->command('cms:update')->dailyAt('2:00');

                // 生成新的SiteMap
                $schedule->command('sitemap:generate')->dailyAt('3:00');

            });
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

    protected function registerMorphMap()
    {
        $this->morphMap(cms_morph_map());
    }

    protected function morphMap(array $map = null, bool $merge = true): array
    {
        return Relation::morphMap($map, $merge);
    }
}
