## Aliyun Service for Larave

提供阿里云OSS、MNS、Memcahe服务接口

## 安装

### 通过 Composer 安装

```sh
composer require laggards/aliyun
```

### 编辑 config/app.php 注册 providers 和 aliases

```php
'providers' => [
    Laggards\Aliyun\AliyunServiceProvider::class,
]
```

```php
'aliases' => [
    'Aliyun' => Laggards\Aliyun\Facades\Aliyun::class,
]
```

### 生成配置文件

```sh
php artisan vendor:publish --provider="Laggards\Aliyun\AliyunServiceProvider"
```
将在config文件夹中生成一个aliyun.php的配置文件，在该配置文件中填入相关信息。

## 使用方法及代码示例

### MNS
阿里云MNS除提供消息中间件服务外同时具备发送短信（SMS）的能力，使用方法：
 - 通过 createClient() 方法实例化一个mns对象
 - 调用该对象的SendSMSMessage()方法即可发送SMS消息到指定手机号

 ```php
 use Aliyun;

 $mns = Aliyun::createClient('mns');    
 $ret = $mns->SendSMSMessage(13888888888,'SMS_14695416',['customer'=>'测试']);   
 // 成功返回 True， 失败返回False
 ```


## 目前支持的阿里云服务

SDK 目前支持下列服务:

| 服务名  | 类名  | API 版本 | 服务介绍
| :------------ |:---------------:| -----:| -----:|
| Aliyun OSS    | ALY.OSS | 2017-09-01 | [OSS](https://www.aliyun.com/product/oss) |
| Aliyun MNS    | ALY.MNS | 2017-09-01 | [MNS](https://www.aliyun.com/product/mns) |
| Aliyun CDN    | ALY.CDN | 2017-09-01 | [CDN](https://www.aliyun.com/product/cdn) |
| Aliyun Memcache | ALY.Memcache | 2017-09-01 | [Memcache](https://www.aliyun.com/product/ocs) |


#### 使用过程中发现问题，发送issues。

## License

This SDK is distributed under the
[Apache License, Version 2.0](http://www.apache.org/licenses/LICENSE-2.0).
