<?php

namespace Laggards\Aliyun;

use Illuminate\Support\ServiceProvider;
use AlibabaCloud\Client\AlibabaCloud;

class AliyunServiceProvider extends ServiceProvider
{
    protected $defer = true;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/aliyun.php' => config_path('aliyun.php'),
        ]);

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
        
        $this->app->singleton('aliyun', function($app){
            $config = $app->make('config');

            $accessKey = $config->get('aliyun.key');
            $accessSecret = $config->get('aliyun.secret');
            $regionId = $config->get('aliyun.regionId');

            return new AliyunService;
            // return AlibabaCloud::accessKeyClient($accessKey, $accessSecret)
            //                     ->regionId($regionId)
            //                     ->asDefaultClient();
        });
    }

    public function provides() {
        return ['aliyun'];
    }
}
