<?php

namespace Haxibiao\Cms;

use Haxibiao\Cms\Console\Commands\ArchiveTraffic;
use Haxibiao\Cms\Console\Commands\SitemapGenerate;
use Haxibiao\Cms\Console\InstallCommand;
use Haxibiao\Cms\Http\Middleware\SeoTraffic;
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

        //安装时需要
        if ($this->app->runningInConsole()) {
            //数据库
            $this->loadMigrationsFrom($this->app->make('path.haxibiao-cms.migrations'));

            //配置文件
            $this->publishes([
                __DIR__ . '/../config/cms.php' => config_path('cms.php'),
            ], 'cms-config');

        }

        $this->registerMorphMap();
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
