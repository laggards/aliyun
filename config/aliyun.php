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
        'version' => 'latest',
		'AccessKeyId' => env('MNS_AK', ''),
		'AccessKeySecret' => env('MNS_AKS', ''),
		'Endpoint' => env('MNS_Endpoint', ''),
		'EndpointInternal' => env('MNS_Endpoint_INTERNAL', ''),
    ],
	'oss' => [
		'AccessKeyId' => env('OSS_AK', ''),
		'AccessKeySecret' => env('OSS_AKS', ''),
		'Bucket' => env('OSS_Bucket', ''),
		'Endpoint' => env('OSS_Endpoint', ''),
		'EndpointInternal' => env('OSS_Endpoint_INTERNAL', ''),
		'Domain' => env('OSS_DOMAIN', ''),
    ],
];