# Dubbo for FastD

[Docs](https://www.zhaqq.top/posts/8)


关于php swoole请求dubbo的案例实在太少，长连接的案例更少，或者实现方式个人不是很喜欢，所以查阅了下资料，写了一个swoole下长连接请求dubbo的案

!> 写着写着感觉要成服务发现了

## 功能

### 1. zookeeper 定时缓存进程

已实现，

> todo:  后续应该需要的实现的
>>  考虑zookeeper->watch() 实时更新节点
>>  动态调用zookeeper进程更新节点

### 2. swoole_client 同步客户端

已实现

### 3. swoole_client 异步客户端

后续完善基本是需要使用到异步客户端的，时间有限

> todo
>> 心跳
>> 释放
>> 重连

### 4. rpc 请求方式

已实现

```php


// $service  为dubbo服务昵称 config/duboo.php,projects下键值

$data = swoole_client_duboo()->$service($params)

```

## 使用

install

```php

composer require zhaqq/fast-dubbo

```

### usage FastD

* 配置
```shell
cp vendor/zhaqq/fast-dubbo/src/Config/dubbo.php Config/dubbo.php

```
vim config/app.php

```php

    'services' => [
        \FastD\ServiceProvider\CacheServiceProvider::class,
        \FastD\ServiceProvider\LoggerServiceProvider::class,
        \FastD\ServiceProvider\RouteServiceProvider::class,
        
        // add Dubbo
        \Zhaqq\FastDubbo\Providers\DubboServiceProvider::class,
    ],

```

vim config/Server.php

```php

    'processes' => [
        // add
       Zhaqq\FastDubbo\Process\StorageProcess::class
    ],

```

* usage

```php
    
    // use for helpers
    $data = swoole_invoke_to_json($provider_name, $params);

    // or
    $data = swoole_client_duboo()->$provider_name($params);
    return \Zhaqq\FastDubbo\Tools\Json::decode($data);

    
    // or
    $data = swoole_client_duboo()->invoke($provider_name, $params);
    return \Zhaqq\FastDubbo\Tools\Json::decode($data);
    
```

### other

```php
require __DIR__ . '/vendor/autoload.php';

// todo

```
