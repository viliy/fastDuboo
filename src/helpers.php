<?php

/**
 * @param string $provider
 * @throws \Icecave\Flax\Exception\EncodeException
 * @throws \Psr\Cache\InvalidArgumentException
 * @return string
 * @throws Exception
 */
function duboo_buffer(string $provider, array $params)
{
    $cacheItem = cache()->getItem('zk.' . $provider);
    $uriData = $cacheItem->get();

    var_dump($uriData, config()->get('dobbo.options.prefix') . $provider);
    $providerConfig = config()->get("dubbo.projects.$provider");

    /**
     * @var $dubbo \Zhaqq\FastDubbo\Tools\Dubbo
     */
    $dubbo = new \Zhaqq\FastDubbo\Tools\Dubbo();

    $dubbo->setVersionPath(
        $providerConfig['uri']['path'],
        $providerConfig['uri']['version']
    );

    $dubbo->parseURItoProps($uriData);

    swoole_clent_duboo()->connect($dubbo->host(), $dubbo->port(), 1);

    $data =  $dubbo->buffer(
        $providerConfig['object']['method'],
        [\Zhaqq\FastDubbo\Tools\JavaType::object($providerConfig['object']['name'], $params)]
    );

    var_dump($data);

    return $data;
}


function swoole_clent_duboo()
{
    return app()->get(\Zhaqq\FastDubbo\Client::class);
}