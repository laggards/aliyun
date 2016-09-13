<?php namespace Laggards\Aliyun;

use Laggards\Aliyun\MNS;
use Illuminate\Support\Facades\Facade;
use Illuminate\Config\Repository;
use OSS\OssClient;
use OSS\Core\OssException;

/**
 * Facade for the Aliyun service
 *
 */
class AliyunFacade extends Facade
{
	
	public function __construct()
    {
        $this->app = app();
        $this->configRepository = config('aliyun');;
    }
	
	public static function createClient($obj){
		switch($obj){
			case 'oss':
				$client = new OssClient(config('aliyun.oss.AccessKeyId'), config('aliyun.oss.AccessKeySecret'), config('aliyun.oss.Endpoint'));
				break;
			case 'mns':
				$client = new MNS();
				break;
			case 'memcache':
				$client = new MemcacheSASL();
				break;
			default:
				return null;
				break;	
		}
		return $client;
	}
}
