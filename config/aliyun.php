<?php
//use Laggards\Aliyun;

return [
    /*
    |--------------------------------------------------------------------------
    | Aliyun Configuration
    |--------------------------------------------------------------------------
    |
    */
    'mns' => [
        'version' => env('MNS_Version', ''),
        'Queue' => env('MNS_Queue', ''),
        'AccessKeyId' => env('MNS_AK', ''),
        'AccessKeySecret' => env('MNS_AKS', ''),
        'Endpoint' => env('MNS_Endpoint', ''),
        'EndpointInternal' => env('MNS_Endpoint_INTERNAL', ''),
    ],
    'sms' => [
        'TopicId' => env('SMS_Topic', ''),
        'Sign' => env('SMS_Sign', ''),
    ],
    'oss' => [
        'AccessKeyId' => env('OSS_AK', ''),
        'AccessKeySecret' => env('OSS_AKS', ''),
        'Bucket' => env('OSS_Bucket', ''),
        'Endpoint' => env('OSS_Endpoint', ''),
        'EndpointInternal' => env('OSS_Endpoint_INTERNAL', ''),
        'Domain' => env('OSS_DOMAIN', ''),
    ],
    'memcache' => [
        'Host' => env('Memcache_HOST', ''),
        'Port' => env('Memcache_PORT', ''),
        'Weight' => env('Memcache_WEIGHT', ''),
        'UserName' => env('Memcache_UserName', ''),
        'PassWord' => env('Memcache_PassWord', ''),
    ],
    'cdn' => [
        'Url' => env('CDN_URL', ''),
        'Origin' => env('CDN_ORIGIN', ''),
        'ImgUrl' => env('CDN_IMG', ''),
    ],
];
