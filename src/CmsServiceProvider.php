<?php

namespace Haxibiao\Config;

use Haxibiao\Config\Console\InstallCommand;
use Illuminate\Config\Repository as Config;
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
        $src_path = __DIR__;
        foreach (glob($src_path . '/helpers/*.php') as $filename) {
            require_once $filename;
        }

        // Register Commands
        $this->commands([
            InstallCommand::class,
        ]);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if (!$this->app->configurationIsCached()) {
            $this->mergeConfigFrom(__DIR__ . '/../config/cms.php', 'cms');
        }
    }
}
