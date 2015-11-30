<?php
namespace Laggards\Aliyun;

use Illuminate\Support\ServiceProvider;

class AliyunServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $source = realpath(__DIR__ . '/../config/aliyun.php');
        if (class_exists('Illuminate\Foundation\Application', false)) {
            $this->publishes([$source => config_path('aliyun.php')]);
        }
        $this->mergeConfigFrom($source, 'aliyun');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('aliyun', function ($app) {
            $config = $app['config']->get('aliyun');
            //return new Sdk($config);
        });
        //$this->app->alias('aliyun', 'Aws\Sdk');
    }
}
