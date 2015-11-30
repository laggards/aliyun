<?php namespace Laggards\Aliyun;

use Laggards\Aliyun\OSS;
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
				return null;
				break;
			default:
				return null;
				break;	
		}	
	}
	
    public static function get_oss_client() {
        $oss = new ALIOSS(self::accessKeyId, self::accesKeySecret, self::endpoint);
        return $oss;
    }

    public static function my_echo($msg) {
        $new_line = " \n";
        echo $msg . $new_line;
    }

    public static function get_bucket_name() {
        return config('aliyun.oss.ossBucket');
    }

    public static function create_bucket() {
        $oss = self::get_oss_client();
        $bucket = self::get_bucket_name();
        $acl = ALIOSS::OSS_ACL_TYPE_PUBLIC_READ;
        $res = $oss->create_bucket($bucket, $acl);
        $msg = "创建bucket " . $bucket;
        OSSUtil::print_res($res, $msg);
    }
}