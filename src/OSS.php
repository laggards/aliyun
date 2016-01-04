<?php namespace Laggards\Aliyun;

require_once 'lib/AliyunOSS/sdk.class.php';
use ALIOSS;

class OSS extends ALIOSS
{
	protected static $metaOptions = [
        'CacheControl',
        'Expires',
        'UserMetadata',
        'ContentType',
        'ContentLanguage',
        'ContentEncoding'
    ];
    
	public $bucket;
	
	public $client;
	
    public function __construct()
    {
		$this->bucket = config('aliyun.oss.Bucket');
		parent::__construct(config('aliyun.oss.AccessKeyId'), config('aliyun.oss.AccessKeySecret'), config('aliyun.oss.Endpoint'));
    }
    
	public function list_object($bucket= NULL,$options = NULL) {
		$options = array();
		$response = parent::list_object($this->bucket, $options);
		return $response;
    }
    
    public function getBucket()
    {
        return $this->bucket;
    }
	
	public function put($object, $file, $type = null){
		if($type){
			$object = $type.'/'.date("Y/m/d/").$object;
		}else{
			$object = 'uploads/'.date("Y/m/d/").$object;
		}
		if($this->is_exist($object)){
			return false;
		}else{
			$options = array();		
			$response = parent::upload_file_by_file($this->bucket, $object, $file, $options);
			if($response->status === 200){
				return $object; 
			}else{
				return false;
			}	
		}

	}
	
	public function get($object){
		$response = parent::get_object($this->bucket, $object, $options = NULL);
		if($response->status === 200){
			return '//'.$this->bucket.'.'.config('aliyun.oss.Endpoint').'/'.$object; 
		}else{
			return false;
		}
	}
	
	public function is_exist($object){
		$response = parent::is_object_exist($this->bucket, $object, $options = NULL);
		if($response->status === 200){
			return true;
		}else{
			return false;
		}
	}
	
	public function delete($object){
		$response = parent::delete_object($this->bucket, $object, $options = NULL);
		var_dump($response);
		if($response->status === 204){
			return true;
		}else{
			return false;
		}
	}
}
