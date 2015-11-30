<?php namespace Laggards\Aliyun;

require_once 'lib/OSS/sdk.class.php';
use ALIOSS;

class OSS extends ALIOSS
{
	public $client;
	public $bucket;
	
    public function __construct()
    {
		$this->bucket = config('aliyun.oss.Bucket');
		parent::__construct(config('aliyun.oss.AccessKeyId'), config('aliyun.oss.AccessKeySecret'), config('aliyun.oss.Endpoint'));
    }
	
	public function list_bucket($options = NULL) {
		$options = array();
		$response = parent::list_object($this->bucket, $options);
		return $response;
    }
	
	public function put($bucket,$object, $file_path){
		$options = array(
			parent::OSS_HEADERS => array(
				'Expires' => '2012-10-01 08:00:00',
				'Cache-Control' => '2012-10-01 08:00:00',
				'Content-Disposition' => 'just-for-test',
				'Content-Encoding' => 'utf-8',
				'Content-Type' => 'text/plain2',
			),
		);
		$res = parent::upload_file_by_file($bucket, $object, $file_path, $options);
	}
	
	public function get(){
		
	}
	
	public function delete($object){
		$res = parent::delete_object($this->bucket, $object);	
		return $res;
	}
}