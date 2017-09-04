<?php

namespace Laggards\Aliyun;

require_once('lib/mns-autoloader.php');

use AliyunMNS\Client;
use AliyunMNS\Topic;
use AliyunMNS\Constants;
use AliyunMNS\Model\MailAttributes;
use AliyunMNS\Model\SmsAttributes;
use AliyunMNS\Model\BatchSmsAttributes;
use AliyunMNS\Model\MessageAttributes;
use AliyunMNS\Exception\MnsException;
use AliyunMNS\Requests\PublishMessageRequest;

//use AliyunMNS\Exception\MnsException;

class MNS
{
    private $accessId;
    private $accessKey;
    private $endPoint;
    private $queue;
    public $client;

    public function __construct()
    {
        $this->accessId = config('aliyun.mns.AccessKeyId');
        $this->accessKey = config('aliyun.mns.AccessKeySecret');
        $this->endPoint =  config('aliyun.mns.Endpoint');
        $this->queue = config('aliyun.mns.Queue');
        $this->client = new Client($this->endPoint, $this->accessId, $this->accessKey);
        $this->queue = $this->client->getQueueRef($this->queue);
    }

    public function SendSMSMessage($receiver,$templateCode,$smsParams)
    {
        $topic = $this->client->getTopicRef(config('aliyun.sms.TopicId'));
        foreach($smsParams as &$param){
          $param = (string)$param;
        }
        $messageAttributes = new SmsAttributes(config('aliyun.sms.Sign'), $templateCode, $smsParams, $receiver);
        $messageBody = "smsmessage";
        $request = new PublishMessageRequest($messageBody, new MessageAttributes($messageAttributes));
        try
        {
            $res = $topic->publishMessage($request);
            return true;
        }
        catch (MnsException $e)
        {
            return false;
        }
    }

    public function SendMessage($messageBody)
    {
        // as the messageBody will be automatically encoded
        // the MD5 is calculated for the encoded body
        $bodyMD5 = md5(base64_encode($messageBody));
        $request = new SendMessageRequest($messageBody);
        try {
            $res = $this->queue->sendMessage($request);
            return true;
        } catch (MnsException $e) {
            echo "SendMessage Failed: " . $e;
            return;
        }
    }

    public function BatchSendMessage()
    {
        try {
            $res = $queue->deleteMessage($receiptHandle);
            echo "DeleteMessage Succeed! \n";
        } catch (MnsException $e) {
            echo "DeleteMessage Failed: " . $e;
            return;
        }
    }

    public function ReceiveMessage()
    {
        $receiptHandle = null;
        try {
            $res = $this->queue->receiveMessage();
            $receiptHandle = $res->getReceiptHandle();
            $MessageBody = $res->getMessageBody();
            return array('MessageBody'=>$MessageBody,'receiptHandle'=>$receiptHandle);
        } catch (MnsException $e) {
            echo "ReceiveMessage Failed: " . $e;
            return;
        }
    }

    public function BatchReceiveMessage()
    {
        try {
            $res = $queue->deleteMessage($receiptHandle);
            echo "DeleteMessage Succeed! \n";
        } catch (MnsException $e) {
            echo "DeleteMessage Failed: " . $e;
            return;
        }
    }

    public function DeleteMessage($receiptHandle)
    {
        try {
            $res = $queue->deleteMessage($receiptHandle);
            return true;
        } catch (MnsException $e) {
            echo "DeleteMessage Failed: " . $e;
            return;
        }
    }

    public function BatchDeleteMessage()
    {
        try {
            $res = $queue->deleteMessage($receiptHandle);
            echo "DeleteMessage Succeed! \n";
        } catch (MnsException $e) {
            echo "DeleteMessage Failed: " . $e;
            return;
        }
    }

    public function PeekMessage()
    {
        try {
            $res = $queue->deleteMessage($receiptHandle);
            echo "DeleteMessage Succeed! \n";
        } catch (MnsException $e) {
            echo "DeleteMessage Failed: " . $e;
            return;
        }
    }

    public function BatchPeekMessage()
    {
        try {
            $res = $queue->deleteMessage($receiptHandle);
            echo "DeleteMessage Succeed! \n";
        } catch (MnsException $e) {
            echo "DeleteMessage Failed: " . $e;
            return;
        }
    }

    public function ChangeMessageVisibility()
    {
        try {
            $res = $queue->deleteMessage($receiptHandle);
            echo "DeleteMessage Succeed! \n";
        } catch (MnsException $e) {
            echo "DeleteMessage Failed: " . $e;
            return;
        }
    }
}
