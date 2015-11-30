<?php namespace Laggards\Aliyun;

require_once 'lib/mns-autoloader.php';

use AliyunMNS\Client;
use AliyunMNS\Requests\SendMessageRequest;
use AliyunMNS\Requests\CreateQueueRequest;
use AliyunMNS\Exception\MnsException;

class MSN
{
	private $accessId;
    private $accessKey;
    private $endPoint;
    private $client;
	
    public function __construct()
    {
		$this->bucket = config('aliyun.oss.Bucket');
		parent::__construct(config('aliyun.oss.AccessKeyId'), config('aliyun.oss.AccessKeySecret'), config('aliyun.oss.Endpoint'));
    }
	
	public function SendMessage() {
		$messageBody = "test";
        // as the messageBody will be automatically encoded
        // the MD5 is calculated for the encoded body
        $bodyMD5 = md5(base64_encode($messageBody));
        $request = new SendMessageRequest($messageBody);
        try
        {
            $res = $queue->sendMessage($request);
            echo "MessageSent! \n";
        }
        catch (MnsException $e)
        {
            echo "SendMessage Failed: " . $e;
            return;
        }
	}
	
	public function BatchSendMessage() {
		try
        {
            $res = $queue->deleteMessage($receiptHandle);
            echo "DeleteMessage Succeed! \n";
        }
        catch (MnsException $e)
        {
            echo "DeleteMessage Failed: " . $e;
            return;
        }	
	}
	
	public function ReceiveMessage() {
		$receiptHandle = NULL;
        try
        {
            $res = $queue->receiveMessage();
            echo "ReceiveMessage Succeed! \n";
            if (strtoupper($bodyMD5) == $res->getMessageBodyMD5())
            {
                echo "You got the message sent by yourself! \n";
            }
            $receiptHandle = $res->getReceiptHandle();
        }
        catch (MnsException $e)
        {
            echo "ReceiveMessage Failed: " . $e;
            return;
        }	
	}
	
	public function BatchReceiveMessage() {
		try
        {
            $res = $queue->deleteMessage($receiptHandle);
            echo "DeleteMessage Succeed! \n";
        }
        catch (MnsException $e)
        {
            echo "DeleteMessage Failed: " . $e;
            return;
        }	
	}
	
	public function DeleteMessage($receiptHandle) {
		try
        {
            $res = $queue->deleteMessage($receiptHandle);
            echo "DeleteMessage Succeed! \n";
        }
        catch (MnsException $e)
        {
            echo "DeleteMessage Failed: " . $e;
            return;
        }
	}
	
	public function BatchDeleteMessage() {
		try
        {
            $res = $queue->deleteMessage($receiptHandle);
            echo "DeleteMessage Succeed! \n";
        }
        catch (MnsException $e)
        {
            echo "DeleteMessage Failed: " . $e;
            return;
        }	
	}
	
	public function PeekMessage() {
		try
        {
            $res = $queue->deleteMessage($receiptHandle);
            echo "DeleteMessage Succeed! \n";
        }
        catch (MnsException $e)
        {
            echo "DeleteMessage Failed: " . $e;
            return;
        }	
	}
	
	public function BatchPeekMessage() {
		try
        {
            $res = $queue->deleteMessage($receiptHandle);
            echo "DeleteMessage Succeed! \n";
        }
        catch (MnsException $e)
        {
            echo "DeleteMessage Failed: " . $e;
            return;
        }	
	}
	
	public function ChangeMessageVisibility() {
		try
        {
            $res = $queue->deleteMessage($receiptHandle);
            echo "DeleteMessage Succeed! \n";
        }
        catch (MnsException $e)
        {
            echo "DeleteMessage Failed: " . $e;
            return;
        }	
	}
}