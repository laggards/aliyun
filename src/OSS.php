<?php namespace Laggards\Aliyun;

require_once 'lib/AliyunOSS/sdk.class.php';
use ALIOSS;
use Carbon\Carbon;

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
		return parent::__construct(config('aliyun.oss.AccessKeyId'), config('aliyun.oss.AccessKeySecret'), config('aliyun.oss.Endpoint'));
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
			$FileType = $file->getClientOriginalExtension();
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
	
	public function signature($bucket = null, $dir = null){
		if($bucket == null){
			$host = 'http://'.$this->bucket.'.'.config('aliyun.oss.Endpoint');
		}else{
			$host = 'http://'.$bucket.'.'.config('aliyun.oss.Endpoint');
		}
		$now = Carbon::now();
		$end = $now->addMinutes(30);
		$expiration = $end->toIso8601String();
		$pos = strpos($expiration, '+');
		$expiration = substr($expiration, 0, $pos);
		$expiration .='Z';
		//$oss_sdk_service = $this->client;
		//var_dump($oss_sdk_service);
		if($dir == null){
			$dir = 'temp/'.date("Y/m/d/");
		}

		$condition = array(0=>'content-length-range', 1=>0, 2=>1048576000);
		$conditions[] = $condition; 
		
		$start = array(0=>'starts-with', 1=>'$key', 2=>$dir);
		$conditions[] = $start; 

		$arr = array('expiration'=>$expiration,'conditions'=>$conditions);
		//echo json_encode($arr);
		//return;
		$policy = json_encode($arr);
		$base64_policy = base64_encode($policy);
		$string_to_sign = $base64_policy;
		
		$signature = base64_encode(hash_hmac('sha1', $string_to_sign, config('aliyun.oss.AccessKeySecret'), true));

		$response = array();
		$response['accessid'] = config('aliyun.oss.AccessKeyId');
		$response['host'] = $host;
		$response['policy'] = $base64_policy;
		$response['signature'] = $signature;
		$response['expire'] = $end;
		//这个参数是设置用户上传指定的前缀
		$response['dir'] = $dir;
		return $response;
	}

}
