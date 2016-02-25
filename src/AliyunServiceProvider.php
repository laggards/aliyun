<?php
namespace Laggards\Aliyun;

use Illuminate\Contracts\Container\Container as Application;
use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Application as LumenApplication;

class AliyunServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
		$this->setupConfig($this->app);
    }

	/**
     * Setup the config.
     *
     * @param \Illuminate\Contracts\Container\Container $app
     *
     * @return void
     */
    protected function setupConfig(Application $app)
    {
		$source = realpath(__DIR__ . '/../config/aliyun.php');
		if ($app instanceof LaravelApplication && $app->runningInConsole()) {
            $this->publishes([$source => config_path('aliyun.php')]);
        } elseif ($app instanceof LumenApplication) {
            $app->configure('aliyun');
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
