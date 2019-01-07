# Dubbo for FastD

install

```php

composer require zhaqq/fast-dubbo

```

## usage FastD

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
