<?php

namespace Laggards\Aliyun;

use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;

class AliyunServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/aliyun.php' => $this->app->configPath().'/aliyun.php',
        ], 'config');

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/aliyun.php',
            'aliyun'
        );
        //$this->app->alias('aliyun', 'Aws\Sdk');
    }
}
