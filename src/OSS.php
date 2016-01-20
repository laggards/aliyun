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
	
	public function put_creative($object,$file){
		$options = array();		
		$response = parent::upload_file_by_file('dl-facsimile', $object, $file, $options);
		if($response->status === 200){
			return $object; 
		}else{
			return false;
		}	
	}
	
	public function put_content($object,$content, $display_id){
		$options = array(
			'content' => $content,
			'length' => strlen($content),
		);	
		$response = parent::upload_file_by_content('dl-facsimile', $object, $options);
		if($response->status === 200){
			return $object; 
		}else{
			return false;
		}	
	}
	
	function putObjectByRaw($bucket = null)
	{
		$object = "test/multipart-test.txt";
		/**
		 *  step 1. 初始化一个分块上传事件, 也就是初始化上传Multipart, 获取upload id
		 */
		try{
			$uploadId = $ossClient->initiateMultipartUpload($bucket, $object);
		} catch(OssException $e) {
			printf(__FUNCTION__ . ": initiateMultipartUpload FAILED\n");
			printf($e->getMessage() . "\n");
			return;
		}
		print(__FUNCTION__ . ": initiateMultipartUpload OK" . "\n");
		/*
		 * step 2. 上传分片
		 */
		$partSize = 10 * 1024 * 1024;
		$uploadFile = __FILE__;
		$uploadFileSize = filesize($uploadFile);
		$pieces = $ossClient->generateMultiuploadParts($uploadFileSize, $partSize);
		$responseUploadPart = array();
		$uploadPosition = 0;
		$isCheckMd5 = true;
		foreach ($pieces as $i => $piece) {
			$fromPos = $uploadPosition + (integer)$piece[$ossClient::OSS_SEEK_TO];
			$toPos = (integer)$piece[$ossClient::OSS_LENGTH] + $fromPos - 1;
			$upOptions = array(
				$ossClient::OSS_FILE_UPLOAD => $uploadFile,
				$ossClient::OSS_PART_NUM => ($i + 1),
				$ossClient::OSS_SEEK_TO => $fromPos,
				$ossClient::OSS_LENGTH => $toPos - $fromPos + 1,
				$ossClient::OSS_CHECK_MD5 => $isCheckMd5,
			);
			if ($isCheckMd5) {
				$contentMd5 = OssUtil::getMd5SumForFile($uploadFile, $fromPos, $toPos);
				$upOptions[$ossClient::OSS_CONTENT_MD5] = $contentMd5;
			}
			//2. 将每一分片上传到OSS
			try {
				$responseUploadPart[] = $ossClient->uploadPart($bucket, $object, $uploadId, $upOptions);
			} catch(OssException $e) {
				printf(__FUNCTION__ . ": initiateMultipartUpload, uploadPart - part#{$i} FAILED\n");
				printf($e->getMessage() . "\n");
				return;
			}
			printf(__FUNCTION__ . ": initiateMultipartUpload, uploadPart - part#{$i} OK\n");
		}
		$uploadParts = array();
		foreach ($responseUploadPart as $i => $eTag) {
			$uploadParts[] = array(
				'PartNumber' => ($i + 1),
				'ETag' => $eTag,
			);
		}
		/**
		 * step 3. 完成上传
		 */
		try {
			$ossClient->completeMultipartUpload($bucket, $object, $uploadId, $uploadParts);
		}  catch(OssException $e) {
			printf(__FUNCTION__ . ": completeMultipartUpload FAILED\n");
			printf($e->getMessage() . "\n");
			return;
		}
		printf(__FUNCTION__ . ": completeMultipartUpload OK\n");
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
