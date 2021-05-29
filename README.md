# iot推送消息组件

## 介绍
jdmm/noticepush是一个基于saber的组件，目前仅支持post

## 安装

```bash
composer require jdmm/noticepush
```
## 注意
添加配置文件（以下为测试数据）
```php
url: 39.96.31.118:8000
appkey: APP000005
masterSecret: 3333333333333333
pushUrl: /jdiot/api/push/push  //可以在这里定义也可以在代码里写
```

## 使用

### 发布消息

发布消息
```
<?php

$path = config('iot_api.pushUrl');
$mac = '98:98:93:00:00:08';
$appkey = config('iot_api.appkey');
$masterSecret = config('iot_api.masterSecret');
//如要iot其他参数放到 $message
$message = [
  'content' => 'teadgfdafdas'
];
$push = new NoticePush();
$res = $push->iotPost(config('iot_api.url').$path,$mac,$message,$appkey,$masterSecret);
```
返回格式
 ```php
 $res = [
 	'statusCode' => 200, // http状态码
	'body' => []  //从接口返回的内容
 ];
 ```