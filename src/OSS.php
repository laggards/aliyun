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
    
	public function list_object($bucket= null,$options = null) {
		if($bucket == null){
			$bucket = $this->bucket;
		}
		$options = array();
		$response = parent::list_object($bucket, $options);
		return $response;
    }
    
    public function getBucket()
    {
        return $this->bucket;
    }
	
	public function put( $file, $object = null, $bucket = null ){
		if($object == null){
			$FileType = $temp_file->getClientOriginalExtension();
			$newFileName = str_random(32).'.'.$FileType;
			$object = 'temp/'.date("Y/m/d/").$newFileName;
		}
		if($bucket == null){
			$bucket = $this->bucket;
		}
		if($this->is_exist($object)){
			return false;
		}else{
			$options = array();		
			$response = parent::upload_file_by_file($bucket, $object, $file, $options);
			if($response->status === 200){
				return $object; 
			}else{
				return false;
			}	
		}
	}

	public function put_content($content, $object,  $bucket = null ){
		if($bucket == null){
			$bucket = $this->bucket;
		}
		$options = array(
			'content' => $content,
			'length' => strlen($content),
		);	
		$response = parent::upload_file_by_content($bucket, $object, $options);
		if($response->status === 200){
			return $object; 
		}else{
			return false;
		}	
	}
	
	public function move( $from_object, $to_object, $from_bucket= null, $to_bucket = null, $options = NULL){
		if($from_bucket == null){
			$from_bucket = $this->bucket;
		}
		if($to_bucket == null){
			$to_bucket = $this->bucket;
		}
		$response = parent::copy_object($from_bucket,$from_object, $to_bucket, $to_object, $options);
		if($response->status === 200){
			$this->delete($from_object,$from_bucket);
			return true; 
		}else{
			return false;
		}	
		
	}
	function putObjectByRaw( $file, $object, $bucket = null)
	{
		if($bucket == null){
			$bucket = $this->bucket;
		}
		$options = array(
				ALIOSS::OSS_FILE_UPLOAD => $file,
				'partSize' => 5242880,
			);
		$res = parent::create_mpu_object($bucket, $object,$options);
		$msg = "通过multipart上传文件";
		var_dump($res, $msg);
	}
	
	public function get($object,$bucket = null){
		if($bucket == null){
			$bucket = $this->bucket;
		}
		$response = parent::get_object($bucket, $object, $options = NULL);
		if($response->status === 200){
			return '//'.$this->bucket.'.'.config('aliyun.oss.Endpoint').'/'.$object; 
		}else{
			return false;
		}
	}
	
	public function is_exist($object,$bucket = null){
		if($bucket == null){
			$bucket = $this->bucket;
		}
		$response = parent::is_object_exist($bucket, $object, $options = NULL);
		if($response->status === 200){
			return true;
		}else{
			return false;
		}
	}
	
	public function delete($object,$bucket = null){
		if($bucket == null){
			$bucket = $this->bucket;
		}
		$response = parent::delete_object($bucket, $object, $options = NULL);
		if($response->status === 204){
			return true;
		}else{
			return false;
		}
	}
}
