<?php namespace Laggards\Aliyun;

use Laggards\Aliyun\OSS;
use Laggards\Aliyun\MNS;
use Illuminate\Support\Facades\Facade;
use Illuminate\Config\Repository;

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
				$oss = new OSS();
				return $oss;
				break;
			case 'mns':
				$mns = new MNS();
				return $mns;
				break;
			case 'memcache':
				$memcache = new MemcacheSASL();
				return $memcache;
				break;
			default:
				return null;
				break;	
		}	
	}
}
