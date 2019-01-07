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
    $cacheItem = cache()->getItem(config()->get('dubbo.options.prefix') . $provider);
    $uriData = $cacheItem->get();

    $providerConfig = config()->get("dubbo.projects.$provider");

    /**
     * @var $dubbo \Zhaqq\FastDubbo\Tools\Dubbo
     */
    $dubbo = app()->get(\Zhaqq\FastDubbo\Tools\Dubbo::class);

    $dubbo->setVersionPath(
        $providerConfig['uri']['path'],
        $providerConfig['uri']['version']
    );

    $dubbo->parseURItoProps($uriData);

    swoole_clent_duboo()->connect($dubbo->host(), $dubbo->port(), 1);

    $data = $dubbo->buffer(
        $providerConfig['object']['method'],
        [\Zhaqq\FastDubbo\Tools\JavaType::object($providerConfig['object']['name'], $params)]
    );

    return $data;
}


function swoole_clent_duboo()
{
    return app()->get(\Zhaqq\FastDubbo\Client::class);
}

/**
 * @param string $service
 * @param array $params
 * @return array
 */
function swoole_invoke_to_json(string $service, array $params)
{

    $data = swoole_clent_duboo()->$service($params);

    return \Zhaqq\FastDubbo\Tools\Json::decode($data);
}